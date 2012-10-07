<?php

class Application_Form_City extends Zend_Form
{

    public function init()
    {
        $country = new Application_Model_DbTable_Country;
        $countries = $country->fetchAllOrderedByName();
        $countriesForSelect = array();
        
        foreach($countries as $countryElement){
            $countriesForSelect[$countryElement->name] = $countryElement->name;
        }
        
        $select = new Zend_Form_Element_Select('country', 
        array(
                'label' => 'Państwo',
                'multiOptions' => $countriesForSelect
        ));
        
        $this->setMethod('post');
        $this->addElement($select);
        $this->addElement('text', 'name', array('label' => 'Nazwa'));
        $this->addElement('submit', 'submit', array('label' => 'Zapisz'));
    }


}

