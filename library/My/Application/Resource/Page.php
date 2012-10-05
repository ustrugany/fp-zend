<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author = "piter";
 */
class My_Application_Resource_Page extends Zend_Application_Resource_ResourceAbstract{
    
    protected $_page;
    
    public function getPage(){
        if(null === $this->_page){
            $this->_page = new My_Page($this->getOptions());
        }
        return $this->_page;
    }
    
    public function init(){
        $page = $this->getPage();
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap(array(
                'layout',
                'view',
                'frontController'
        ));
        
        /* @var $front Zend_Controller_Front */
        $front = $bootstrap->getResource('frontController');
        /* @var $layout Zend_Layout */
        $layout = $bootstrap->getResource('layout');
        /* @var $view Zend_View */
        $view = $bootstrap->getResource('view');
        
        $request = new Zend_Controller_Request_Http;
        $front->setRequest($request);
        $baseUrl = $request->getBaseUrl();
        $view->headTitle()
                ->setDefaultAttachOrder($page->getTitleDefaultAttachOrder())
                ->setSeparator($page->getTitleSeparator())
                ->headTitle($page->getTitleContent());
        
        foreach($page->getCss() as $css){
            if(isset($css['media'])){
                $view->headLink()->appendStylesheet($baseUrl . $css['href'],
                        $css['media']);
            } else {
                $view->headLink()->appendStylesheet($baseUrl . $css['href']);
            }
        }
        
        foreach($page->getJs() as $js){
            $view->headScript()->appendFile(
                    $baseUrl . $js, 'text/javascript');
        }
        
        if($keywords = $page->getKeywords()){
            $view->headMeta()->appendName('keywords', $keywords);
        }
        
        if($description = $page->getDescription()){
            $view->headMeta()->appendName('description', $description);
        }
        
        $extension = $page->getExtension();
        if($extension != 'phtml'){
            $layout->setViewSuffix($extension);
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setViewSuffix($extension);
            $viewRenderer->setView($view);
        }
        
        return $page;
    }
    
}

?>
