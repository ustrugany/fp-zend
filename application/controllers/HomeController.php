<?php

class HomeController extends Zend_Controller_Action
{

    protected $_flashMessenger = null;
    
    protected $_logger = null;
    
    protected $_contextSwitch = null;
    /**
     * 
     * @var Zend_ 
     */
    protected $_ajaxContext = null;
    /**
     *
     * @var Zend_Session_Namespace
     */
    protected $_publicSession = null;
    /**
     * 
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
                ->setAutoJsonSerialization(false)
                    ->initContext();
                
        $this->_flashMessenger =
            $this->_helper->getHelper('FlashMessenger');
        
        $this->initView();
        $this->writeSOAPConfig();
        
        $this->_cache = Zend_Registry::get('cache');
        $this->_session = Zend_Registry::get('session');
    }
    
    protected function writeSOAPConfig()
    {
        $config = Zend_Registry::get('config');
        $configPath = Zend_Registry::get('configPath');
        $config->production->SOAP = array(
                'url' => 'http://www.webservicex.com/globalweather.asmx?WSDL',
                'timeout' => 120
        );
        $writer = new Zend_Config_Writer_Ini(array('config'   => $config, 'filename' => $configPath));
        $writer->write();
    }

    public function indexAction()
    {
        $this->fetchAndPassCountries();
        $countryName = $this->getRequest()->get('country', null);
        $cityName = $this->getRequest()->get('city', null);
        if(!is_null($countryName)){
            $cities = $this->getCitiesByCountryName($countryName);
            $this->view->cities = $cities;
            if(!is_null($cityName)){
                $cityWeather = $this->getCityWeatherWithSOAP($cityName, $countryName);
                $this->view->weather = $cityWeather;
            }
        } else {
            $this->_flashMessenger->setNamespace('info')->addMessage('Wybierz kraj');
        }
//        $this->getAPICities('Germany');
//        $this->getAPIWeather('Berlin', 'Germany');
//        $logger = Zend_Registry::get('logger');
//        $logger->warn('Testowe ostrzeżenie loggera');
//        $logger->info('Testowe info loggera');
//        $logger->err('Testowy błąd loggera');
    }
    
    public function testAction()
    {
    }
    
    protected function getCountryCitiesCacheKey($countryName)
    {
        return "cached_cities_for_{$countryName}_country";
    }

    public function fetchAndPassCities()
    {
        $City = new Application_Model_DbTable_City;
        $this->view->cities = $City->fetchAll();
    }

    public function fetchAndPassCountries()
    {
        $Country = new Application_Model_DbTable_Country;
        $this->view->countries = $Country->fetchAllOrderedByName();
    }
    
    protected function getCitiesByCountryWithSOAP($country)
    {
        $config = Zend_Registry::get('config');
        $SOAPoptions = $config->production->SOAP;
        $WeatherAPI = My_WeatherAPI::instance($SOAPoptions->toArray());
        $result = $WeatherAPI->requestCitiesByCountry($country);
        return $result;
    }
    
    public function getCitiesAction()
    {
        $countryName = $this->getRequest()->get('country');
        $this->_logger->info($countryName);
        $cities = $this->getCitiesByCountryName($countryName);
        $this->_logger->info($cities);
//        $this->view->cities = $cities;
    }
    
    protected function getCitiesByCountryName($countryName)
    {
        $cities = null;
        $Country = new Application_Model_DbTable_Country;
        $country = $Country->findCountryByName($countryName);
        
        if($country instanceof Application_Model_DbTable_Country_Row){
//            if(($cached_cities = $this->_cache->load($this->getCountryCitiesCacheKey($countryName))) == false){
                $cities = $country->getCities();
                if($cities->count() == 0){
                    $countryRow = $Country->replaceCountry($countryName);
                    $cities = $this->getCitiesByCountryWithSOAP($countryName);
                    if(!empty($cities)){
                        Zend_Registry::get('logger')->info('got cities by SOAP');
                        $cities = $Country->createCountryCitiesFromArray($countryRow, $cities);
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
    
    protected function getCityWeatherWithSOAP($cityName, $countryName)
    {
        $config = Zend_Registry::get('config');
        $SOAPoptions = $config->production->SOAP;
        $WeatherAPI = My_WeatherAPI::instance($SOAPoptions->toArray());
        $result = $WeatherAPI->requestCityWeather($cityName, $countryName);
        return $result;
    }
    
    protected function createCityAndCityWeather($cityName, $cityWeather)
    {
        $City = new Application_Model_DbTable_City;
        $CityWeather = new Application_Model_DbTable_CityWeather;
        $cityName = $City->createRow($cityName);
        $cityName->save();
        $cityWeather['city_id'] = $cityName->id;
        $CityWeather->createRow($cityWeather)->save();
    }
}

