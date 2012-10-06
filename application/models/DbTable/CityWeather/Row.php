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
class Application_Model_DbTable_CityWeather_Row extends Zend_Db_Table_Row {
    

    public function __toString()
    {
        $result = "";
        $city = $this->findParentRow('Application_Model_DbTable_City');
        $city = (string) $city;
        $result = "<h2>Pogoda dla $city: </h2> $this->value";
        return $result;
    }
}

?>
