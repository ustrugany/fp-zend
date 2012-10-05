<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Row
 *
 * @author = "piter";
 */
class Application_Model_DbTable_City_Row extends Zend_Db_Table_Row {
    
    protected $_stringTemplate = "Miasto: %s, pogoda: %s";


    public function __toString()
    {
        $result = (string) $this->name;
        $weathers = $this->findDependentRowset('Application_Model_DbTable_CityWeather');
        if($weathers->count())
        {
            $result .= " pogoda: {$weathers->current()->value} ";
        }
        return $result;
    }
    
    public function getCountry()
    {
        return $this->findParentRow('Application_Model_DbTable_Country');
    }
}

?>
