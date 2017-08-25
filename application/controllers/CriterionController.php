<?php

class CriterionController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive , $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_CriterionService::getInstance();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * Просмотр ( с возможностью удаления, редактирования)
     * 
     * @param array $selectFields - default-все! - поля для выборки для просмотра
     * @param boolean  $flagArchive   default=FALSE - поля для выборки для просмотра     
     * @return view
     */
    public function loadAction() {      
        $criterions = $this->service->fetchAll($this->userIdentity);
        $this->view->criterions = $criterions;
        $this->view->roleId = $this->userIdentity->role_id;
    }
    
    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_Criterion($this->userIdentity->division_id);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $this->view->form = $form;        
    }
     
    /**
     * Редактирование критерия
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Criterion($this->userIdentity->division_id);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');        
        $criterion = $this->service->find($id);
        
        $form->populate(array(
            'value' => $criterion->getValue(),
            'measure' => $criterion->getMeasure(),
            'hiddenid' => $id
        ));
        $this->view->form = $form;
    }
    /**
     * Api удаления или вывод foreign key exception
     */
    public function deleteAction() {
        
        try {            
            $this->service->delete($this->_getParam('id'));
            $this->_redirect('/criterion/load');
        } catch (Exception $exc) {
           $this->_forward('exception-foreign', 'error','default',array('instance' => 'критерий'));
        }        
        
       
    }

    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect('/criterion/load');
    }

}
