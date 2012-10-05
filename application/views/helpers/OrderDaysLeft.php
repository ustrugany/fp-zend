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
class Zend_View_Helper_OrderDaysLeft extends Zend_View_Helper_Url{
    
    protected $_daysForRealisation = 11;
    
    public function orderDaysLeft($dateOfPayment)
    {
        $days = null;
		$writer = new Zend_Log_Writer_Firebug();
		$logger = new Zend_Log($writer);
		$logger->log('orderDaysLeft: ' . $dateOfPayment, Zend_Log::INFO);
        
        if(!is_null($dateOfPayment) && ($dateOfPayment != "0000-00-00")){
            $now = Zend_Date::now();
            $then = new Zend_Date($dateOfPayment);
            $then =$then->addDay($this->_daysForRealisation);
            $difference = $then->sub($now);

            $measure = new Zend_Measure_Time($difference->toValue(), Zend_Measure_Time::SECOND);
            $measure->convertTo(Zend_Measure_Time::DAY);

            $days = floor($measure->getValue());
            $days = ($days < 0) ? 0 : $days;
        }
        
        return $days;
    }
}

?>
