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
class Application_Model_DbTable_Country_Row extends Zend_Db_Table_Row {

    public function __toString()
    {
        return $this->name;
    }
    
    public function getCities()
    {
        return $this->findDependentRowset('Application_Model_DbTable_City');
    }
}

?>
