<?php

class Application_Form_Options extends Zend_Form
{

    public function init()
    {   
        $this->setMethod('post');
        $this->addElement('text', 'url', array('label' => 'URL Webservice'));
        $this->addElement('text', 'timeout', array('label' => 'Timeout Webservice'));
        $this->addElement('submit', 'submit', array('label' => 'Zapisz'));
    }


}

