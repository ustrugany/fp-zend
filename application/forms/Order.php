<?php

class Application_Form_Order extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        $this->addElement('text', 'model', array('label' => 'Model'));
        $this->addElement('text', 'unique', array('label' => 'Numer zamówienia'));
        $this->addElement('text', 'ebay_id', array('label' => 'Numer ebay'));
        $this->addElement('text', 'invoice_id', array('label' => 'Numer faktury vat'));
        $this->addElement('text', 'color', array('label' => 'Kolor'));
        $this->addElement('text', 'frame', array('label' => 'Stelaż'));
        $this->addElement('radio', 'is_seat', array('label' => 'Fotelik', 'multiOptions' => array('0' => 'Nie', '1' => 'Tak')));
//        $this->addElement('radio', 'is_paid', array('label' => 'Opłacone', 'multiOptions' => array('0' => 'Nie', '1' => 'Tak')));
        $this->addElement('text', 'wheels', array('label' => 'Koła'));
        $this->addElement('textarea', 'bonus', array('label' => 'Dodatki', 'cols' => 50, 'rows' => 3));
        $this->addElement('textarea', 'client', array('label' => 'Klient', 'cols' => 50, 'rows' => 3));
        $this->addElement('text', 'date_of_payment', array('label' => 'Data opłacenia', 'value' => null));
        $this->addElement('text', 'date_of_receipt', array('label' => 'Data odbioru', 'value' => date('Y-m-d')));
        $this->addElement('submit', 'submit', array('label' => 'Zapisz'));
    }


}

