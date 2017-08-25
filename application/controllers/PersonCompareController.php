<?php

/**
 * Персональные настройки пользователя 
 */
class PersonCompareController extends Zend_Controller_Action {

    private $userIdentity;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
    }

    /**
     * Выбор названия справочников (глава - пункт)
     */
    public function dictionaryAction() {


        $dictionary = Services_DictionaryService::fetchAll();
        $form = new Application_Form_Dictionary();
        $this->view->dictionary = $dictionary;
        foreach ($dictionary['chapterName'] as $row) {
            $form->level1->addMultioption($row->getId(), $row->getFname());
        }
        foreach ($dictionary['partName'] as $row) {
            $form->level2->addMultioption($row->getId(), $row->getFname());
        }
        //set level1 , level2 - default from session dictionary
        $form->level1->setValue(Services_DictionaryService::getDictionaryChapterFromSession()->getId());
        $form->level2->setValue(Services_DictionaryService::getDictionaryPartFromSession()->getId());

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                Services_DictionaryService::save($this->_request->getPost(), $this->userIdentity);
                $this->_redirect('/PersonCompare/check-exit');
            }
        }


        $this->view->form = $form;
    }

    /**
     * Подразделения выбор 
     */
    public function indexAction() {
        $subDivisions = Services_SubDivisionService::getInstance()->fetchAllAccepted($this->userIdentity);
        $form = new Application_Form_UserDivision($subDivisions, $this->userIdentity);
        $this->view->form = $form;
    }

    /**
     * Api сохранения выбранных подразделений
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        if ($this->_request->getPost()) {
            $manager_id = $_POST['manager_id'];
            Services_UserSubDivisionService::getInstance()->delete($this->userIdentity);

            unset($_POST['manager_id']);
            unset($_POST['submit']);

            foreach ($this->_request->getPost() as $subDivision => $accepted) {
                if ($accepted == '1') {
                    $id = substr($subDivision, 11);
                    echo $id;
                    Services_UserSubDivisionService::getInstance()->save(array('manager_id' => $manager_id, 'subdivision_id' => $id));
                }
            }
        }
        $this->_redirect('/PersonCompare/index');
    }

    /**
     * Выбор тем
     */
    public function themeAction() {
//        $this->_helper->viewRenderer->setNoRender();
        $themes = Services_ExamService::getInstance()->fetchAllThemes();
        if ($themes == null) {
            $this->view->errorNoAviableThemes = true;
        }
        $mapper = new Application_Model_UserThemeMapper();
        $checkedThemes = $mapper->checkAcceptedThemesByManager($this->userIdentity->id, $themes);
        $form = new Application_Form_UserTheme($checkedThemes, $this->userIdentity);
        $this->view->form = $form;
    }

    /**
     * Api сохранения тем 
     */
    public function checkformThemeAction() {
        $this->_helper->viewRenderer->setNoRender();
        if ($this->_request->getPost()) {
            $manager_id = $_POST['manager_id'];
            Services_UserThemeService::getInstance()->delete($this->userIdentity);

            unset($_POST['manager_id']);
            unset($_POST['submit']);

            foreach ($this->_request->getPost() as $theme => $accepted) {
                if ($accepted == '1') {
                    $id = substr($theme, 5);
                    echo $id;
                    Services_UserThemeService::getInstance()->save(array('manager_id' => $manager_id, 'exam_theme_id' => $id));
                }
            }
        }
        $this->_redirect('/PersonCompare/theme');
    }

}
