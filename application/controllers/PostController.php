<?php
/**
 * Работа с должностями  ( работа только админом)
 */
class PostController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_PostService::getInstance();
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
        $posts = $this->service->fetchAll($this->userIdentity);
        $this->view->posts = $posts;
        $this->view->roleId = $this->userIdentity->role_id;
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_Post($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        if ($this->userIdentity->role_id == '1') {
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
        }
        $this->view->form = $form;
    }
    /**
     * Редактирование должностей
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Post($this->userIdentity);

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }

        $id = $this->_getParam('id');

        $post = $this->service->find($id);

        $form->populate(array(
            'fname' => $post->getFname(),
            'sname' => $post->getSname(),
            'hiddenid' => $id
        ));
        if ($this->userIdentity->role_id == '1') {
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
            $form->division_id->setValue($post->getDivision()->getId());
        }

        $this->view->form = $form;
    }

    
    /**
     * Api Удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'));
        $this->_redirect('/post/load');
    }
    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect('/post/load');
    }

}
