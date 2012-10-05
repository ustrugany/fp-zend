<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CellNullRenderer
 *
 * @author = "piter";
 */
class Zend_View_Helper_CellNullRenderer extends Zend_View_Helper_Url{
    
    protected $_emptyValueTemplate = "<span class=\"empty-cell\">brak</span>";
    
    public function cellNullRenderer($value){
        return empty($value) ?  $this->_emptyValueTemplate : $value;
    }
}

?>
