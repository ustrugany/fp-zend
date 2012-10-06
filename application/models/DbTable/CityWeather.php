<?php

class Application_Model_DbTable_CityWeather extends Zend_Db_Table_Abstract
{
    protected $_rowClass = 'Application_Model_DbTable_CityWeather_Row';
    protected $_name = 'cities_weathers';
    protected $_referenceMap = array(
        'city' => array(
                'columns' => array('city_id'),
                'refTableClass' => 'Application_Model_DbTable_City',
                'refTableColumns' => array('id')
        )      
    );
    
    public function createCityWeather($cityName, $cityWeather)
    {
        $City = new Application_Model_DbTable_City;
        $city = $City->findCityByName($cityName);
        $weather = null;
        
        if($city instanceof Application_Model_DbTable_City_Row){
            $this->getAdapter()->beginTransaction();
            try{
                $deleted = $this->delete(array('city_id' => $city->id));
                $weather = $this->createRow(array('city_id' => $city->id, 
                        'value' => $cityWeather,
                        'insert_time' => date('Y-m-d H:i:s')));
                $weather->save();
                $this->getAdapter()->commit();
                
                $logger = Zend_Registry::get('logger');
            } catch (Exception $e){
                $this->getAdapter()->rollBack();
                throw $e;
            }
        }
        $logger->info('createCityWeather');
        $logger->info($weather);
        return $weather;
    }
}

