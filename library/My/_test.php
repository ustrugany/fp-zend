<?php /*
            $test = "<?xml version='1.0' standalone='yes'?><movies>
            <movie>
                <title>PHP: Behind the Parser</title>
                <characters>
                <character>
                    <name>Ms. Coder</name>
                    <actor>Onlivia Actora</actor>
                </character>
                </characters>
            </movie>
            <movie>
                <title>PHP and the DOM</title>
                <rating type='thumbs'>7</rating>
                <rating type='stars'>5</rating>
            </movie>
            </movies> ";
            $doc = new DOMDocument;
            $doc->loadXML($test);
            $xml = new DOMXPath($doc);
            $query = '//movies/movie/title'; 
            $result = $xml->query($query);
            var_dump($result);
            */ ?>

It is possible. Sample implementation in described in this blog post:

    Zend Framework: View Helper Priority Messenger | emanaton
    http://www.emanaton.com/code/php/zendprioritymessenger

Excerpt:

class AuthController extends Zend_Controller_Action {
  function loginAction() {
    . . .
    if ($this->_request->isPost()) {
      $formData = $this->_request->getPost();
      if ($this->view->form->isValid($formData)) {
        . . .
      } else {
        $this->view->priorityMessenger('Login failed.', 'error');
      }
    . . .
  }
