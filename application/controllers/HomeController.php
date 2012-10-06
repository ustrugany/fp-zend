<?php

class HomeController extends Zend_Controller_Action
{
    /**
     * Flaga niedostepnosci API
     * @var type 
     */
    protected $_API_unavailable = false;
    
    /**
     * Klasa API do obslugi webservive pogody
     * @var type 
     */
    protected $_API_Weather = null;
    
    /**
     *  Helper flashmessager
     */
    protected $_flashMessenger = null;
    
    /**
     * Logger
     * @var type 
     */
    protected $_logger = null;
    
    /**
     * Przelacznik kontekstu (do przelaczania
     * formatu odpowiedzi w przypadku zadan AJAXowych)
     * @var type 
     */
    
    protected $_contextSwitch = null;
    /**
     * Alternatywny przelacznik kontekstu
     * @var Zend_ 
     */
    protected $_ajaxContext = null;
    
    /**
     * Sesja
     * @var Zend_Session_Namespace
     */
    protected $_session = null;
    
    /**
     * Cache 
     * UWAGA: problem z cachowaniem obiektow rekordu App...Row i
     * zestawów rekordów App...Rowset. Sprawdzić czy to się da w przyszlosci
     * w ogole zrealizowac.
     * @var Zend_Cache
     */
    protected $_cache = null;
    
    public function init()
    {
        $this->_contextSwitch = $this->_helper->getHelper('contextSwitch');
        $this->_ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->_logger = Zend_Registry::get('logger');
//        $this->_ajaxContext->addActionContext('index', 'html')
//                    ->addActionContext('get-cities', 'json')
//                    ->initContext();
        $this->_contextSwitch
             ->addActionContext('get-cities', 'json')
             ->addActionContext('get-city-weather', 'json')
                ->setAutoJsonSerialization(false)
                    ->initContext();
                
        $this->_flashMessenger =
            $this->_helper->getHelper('FlashMessenger');
        
        $this->initView();
        $this->writeSOAPConfig(array());
        
        $this->_cache = Zend_Registry::get('cache');
        $this->_session = Zend_Registry::get('session');
        
        $config = Zend_Registry::get('config');
        $SOAPoptions = $config->production->SOAP;
        
        try{
            $this->_API_Weather = API_Weather::instance($SOAPoptions->toArray());
        } catch(Exception $e){
            echo $e->getMessage();
            $this->_logger->error($e);
            $this->_API_unavailable = true;
        }
    }
    
    /**
     * Nadpisuje konfiguracje API
     * @param type $options 
     */
    protected function writeSOAPConfig($options = array())
    {
        $config = Zend_Registry::get('config');
        $configPath = Zend_Registry::get('configPath');
        
        $options = array(
            'url' => 'http://www.webservicex.com/globalweather.asmx?WSDL',
            'timeout' => 120
        );
        $oldOptions = $config->production->SOAP->toArray();
        $newOptions = array_merge($oldOptions, $options);
        $config->production->SOAP =  $newOptions;
        
        $writer = new Zend_Config_Writer_Ini(array('config'   => $config, 'filename' => $configPath));
        $writer->write();
    }

    /*
     * Glowna akcja
     */
    public function indexAction()
    {
        $this->fetchAndPassCountries();
//        $countryName = $this->getRequest()->get('country', null);
//        $cityName = $this->getRequest()->get('city', null);
//        if(!is_null($countryName)){
//            $cities = $this->getCitiesByCountryName($countryName);
//            $this->view->cities = $cities;
//            if(!is_null($cityName)){
//                $cityWeather = $this->getCityWeatherWithSOAP($cityName, $countryName);
//                $this->view->weather = $cityWeather;
//            }
//        } else {
//            $this->_flashMessenger->setNamespace('info')->addMessage('Wybierz kraj');
//        }
//        $this->getAPICities('Germany');
//        $this->getAPIWeather('Berlin', 'Germany');
//        $logger = Zend_Registry::get('logger');
//        $logger->warn('Testowe ostrzeżenie loggera');
//        $logger->info('Testowe info loggera');
//        $logger->err('Testowy błąd loggera');
    }
    
//    protected function getCountryCitiesCacheKey($countryName)
//    {
//        return "cached_cities_for_{$countryName}_country";
//    }

    /**
     * Pobiera miasta z bazy danych w danym porzadku i przekazuje do widoku
     */
    public function fetchAndPassCountries()
    {
        $Country = new Application_Model_DbTable_Country;
        $this->view->countries = $Country->fetchAllOrderedByName();
    }
    
    /**
     * Pobiera miasta dla danego kraju przez API/Webservice
     * @param type $country
     * @return array 
     */
    protected function getCitiesByCountryWithAPI($country)
    {
        $result = $this->_API_Weather->requestCitiesByCountry($country);
        return $result;
    }
    
