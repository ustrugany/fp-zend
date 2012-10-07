<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Action
 *
 * @author = "piter";
 */
class My_Controller_Action extends Zend_Controller_Action {
    
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
    protected function writeSOAPConfig($options)
    {
        $config = Zend_Registry::get('config');
        $configPath = Zend_Registry::get('configPath');
        $oldOptions = $config->production->SOAP->toArray();
        $newOptions = array_merge($oldOptions, $options);
        $config->production->SOAP =  $newOptions;
        
        $writer = new Zend_Config_Writer_Ini(array('config'   => $config, 'filename' => $configPath));
        $writer->write();
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

?>
