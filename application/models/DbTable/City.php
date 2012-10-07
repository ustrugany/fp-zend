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
    
    /**
     * Wyszukuje kraj na podstawie nazwy
     * @param type $countryName
     * @return type 
     */
    public function findCityByName($cityName)
    {
        $select = $this->select()->where('LOWER(name) = :name')->limit(1);
        $select->bind(array('name' => $cityName));
        return $this->fetchAll($select)->current();
    }
    
    public function fetchAllOrderedByName()
    {
        return $this->fetchAll(null, 'name', 'ASC');
    }
}