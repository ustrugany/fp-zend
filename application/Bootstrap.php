<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRemoveDefaultRoutes()
    {
        $this->bootstrap('router');
        /* @var $router Zend_Controller_Router_Rewrite */
        $router = $this->getResource('router');
        // usuwa trasy w postaci /kontroler/akcja
//        $router->removeDefaultRoutes();
        $standard_route = new Zend_Controller_Router_Route(
            'zamowienie/lista/:page',
            array('controller'=>'order',
                    'action' => 'list',
                    'page' => NULL
            )
        );
        $router->addRoute('order_list_pagination', $standard_route);
        
        $this->bootstrap('view');
        $view = $this->getResource('view');
        
        $this->bootstrap('page');
        $page = $this->getResource('page');
        $view->headLink()->appendStylesheet($view->baseUrl('/css/ie.css'), 'screen', 'IE');
        
		// poprawka dla hostingu bez mod_rewrite
        if(($_SERVER["SERVER_ADDR"] == "85.199.177.117") && ($_SERVER["SERVER_NAME"] == "aplikacja.babybuu.de")){
            $this->bootstrap(array('frontController'));
            $front = $this->getResource('frontController');
            $front->setBaseUrl('/index.php');
        }
        
        return $router;
    }
    
    protected function _initProfiler(){
//        $this->bootstrap('DB');
	    // pobieramy lub tworzymy obiekt z konfiguracją bazy danych
	    // polecam uzależnienie dodania profilera od lokalizacji aplikacji
	    if (in_array(APPLICATION_ENV, array('development', 'testing'))) {
//	        $db = Zend_Registry::get('Db');
//            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
//            var_dump($db);exit;
//	        $db->getConnection();
	        // jeśli łączymy się z kilkoma bazami danych, to warto je pogrupować
//	        $profiler = new Zend_Db_Profiler_Firebug('NAZWA_GRUPY');
////	        $profiler->setEnabled(true);
//	        $db->setProfiler($profiler);
	    }
	}
}

