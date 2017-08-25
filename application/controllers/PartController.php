<?php

/**
 * главы 
 */
class PartController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_PartService::getInstance();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_Part($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $data = Services_ChapterService::getInstance()->fetchAll($this->userIdentity);
        foreach ($data as $chapter) {
            $form->chapter_id->addMultioption($chapter->getId(), $chapter->getFname());
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
        $parts = $this->service->fetchAll($this->userIdentity);
        $this->view->parts = $parts;
        $this->view->roleId = $this->userIdentity->role_id;
    }

    /**
     * Редактирование пунктов
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Part($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $part = $this->service->find($id);

        $form->populate(array(
            'fname' => $part->getFname(),
            'sname' => $part->getSname(),
            'comment' => $part->getComment(),
            'archive' => $part->getArchive(),
            'hiddenid' => $id
        ));
        $data = Services_ChapterService::getInstance()->fetchAll($this->userIdentity);
        foreach ($data as $chapter) {
            $form->chapter_id->addMultioption($chapter->getId(), $chapter->getFname());
        }
        $form->chapter_id->setValue($part->getChapter()->getId());
        $this->view->form = $form;
    }

    /**
     * Api удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'), $this->flagArchive);
        $this->_redirect('/part/load');
    }

    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->save($this->_request->getPost());
        $this->_redirect('/part/load');
    }

}
