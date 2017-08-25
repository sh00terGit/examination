<?php

/**
 *@todo  Работа с экзаменами
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class AdminExamController extends Zend_Controller_Action {

    private $userIdentity, $allExams, $service, $flagArchive;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_ExamService::getInstance();
        if ($this->userIdentity->role_id == 1) {
            $this->allExams = TRUE;
            $this->flagArchive = FALSE;
        } else {
            $this->allExams = FALSE;
            $this->flagArchive = TRUE;
        }
    }
    /**
     * редирект на экзамены
     */
    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_redirect('adminExam/load');
    }

    
    /**
     * загрузка экзаменов согласно выбранным темам или вывод ошибки (нет выбранных тем)
     */
    public function loadAction() {
        if (Services_UserThemeService::getInstance()->fetchByManagerInArray($this->userIdentity) != null) {
            $exams = $this->service->fetchExamsByPersonThemes($this->userIdentity);
        } else {
            $this->view->errorNoThemes = true;
        }

        $this->view->exams = $exams;
        $this->view->userIdentity = $this->userIdentity;
    }

    
    /**
     * загрузка тем экзаменов 
     */
    public function loadThemeAction() {
        $themes = $this->service->fetchAllThemes();
        $this->view->themes = $themes;
        $this->view->userIdentity = $this->userIdentity;
    }

    
   /**
    * добавление темы ( подразделение ответвенное)
    * 
    */
    public function addThemeAction() {
        $form = new Application_Form_Theme($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform-theme");
            }
        }
        $this->view->form = $form;
    }

    /**
     *  редактирование темы  ( подразделение отвественное)
     * @return type
     */
    public function editThemeAction() {
        $form = new Application_Form_Theme($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform-theme");
            }
        }
        $id = $this->_getParam('id');
        $theme = $this->service->findTheme($id);
        $form->populate(array(
            'fname' => $theme->getFname(),
            'sname' => $theme->getSname(),
            'comment' => $theme->getComment(),
            'hiddenid' => $id,
        ));
        $this->view->form = $form;
    }

    
    /**
     * функция добавления 
     */
    public function checkformThemeAction() {
        $this->_helper->viewRenderer->setNoRender();
        if ($this->_request->getPost()) {
            $this->service->saveTheme($this->_request->getPost());
        }
        $this->_redirect('/adminExam/load-theme');
    }

    
    /**
     * архив экзаменов  ( не исполькзуется)
     */
    public function loadArchiveAction() {
        $exams = $this->service->fetchAllArchive($this->userIdentity);
        $this->view->exams = $exams;
        $this->view->roleId = $this->userIdentity->role_id;
    }

    public function loadDivisionAction() {
        $exams = $this->service->fetchAllDivision($this->userIdentity);
        $this->view->exams = $exams;
    }

    /**
     * Добавление экзамена
     * @return type
     */
    public function addAction() {
        $form = new Application_Form_AdminExam($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        // главы экзаменов ( отделения отвественные)
        $dataChapters = Services_ChapterService::getInstance()->fetchAll($this->userIdentity);
        foreach ($dataChapters as $chapter) {
            $form->chapters->addMultioption($chapter->getId(), $chapter->getFname());
        }
        // темы выбранные
        $dataThemes = $this->service->fetchThemesByManager($this->userIdentity);
        foreach ($dataThemes as $theme) {
            $form->theme->addMultioption($theme->getId(), $theme->getFname());
        }
        // вывод ошибки если главы не отмеченны
        if ($dataChapters == null) {
            $this->view->errorNoChaptersToChange = true;
        }

        $this->view->form = $form;
    }

    
    /**
     * Редактирование экзамена ( отделение ответственное)
     * @return type
     */
   
    public function editAction() {
        $form = new Application_Form_AdminExam($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $exam = $this->service->find($id);

        //удаляем изменение типа экзамена (билет\ не билет)
        //
//        определить ошибку тип ( блокирует вьюху)
        $form->removeElement('type');
        $form->populate(array(
            'fname' => $exam->getFname(),
            'sname' => $exam->getSname(),
            'comment' => $exam->getComment(),
            'hiddenid' => $id,
        ));
        
        $dataChapters = Services_ChapterService::getInstance()->fetchAll($this->userIdentity);
        foreach ($dataChapters as $chapter) {
            $form->chapters->addMultioption($chapter->getId(), $chapter->getFname());
        }

        $data = $this->service->fetchThemesByManager($this->userIdentity);
        foreach ($data as $theme) {
            $form->theme->addMultioption($theme->getId(), $theme->getSname());
        }

        //ошибка если нет глав
        if ($dataChapters == null) {
            $this->view->errorNoChaptersToChange = true;
        }

        $form->theme->setValue($exam->getTheme()->getId());
        $form->chapters->setValue($exam->getChapters());
        $this->view->form = $form;
    }

    //  удаление экзамена или вывод ошибки foreign key
    public function deleteAction() {
        try {
            $this->_helper->viewRenderer->setNoRender();
            $this->service->delete($this->_getParam('id'), $this->_getParam('archive'));
            if ($this->_getParam('archive') == 0) {
                //удаление навсегда
                $this->_redirect('/adminExam/load-archive');
            } else {
                //удаление в архив
                $this->_redirect('/adminExam/load');
            }
        } catch (Exception $ex) {
            $this->_forward('exception-foreign', 'error', 'default', array('instance' => 'экзамен'));
        }
    }
    /**
     * удаление темы или вывод ошибки foreign-key
     */
    public function deleteThemeAction() {
        try {
            $this->service->deleteTheme($this->_getParam('id'));
            $this->_redirect('/adminExam/load-theme');
        } catch (Exception $exc) {
            $this->_forward('exception-foreign', 'error','default',array('instance' => 'тему'));
        }
    }
    
    /**
     * Восстановление экзамена 
     */
    public function restoreAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->restore($this->_getParam('id'));
        $this->_redirect('/adminExam/load');
    }

    /**
     * Заполенение экзамена ( вопросами с выбрааных глав)
     */
    public function fillingAction() {
        $examId = $this->_getParam('id');
        // билетная организация или нет
        $typeBilet = $this->_getParam('typeBilet');
        // вопросы
        $questions = Services_QuestionService::getInstance()->fetchByChapter($examId, $this->userIdentity);
        // билеты (один если не билетная организация без возможности добавить)
        $sections = Services_SectionService::getInstance()->fetchByExam($examId, $this->userIdentity);
        $form = new Application_Form_FillExam($typeBilet, $examId, $questions, $sections);
        $examFname = Services_ExamService::getInstance()->find($examId)->getFname();
        $this->view->examFname = $examFname;
        $this->view->form = $form;
    }

    
   /**
    * Добавление пользователей на экзамен (
    *  НЕ АКТУАЛЬНО 
    * ВЫПОЛНЯЕТСЯ В РАСПИСАНИИ
    */
    public function addUsersAction() {
        $examId = $this->_getParam('examId');
        $users = Services_UserService::getInstance()->fetchByManager($this->userIdentity);
        $userExamMapper = new Application_Model_UserExamMapper();
        $acceptedUsers = $userExamMapper->fetchUsers($examId);

        //удаляем с общего списка юзеров отмеченных 
        foreach ($acceptedUsers as $uexam) {
            if ($users != null) {
                foreach ($users as $key => $user) {
                    if ($uexam->getUser()->getId() === $user->getId()) {
                        unset($users[$key]);
                    }
                }
            }
        }
        $form = new Application_Form_AddUsersExam($users, $acceptedUsers, $examId);
        $this->view->form = $form;
    }

    /**
     * Api добавления ()
     *  НЕ АКТУАЛЬНО 
     * ВЫПОЛНЯЕТСЯ В РАСПИСАНИИ
     */
    public function checkAddUsersAction() {
        $this->_helper->viewRenderer->setNoRender();
        $examId = $this->_getParam('examId');
        $divisionId = $this->userIdentity->division_id;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');


        //get dest users

        $destinationUsers = preg_grep("/destination_/", array_keys($_POST));
        if (count($destinationUsers) != null) {
            $usersToDelete = array();
            $remainingUsers = array();
            foreach ($destinationUsers as $user) {
                if ($_POST[$user] == 0) {
                    $id = substr($user, 12);
                    $usersToDelete[] = $id;
                } else {
                    $id = substr($user, 12);
                    $remainingUsers[] = $id;
                }
            }
        }

        // get source users
        $sourceUsers = preg_grep("/source_/", array_keys($_POST));
        if (count($sourceUsers) != null) {
            $UsersToAdd = array();
            foreach ($sourceUsers as $user) {
                $id = substr($user, 7);
                if (count($remainingUsers) == null || array_search($id, $remainingUsers) === FALSE) {
                    $UsersToAdd[] = $id;
                }
            }
        }

        $userExamMapper = new Application_Model_UserExamMapper();
        $acceptedUsers = $userExamMapper->refreshUsers($UsersToAdd, $usersToDelete, $examId, $divisionId);

        $this->_redirect($refUrl);
    }

    /**
     * Api добавления вопросов
     */
    public function checkFillingAction() {
        $this->_helper->viewRenderer->setNoRender();
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        Services_SectionService::getInstance()->refreshBilet($this->_request->getPost());

        $this->_redirect($refUrl);
    }

   /**
    * Api сохранения формы
    */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        if ($this->_request->getPost()) {
            $this->service->save($this->_request->getPost());
        }
        $this->_redirect('/adminExam/load');
    }

    /**
     * Переопределение собственника экзамена на себя (билеты , вопросы и главы остаются на месте)
     * + переопределение расписания к этому экзамену на себя
      *  НЕ АКТУАЛЬНО 
    * ВЫПОЛНЯЕТСЯ В РАСПИСАНИИ
     * @param 
     * @return
     */
    public function overrideManagerExamAction() {
        $this->_helper->viewRenderer->setNoRender();

        $examId = $this->_getParam('id');
        //заменяем владельца
        $this->service->overrideManager($examId, $this->userIdentity->id);
        $this->_redirect('/adminExam/load-division');
    }

    /**
     * Копирует экзамен себе (без расписания и пользователей)
     *  НЕ АКТУАЛЬНО 
    * ВЫПОЛНЯЕТСЯ В РАСПИСАНИИ
     * @param 
     * @return view
     */
    public function cloneExamDataAction() {
        $this->_helper->viewRenderer->setNoRender();

        $this->_redirect('/adminExam/load-division');
    }

}
