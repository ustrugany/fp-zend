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
class Zend_View_Helper_LoopIndexClass extends Zend_View_Helper_Url{
    
    public function loopIndexClass($index, $count){
        $result = array();
        $odd_class = "odd";
        $even_class = "even";
        $first_class = "first";
        $last_class = "last";
        
        if((($index + 1) % 2) == 0){
            $result[] = $even_class;
        } else {
            $result[] = $odd_class;
        }
        
        if($index == 0){
            $result[] = $first_class;
        }
        
        if($index == ($count - 1)){
            $result[] = $last_class;
        }
        
        return implode(" ", $result);
    }
}

?>
