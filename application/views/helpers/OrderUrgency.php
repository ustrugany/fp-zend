<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderDaysLeft
 *
 * @author = "piter";
 */
class Zend_View_Helper_OrderUrgency extends Zend_View_Helper_Url{
    
    protected $_mediumUrgency = 7;
    
    protected $_highUrgency = 4;
    
    public function orderUrgency($daysLeft = null)
    {
        $urgency = 'undefined';
        if(!is_null($daysLeft)){
            if($this->_isLowUrgency($daysLeft)){
                $urgency = $this->_getLowUrgencyName();
            } else if($this->_isMediumUrgency($daysLeft)){
                $urgency = $this->_getMediumUrgencyName();
            } else if($this->_isHighUrgency($daysLeft)){
                $urgency = $this->_getHighUrgencyName();
            }
        }
        return $urgency;
    }
    
    protected function _isLowUrgency($daysLeft){
        return ($daysLeft > $this->_mediumUrgency);
    }
    
    protected function _isMediumUrgency($daysLeft){
        return ($daysLeft <= $this->_mediumUrgency && $daysLeft > $this->_highUrgency);
    }
    
    protected function _isHighUrgency($daysLeft){
        return ($daysLeft <= $this->_highUrgency);
    }
    
    protected function _getLowUrgencyname(){
        return 'low';
    }
    
    protected function _getMediumUrgencyname(){
        return 'medium';
    }
    
    protected function _getHighUrgencyname(){
        return 'high';
    }
}

?>
