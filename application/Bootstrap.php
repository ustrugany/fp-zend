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
        
        return $router;
    }
    
    protected function _initIEFeatures()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        
//        $this->bootstrap('page');
//        $page = $this->getResource('page');
        $view->headLink()->appendStylesheet($view->baseUrl('/css/ie.css'), 'screen', 'IE');
        $view->headScript()->appendFile('http://html5shim.googlecode.com/svn/trunk/html5.js', 'text/javascript', array('conditional' => 'IE'));
    }
    
    protected function _initProfiler()
    {
        $this->bootstrap('db');
        $db = $this->getResource('db');
	    // pobieramy lub tworzymy obiekt z konfiguracją bazy danych
	    // polecam uzależnienie dodania profilera od lokalizacji aplikacji
	    if (in_array($this->getEnvironment(), array('development', 'testing'))) {
            
	        // jeśli łączymy się z kilkoma bazami danych, to warto je pogrupować
	        $profiler = new Zend_Db_Profiler_Firebug('Profiler bazadanych - FP');
            $profiler->setEnabled(true);
//            $this->getPluginResource('db')->getDbAdapter()->setProfiler($profiler);
            $db->setProfiler($profiler);
	    }
	}
    
    protected function _initLogs()
    {
        $logger = new Zend_Log();
        if (in_array($this->getEnvironment(), array('development', 'testing'))) {
            $writer = new Zend_Log_Writer_Firebug();
        }
        $logger->addWriter($writer);
        Zend_Registry::set('logger', $logger);
        return $logger;
    }
    
    protected function _initSession()
    {
        Zend_Session::start();
        $publicSession = new Zend_Session_Namespace('public');
        Zend_Registry::set('session', $publicSession);
        return $publicSession;
    }
    
    protected function _initCache()
    {
        $frontendOptions = array(
            'lifetime' => 60, // 7200 2h
            'automatic_serialization' => true
        );

        $backendOptions = array( 'cache_dir' => APPLICATION_PATH . '/../cache');
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
        return $cache;
    }
    
    protected function _initApplicationConfig()
    {
        $configPath = APPLICATION_PATH . '/configs/application.ini';
        $config = new Zend_Config_Ini($configPath, null, array(
                    'skipExtends'        => true,
                    'allowModifications' => true));
        
        Zend_Registry::set('configPath', $configPath);
        Zend_Registry::set('config', $config);
        
        return $config;
    }
}