    /**
     * Pobiera miasta dla danego kraju przekazanego
     * w zadaniu 
     */
    public function getCitiesAction()
    {
        $countryName = $this->getRequest()->get('country');
        $cities = null;
        
        if(is_string($countryName)){
            $cities = $this->getCitiesByCountryName($countryName);
        }
        
        if(is_null($cities)){
            $this->view->success = false;
        }
        
        $this->_logger->info("getCitiesAction[$countryName]");
        $this->_logger->info($cities);
        
        $this->view->cities = $cities;
    }
    
    /**
     * Akcja pobierania pogody dla danego miasta 
     */
    public function getCityWeatherAction()
    {
        $countryName = $this->getRequest()->get('country');
        $cityName = $this->getRequest()->get('city');
        
        try{
            $cityWeather = $this->getCityWeatherWithAPI($cityName, $countryName);
            $cityWeather = $this->view->partial('weather/info.phtml', array('weather' => $cityWeather));
            $CityWeather = new Application_Model_DbTable_CityWeather;
            $cityWeather = $CityWeather->createCityWeather($cityName, $cityWeather);
            $this->view->sourceInformation = "Źródło: webservice";
        } catch(Exception $e){
            $this->_API_unavailable = true;
            $this->_logger->err($e->getMessage());
            $cityWeather = $this->getCityWeatherFromDb($cityName);
            $this->view->sourceInformation = "Źródło: Baza danych ({$cityWeather->insert_time})";
        }
        
        $weatherToPassToView = $cityWeather->value;;
        
        if(is_null($cityWeather)){
            $weatherToPassToView = "Przepraszamy. API niedostępne/brak informacji pogodowych dla \"$cityName\" w bazie danych.";
            $this->view->success = false;
        } else {
            $weatherToPassToView = $cityWeather->value;
        }
        
        $this->_logger->info("getCityWeatherAction[$cityName, $countryName]: {$weatherToPassToView}");
        $this->view->weather = $weatherToPassToView;
    }
    
    /**
     * Pobiera miasta dla danego kraju
     * @param string $countryName
     * @return Application_Model_DbTable_City_Rowset/null 
     */
    protected function getCitiesByCountryName($countryName)
    {
        $cities = null;
        $Country = new Application_Model_DbTable_Country;
        $country = $Country->findCountryByName($countryName);
        
        if($country instanceof Application_Model_DbTable_Country_Row){
//            if(($cached_cities = $this->_cache->load($this->getCountryCitiesCacheKey($countryName))) == false){
                $cities = $country->getCities();
                if($cities->count() == 0){
//                    $countryRow = $Country->replaceCountry($countryName);
                    $cities = $this->getCitiesByCountryWithAPI($countryName);
                    if(!empty($cities)){
                        Zend_Registry::get('logger')->info('got cities by SOAP');
                        $cities = $Country->createCountryCitiesFromArray($country, $cities);
                    }
                }
                else{
                    Zend_Registry::get('logger')->info('got cities from DB');
                }
//                $this->_cache->save($cities->toArray(), $this->getCountryCitiesCacheKey($countryName));
//            } else {
//                Zend_Registry::get('logger')->info('got cities from Cache');
//                $cities = $cached_cities;
//                $tmp = array();
//                foreach($cities as $city){
//                    $row = new Application_Model_DbTable_City_Row;
//                    $row->setFromArray($city);
//                    $tmp[] = $row;
//                }
//                $cities = $tmp;
//            }
        }
        
        return $cities;
    }
    
    /**
     * Pobiera pogode dla miasta z bazy danych
     * @param string $cityName
     * @return Application_Model_DbTable_CityWeather_Row
     */
    protected function getCityWeatherFromDb($cityName)
    {
        $cityWeather = null;
        $City = new Application_Model_DbTable_City;
        $city = $City->findCityByName($cityName);
        if($city instanceof Application_Model_DbTable_City_Row){
            $cityWeather = $city->getLastCityWeather();
        }
        $this->_logger->info("getCityWeatherFromDb[$cityName]: $cityWeather");
        return $cityWeather;
    }
    
    /**
     * Pobiera pogode poprzez API/Webservice
     * @param string $cityName
     * @param string $countryName
     * @return array
     */
    protected function getCityWeatherWithAPI($cityName, $countryName)
    {
        $cityWeather = $this->_API_Weather->requestCityWeather($cityName, $countryName);
        $this->_logger->info("getCityWeatherWithAPI[$cityName, $countryName]:" . var_export($cityWeather, true));
        
        return $cityWeather;
    }
    
}

