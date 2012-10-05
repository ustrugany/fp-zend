<?php

class Application_Model_DbTable_City extends Zend_Db_Table_Abstract
{
    protected $_rowClass = 'Application_Model_DbTable_City_Row';
    protected $_name = 'cities';
    protected $_dependantTables = array('Application_Model_DbTable_CityWeather');
    protected $_referenceMap = array(
        'country' => array(
                'columns' => array('country_id'),
                'refTableClass' => 'Application_Model_DbTable_Country',
                'refTableColumns' => array('id')
        )      
    );
    
    public function getCityWeathers()
    {
        $weathers = $this->findDependentRowset('Application_Model_DbTable_CityWeather');
        return $weathers;
    }
    
    public function getLastCityWeather()
    {
        return $this->getCityWeathers()->current();
    }
}

