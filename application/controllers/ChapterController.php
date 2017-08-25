<?php
/**
 * Api глав
 */
class ChapterController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_ChapterService::getInstance();
        if ($this->userIdentity->role_id == 1) {
            $this->flagArchive = FALSE;
        } else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_Chapter($this->userIdentity);
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
     * Просмотр ( с возможностью удаления, редактирования)
     * 
     * @param array $selectFields - default-все! - поля для выборки для просмотра
     * @param boolean  $flagArchive   default=FALSE - поля для выборки для просмотра     
     * @return view
     */
    public function loadAction() {
        $chapters = $this->service->fetchAll($this->userIdentity);
        $this->view->roleId = $this->userIdentity->role_id;
        $this->view->chapters = $chapters;
    }
    
    /**
     * Редактирование глав
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Chapter($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $chapter = $this->service->find($id);

        $form->populate(array(
            'fname' => $chapter->getFname(),
            'sname' => $chapter->getSname(),
            'comment' => $chapter->getComment(),
            'archive' => $chapter->getArchive(),
            'hiddenid' => $id
        ));
        if ($this->userIdentity->role_id == '1') {
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
            $form->division_id->setValue($chapter->getDivisionId()->getId());
        }
        $this->view->form = $form;
    }

    /**
     * Api удаления
     */
    public function deleteAction() {
        try {
            $this->service->delete($this->_getParam('id'), $this->flagArchive);
            $this->_redirect('/chapter/load');
        } catch (Exception $exc) {            
            $this->_forward('exception-foreign', 'error','default',array('instance' => 'главу'));
        }
    }
    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect('/chapter/load');
    }

}
