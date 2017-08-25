<?php
/**
 * Билеты  ( работа с добавления вопросов на экзамен)
 */
class SectionController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive ,$service ;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service= Services_SectionService::getInstance();
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
        $form = new Application_Form_Section($refUrl,$examId);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $examId = $this->_getParam('examId');
        
        $this->view->form = $form;
        $this->view->refUrl = $refUrl;
    }

    /**\
     * редактирование 
     */
    public function editAction() {
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        $id = $this->_getParam('id');
        $examId = $this->_getParam('examId');
        $section = $this->service->find($id);
        $form = new Application_Form_Section($refUrl , $examId);
        
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        
        $form->populate(array(
            'fname' => $section->getFname(),
            'sname' => $section->getSname(),
            'comment' => $section->getComment(),
            'hiddenid' => $id
        ));
        $this->view->form = $form;
    }

    /**
     * Api удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->service->delete($this->_getParam('id'));
        $this->_redirect($refUrl);
    }
    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect($this->_getParam('refUrl'));
    }

}
