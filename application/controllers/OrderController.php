<?php

class OrderController extends Zend_Controller_Action
{

    protected $_gridOptions = array();
    
    protected $_exportOptions = array('pdf');
    
    protected $_nullColumns = array('date_of_receipt', 'date_of_payment');
    
    public function init()
    {
        /* Initialize action controller here */
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        
//        Zend_Debug::dump($this->getRequest()->getParams());
        
        $export = $this->getRequest()->getParam('export', null);
        if(in_array($export, $this->_exportOptions)){
            $this->_exportToPdf();
        }
        
        $this->_gridOptions['url'] = $this->view->url(array(
                        'controller' => 'order',
                        'action' => 'list'
                    ), 'order_list');
        $this->_gridOptions['export'] = array('pdf' => array('label' => 'PDF'));
        $this->_gridOptions['filter'] = array('order_year' => array('label' => 'Zamówienia za rok', 'values' => array('2012' => '2012', '2013' => '2013', '2014' => '2014', '2015' => '2015')));
        $this->_gridOptions['massactions'] = array('export');
        $this->_gridOptions['massactionfield'] = 'id';
        
        $paginator = $this->_getOrdersPaginator($this->_gridOptions);
        $this->_gridOptions['paginator'] = $paginator;
        
        $this->view->gridOptions = $this->_gridOptions;
//        $this->view->export = array('pdf' => array('label' => 'PDF'));
//        $this->view->filter = array('order_year' => array('label' => 'Zamówienia za rok', 'values' => array('2012' => '2012', '2013' => '2013', '2014' => '2014', '2015' => '2015')));
    }
    
    protected function _filterDataForCRUD($data)
    {
        $filtered = array();
        foreach($data as $key => $value){
            if(in_array($key, $this->_nullColumns) && empty($value)){
                $filtered[$key] = null;
            } else {
                $filtered[$key] = $value;
            }
        }
        
        return $filtered;
    }
    
    public function createAction()
    {
        $this->view->title = "Tworzenie nowego zamówienia";
        
        $form = new Application_Form_Order;
        
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
        
        $paginator = $this->_getOrdersPaginator();
        $this->view->paginator = $paginator;
    }

    public function updateAction()
    {
        $paginator = $this->_getOrdersPaginator();
        $this->view->paginator = $paginator;
        
        $id = $this->getRequest()->getParam('order_id');
        $this->view->title = "Edycja zamówienia <b>(id: {$id})</b>";
        
        $model = new Application_Model_DbTable_Order();
        $form = new Application_Form_Order;
        
        try{
            /* @var $orders Zend_Db_Table_Rowset */
            $orders = $model->find($id);
            if($orders->count()){
                $order = $orders->current();
                if($this->getRequest()->isPost()){
                    if($form->isValid($this->getRequest()->getPost())){
                        $data = $form->getValues();
                        $data['update_time'] = new Zend_Db_Expr('NOW()');
                        
                        $data = $this->_filterDataForCRUD($data);
                        
                        $order->setFromArray($data);
                        $order->save();
                        $this->view->actionMessage = "<span class=\"success\">Zaktualizowano rekord!</span>";
                    }
                }
                
                $order = $order->toArray();
                $this->view->form = $form->populate($order);
                $this->view->order = $order;
            } else {
                throw new Zend_Controller_Action_Exception(sprintf('Rekord o id "%s" nie istnieje', $id), 404);
            }
        } catch(Exception $e){
            throw $e;
        }

        $this->view->order_id = $id;
    }

    public function listAction()
    {
        $this->view->title = "Wylistowanie zamówień";
        $paginator = $this->_getOrdersPaginator();
        $this->view->paginator = $paginator;
    }

