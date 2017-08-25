<?php
/**
 *  Работа с вопросами (разрешена всем кроме гостей)
 */
class QuestionController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_QuestionService::getInstance();
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
        $form = new Application_Form_Question($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $form = $this->formChapters($form);
        $form->part_id->setAttrib('disabled', true);

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
        $questions = $this->service->fetchAll($this->userIdentity);
        $this->view->roleId = $this->userIdentity->role_id;
        $this->view->questions = $questions;
    }

    /**
     * Редактирование с возм. добавления ответа
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Question($this->flagArchive);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $question = $this->service->find($id);

        $form->populate(array(
            'text' => $question->getText(),
            'archive' => $question->getArchive(),
            'hiddenid' => $id
        ));

        $form = $this->formChapters($form);
        $form = $this->formParts($form, $question->getChapter()->getId());
        $form->chapter_id->setValue($question->getChapter()->getId());
        $form->part_id->setValue($question->getPart()->getId());
        $this->view->form = $form;
//        // ответы 
        $answers = $this->answersOfQuestion($question);
        $this->view->answers = $answers;
        $this->view->questionId = $id;
    }

    
    /**
     * Api Удаления вопроса или ошибка foreign key
     */
    public function deleteAction() {
        try {
            $this->service->delete($this->_getParam('id'), $this->flagArchive);
            $this->_redirect('/question/load');
        } catch (Exception $exc) {
            $this->_forward('exception-foreign', 'error', 'default', array('instance' => 'вопрос'));
        }
    }

    /**
     * сохранение вопроса
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->save($this->_request->getPost());
        $this->_redirect('/question/load');
    }

    /**
     * /Добавление глав в форму 
     * @param type $form
     * @return type
     */
    public function formChapters($form) {

        $data = Services_ChapterService::getInstance()->fetchAll($this->userIdentity);
        foreach ($data as $chapter) {
            $form->chapter_id->addMultioption($chapter->getId(), $chapter->getFname());
        }
        return $form;
    }
/**
     * /Добавление пунктов в форму 
     * @param type $form
     * @return type
     */
    public function formParts($form, $chapter_id) {
        $data = $data = Services_PartService::getInstance()->fetchPartsByChapter($chapter_id);
        foreach ($data as $part) {
            if ($part->getComment() == 'auto' && $part->getFname() == '') {
                $part->setFname('без ' .
                        Services_DictionaryService::getDictionaryPartFromSession()->getFname_roditelni());
            }
            $form->part_id->addMultioption($part->getId(), $part->getFname());
        }
        return $form;
    }

    
    /**
     * Выбор всех ответов на вопрос
     * @param type $question - вопрос
     * @return array $answers  -ответы
     */
    public function answersOfQuestion($question) {
        $answerMapper = new Application_Model_AnswerMapper();
        $answers = $answerMapper->fetchAnswers($question);

        return $answers;
    }

    /**
     * Api Ajax выбора пунктов по главам или ошибка
     * @return boolean
     */
    public function selectAction() {
        if ($chapter_id = $this->_request->getParam('parent_id')) {
            $data = Services_PartService::getInstance()->fetchPartsByChapter($chapter_id);
            foreach ($data as $part) {
                // находим безымянныый auto и именуем его 
                if ($part->getComment() == 'auto' && $part->getFname() == '') {
                    $part->setFname('без ' .
                            Services_DictionaryService::getDictionaryPartFromSession()->getFname_roditelni());
                }
            }
            $this->getHelper('json')->sendJson($data);
        } else {
            return false;
        }
    }

}
