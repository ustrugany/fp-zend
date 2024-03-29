<?php

class AdminController extends My_Controller_Action
{
    /**
     * Przed odpaleniem akcji
     * sprawdzamy czy nie wymaga autoryzacji
     * @return type 
     */
    public function preDispatch()
    {
        $auth = Zend_Auth::getInstance();
        if ((!in_array($this->getRequest()->getActionName(), array('index', 'login')))) {
            if(!$auth->hasIdentity()){
                return $this->_helper->redirector(
                    'index',
                    'admin',
                    'default'
                );
            }
        } else {
            if($auth->hasIdentity()){
                return $this->_helper->redirector(
                    'list-cities',
                    'admin',
                    'default'
                );
            }
        }
        $this->view->identity = $auth->getIdentity();
    }
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * Domyslna akcja 
     */
    public function indexAction()
    {
        $this->view->title = "Formularz logowania";
        $this->view->form = new Application_Form_Login();
    }
    
    /**
     * Akcja bslugi formularza logowania
     * @return type 
     */
    public function loginAction()
    {
        $this->_helper->viewRenderer('index');
        $form = new Application_Form_Login();
        if ($form->isValid($this->getRequest()->getPost())) {

            $adapter = new Zend_Auth_Adapter_DbTable(
                null,
                'user',
                'username',
                'password'
            );

            $adapter->setIdentity($form->getValue('username'));
            $adapter->setCredential($form->getValue('password'));

            $auth = Zend_Auth::getInstance();

            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                return $this->_helper->redirector(
                    'list-cities',
                    'admin',
                    'default'
                );
            }
            $form->password->addError('Błędna próba logowania!');
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_helper->redirector(
            'index',
            'admin',
            'default'
        );
    }
    
    /**
     * Akcja aktualizacji opcji SOAP
     * @return type
     * @throws Exception 
     */
    public function updateOptionsAction()
    {
        $this->view->title = "Edycja ustawień";
        $form = new Application_Form_Options;
        
        try{
            if($this->getRequest()->isPost()){
                if($form->isValid($this->getRequest()->getPost())){
                    $data = $form->getValues();
                    $this->writeSOAPConfig($data);
                    $this->_flashMessenger->setNamespace('info')->addMessage("Zaktualizowano opcje!");
                    return $this->_helper->redirector(
                            'list-cities', 'admin', null, array());
                }

            } else {
                $config = Zend_Registry::get('config');
                $data = $config->production->SOAP->toArray();
                $form = $form->populate($data);
            }

            $this->view->form = $form;
            
        } catch(Exception $e){
            throw $e;
        }
    }
    
    /**
     * Akcja tworzenia miasta
     * @return type 
     */
    public function createCityAction()
    {
        $this->view->title = "Tworzenie nowego miasta";
        
        $form = new Application_Form_City;
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getPost())){
                $data = $form->getValues();
                $model = new Application_Model_DbTable_Order();
                
                $data = $this->_filterDataForCRUD($data);
                
                $id = $model->insert($data);
                return $this->_helper->redirector(
                        'update', 'order', null, array('order_id' => $id));
            }
        }
        
        $this->view->form = $form;
    }
    
    /**
     * Akacja aktualizacja miasta
     * @return type
     * @throws Exception
     * @throws Zend_Controller_Action_Exception 
     */
    public function updateCityAction()
    {
        $id = $this->getRequest()->getParam('city_id');
        $this->view->title = "Edycja miasta <b>(id: {$id})</b>";
        
        $model = new Application_Model_DbTable_City();
        $form = new Application_Form_City;
        
        try{
            /* @var $cities Zend_Db_Table_Rowset */
            $cities = $model->find($id);
            
            if($cities->count()){
                $city = $cities->current();
                if($this->getRequest()->isPost()){
                    if($form->isValid($this->getRequest()->getPost())){
                        $data = $form->getValues();
                        $Country = new Application_Model_DbTable_Country;
                        $country = $Country->findCountryByName($data['country']);
                        $data['country_id'] = $country->id;
                        $city->setFromArray($data);
                        $city->save();
                    }
                    
                    $this->_flashMessenger->setNamespace('info')->addMessage("Zaktualizowano rekord!");
                    return $this->_helper->redirector(
                            'list-cities', 'admin', null, array());
                }
                
                $country = $city->getCountry();
                $city = $city->toArray();
                $city['country'] = $country->name;
                $this->view->form = $form->populate($city);
                $this->view->city = $city;
            } else {
                throw new Zend_Controller_Action_Exception(sprintf('Rekord o id "%s" nie istnieje', $id), 404);
            }
        } catch(Exception $e){
            throw $e;
        }

        $this->view->city_id = $id;
    }
    
    /**
     * Akcja wylistowania miast 
     */
    public function listCitiesAction()
    {
        $City = new Application_Model_DbTable_City;
        $cities = $City->fetchAllOrderedByName();
        $this->view->title = "Wylistowanie miast";
        $this->view->cities = $cities;
    }

    /**
     * Akcja usuwania miasta
     * @return type
     * @throws Exception
     * @throws Zend_Controller_Action_Exception 
     */
    public function deleteCityAction()
    {
        $id = $this->getRequest()->getParam('city_id');
        $this->view->title = "Usuwanie miasta <b>(id: {$id})</b>";
        
        $model = new Application_Model_DbTable_City();
        $form = new Application_Form_DialogDelete;
        
        try{
            /* @var $cities Zend_Db_Table_Rowset */
            $cities = $model->find($id);
            if($cities->count()){
                $city = $cities->current();
                $this->view->actionMessage = sprintf("Chcesz usunąć miasto \"%s\"?", $city->name);
                
                if($this->getRequest()->isPost()){
                    if($form->isValid($this->getRequest()->getPost())){
                        $submit = $form->getValue('submit');
                        $cancel = $form->getValue('cancel');
                        
                        if(isset($submit)){
                            $city->delete();
                            $this->_flashMessenger->setNamespace('info')->addMessage("Usunięto rekord!");
                            return $this->_helper->redirector(
                                    'list-cities', 'admin', null, array());
                        } else if($cancel){
                            $this->_flashMessenger->setNamespace('info')->addMessage("Anulowano!");
                            return $this->_helper->redirector(
                                    'list-cities', 'admin', null, array());
                        }
                    }
                }
                
            } else {
                throw new Zend_Controller_Action_Exception(sprintf('Rekord o id "%s" nie istnieje', $id), 404);
            }
        } catch(Exception $e){
            throw $e;
        }
        
        $this->view->form = $form;
    }

}

