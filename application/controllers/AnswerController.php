<?php
/**
 * Работа с ответами
 */
class AnswerController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive , $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_AnswerService::getInstance();
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
        $form = new Application_Form_Answer($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        
        $form->question_id->setValue($id);        
        $question = Services_QuestionService::getInstance()->find($id);
        $this->view->form = $form;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        $this->view->question = $question;
    }

    /**
     * Редактирование ответа
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Answer($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');        
        $answer = Services_AnswerService::getInstance()->find($id);
        $form->populate(array(
            'content' => $answer->getContent(),
            'positive' => $answer->getPositive(),
            'question_id' => $answer->getQuestion()->getId(),
            'image' => $answer->getImage(),
            'archive' => $answer->getArchive(),
            'hiddenid' => $id
        ));

        $this->view->form = $form;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        $this->view->question = $answer->getQuestion();
    }

    /**
     * Api удаления
     */  
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'));        
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        
        $this->_redirect($refUrl);
    }

    /**
     * Api сохранения вопроса
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->save($this->_request->getPost());

        $this->_redirect('/question/edit/'.$_POST['question_id']);
    }

}
