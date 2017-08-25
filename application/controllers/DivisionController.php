<?php

class DivisionController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_DivisionService::getInstance();
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
        $divisions = $this->service->fetchAll();
        $this->view->divisions = $divisions;
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_Division();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $this->view->form = $form;
    }

    /**
     * Редактирование отделения
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Division();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $division = $this->service->find($id);
        $form->populate(array(
            'fname' => $division->getFname(),
            'sname' => $division->getSname(),
            'comment' => $division->getComment(),
            'hiddenid' => $id
        ));

        $this->view->form = $form;
    }

    /**
     * Api удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'));
        $this->_redirect('/division/load');
    }

    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect('/division/load');
    }

}
