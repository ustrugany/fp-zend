<?php

class Application_Form_DialogDelete extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'dialog-delete');
        $this->addElement('submit', 'submit', array('label' => 'Usuń'));
        $this->addElement('submit', 'cancel', array('label' => 'Anuluj'));
    }


}

