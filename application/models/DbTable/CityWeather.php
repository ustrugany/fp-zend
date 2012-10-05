<?php

class Application_Model_DbTable_CityWeather extends Zend_Db_Table_Abstract
{

    protected $_name = 'cities_weathers';
    protected $_referenceMap = array(
        'city' => array(
                'columns' => array('city_id'),
                'refTableClass' => 'Application_Model_DbTable_City',
                'refTableColumns' => array('id')
        )      
    );

}