    public function deleteAction()
    {
        $paginator = $this->_getOrdersPaginator();
        $this->view->paginator = $paginator;
        
        $id = $this->getRequest()->getParam('order_id');
        $this->view->title = "Usuwanie zamówienia <b>(id: {$id})</b>";
        
        $model = new Application_Model_DbTable_Order();
        $form = new Application_Form_DialogDelete;
        
        try{
            /* @var $orders Zend_Db_Table_Rowset */
            $orders = $model->find($id);
            if($orders->count()){
                $order = $orders->current();
                $this->view->actionMessage = sprintf("Chcesz usunąć zamówienie o nr %s?", $order->unique);
                
                if($this->getRequest()->isPost()){
                    if($form->isValid($this->getRequest()->getPost())){
                        $submit = $form->getValue('submit');
                        $cancel = $form->getValue('cancel');
                        
                        if(isset($submit)){
                            $this->view->actionMessage = sprintf("<span class=\"success\">Usunięto zamówienie o nr %s!</span>", $order->unique);
                            $order->delete();
                            $form = "";
                        } else if($cancel){
                            return $this->_helper->redirector(
                                'list', 'order', null, array());
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

    /**
     * Pomocnicza metoda zwracająca posortowane rekordy
     * @return Zend_Db_Table_Rowset 
     *
     */
    protected function _getOrdersPaginator($options = array())
    {
        $page = $this->getRequest()->getParam('page', 1);
        $sort = $this->getRequest()->getParam('sort', 'date_of_payment');
        $dir = $this->getRequest()->getParam('dir', 'asc');
        $orders_year = $this->getRequest()->getParam('order_year', date('yyyy'));
        $client_filter = $this->getRequest()->getParam('client_filter', null);
        
        $order_model = new Application_Model_DbTable_Order();
        
        if(!is_null($sort) && !is_null($dir))
        {
            $options['sort'] = $sort;
            $options['dir'] = $dir;
            
            $this->_gridOptions = array_merge($this->_gridOptions, $options);
            
            if(in_array($sort, array('date_of_payment', 'date_of_receipt'))){
                $sort = new Zend_Db_Expr("ISNULL({$sort}), {$sort}");
            }
        }
        
        $order_part = "$sort $dir";
        
        $select = $order_model->select()->order($order_part);
        
        if(preg_match('/\b(\d){4}\b/', $orders_year)){
            $year_expression = new Zend_Db_Expr("DATE_FORMAT(insert_time, '%Y')");
            $select->where("$year_expression = ?", $orders_year);
        }
        
        if(!is_null($client_filter) && preg_match('/\b(\w)+\b/', $client_filter)){
            $select->where("client like ?", "%{$client_filter}%");
        }
        
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        return $paginator;
    }

    protected function _exportToPdf()
    {
        if($this->getRequest()->isPost()){
            
            $selected = $this->getRequest()->getPost('selected', null);
            if(!is_null($selected)){
                $orderModel = new Application_Model_DbTable_Order();
                $values = array_values($selected);
//                $selected = array_reverse($selected);
                $select = $orderModel->select()->where('id IN (?)', $values);
                $orders = $orderModel->fetchAll($select);
                $this->view->assign(array('orders' => $orders));
                
                $this->_helper->layout->setLayout('pdf');
                $this->_helper->layout->disableLayout();
                
                $this->_helper->viewRenderer->setRender('pdf');
                $this->_helper->viewRenderer->setNoRender();
                
                $layout = $this->_helper->layout->getLayoutInstance();
                $layout->assign('content', $this->view->render('order/pdf.phtml'));
                $output = $layout->render();
                
                $stylesheet = file_get_contents(APPLICATION_PATH . "/../public/css/style.css");
                
                require_once(APPLICATION_PATH . "/../library/MPDF54/mpdf.php");
                $date = date('YmdHis');
                $name = "eksport_{$date}.pdf";
                $mpdf = new mPDF('utf-8', 'A4-L');
				$mpdf->simpleTables = true;
				$this->packTableData = true;
				$this->cacheTables = true;
                $mpdf->AddPage('L');
//                $mpdf = new mPDF(); 
                $mpdf->WriteHTML($output);
                $mpdf->Output($name, 'I');
//                $this->getRequest()->clearParams();
                
                exit;
            }
        }
    }


}











