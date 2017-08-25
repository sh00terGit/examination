<?php

/*
 * Работа с расписанием 
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class ScheduleController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->service = Services_ScheduleService::getInstance();
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * загрузка всех перс. созданных  расписаний 
     */
    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();

        $this->_redirect('schedule/load');
    }
    /**
     * загрузка всех перс. созданных  расписаний 
     */
    public function loadAction() {
        if (Services_UserThemeService::getInstance()->fetchByManagerInArray($this->userIdentity) != null) {
            $schedules = $this->service->fetchSchedulesByPersonThemes($this->userIdentity);
        } else {
            $this->view->errorNoThemes = true;
        }
        $this->view->schedules = $schedules;
        $this->view->roleId = $this->userIdentity->role_id;
    }
    /**
     * загрузка всех отделенческих  созданных  расписаний 
     */
    public function loadDivisionAction() {
        if (Services_UserThemeService::getInstance()->fetchByManagerInArray($this->userIdentity) != null) {
            $schedules = $this->service->fetchDivisionScheduleByPersonThemes($this->userIdentity);
        } else {
            $this->view->errorNoThemes = true;
        }
        $this->view->schedules = $schedules;
        $this->view->roleId = $this->userIdentity->role_id;
    }
    
    /**
     * Добавление расписания или ошибка ( нет экзаменов)
     * @return type
     */
    public function addAction() {
        $form = new Application_Form_Schedule($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                return $this->_forward("checkform");
            }
        }
        // инфо экзаменов       
        $dataExam = Services_ExamService::getInstance()->fetchExamsByPersonThemes($this->userIdentity);
        foreach ($dataExam as $exam) {
            $form->exam_id->addMultioption($exam->getId(), $exam->getFname());
        }
        // инфо экзаменаторов отделения
        $dataUsers = Services_UserService::getInstance()->fetchManagersByDivision($this->userIdentity->division_id);
        foreach ($dataUsers as $manager) {
            $form->manager_id->addMultioption($manager->getId(), $manager->getFIO());
        }
        // инфо критериев отделения
        $dataCriterions = Services_CriterionService::getInstance()->fetchAll($this->userIdentity);
        foreach ($dataCriterions as $criterion) {
            $form->criterion->addMultioption($criterion->getId(), $criterion->getValueMeasure());
        }
        // инфо подразделений
        $dataSubDivisions = Services_SubDivisionService::getInstance()->fetchByManager($this->userIdentity);
        foreach ($dataSubDivisions as $subDivision) {
            $form->subDivision->addMultioption($subDivision->getId(), $subDivision->getSname());
        }

        if ($dataExam == null) {
            $this->view->errorNoExamsToChange = true;
        }
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        $this->view->form = $form;
    }

    
    /**
     * Добавление пользователей на экзамен
     * Api добавления
     */
    public function addUsersAction() {
        $scheduleId = $this->_getParam('scheduleId');
        $users = Services_UserService::getInstance()->fetchByManager($this->userIdentity);
        if ($users == null) {
            $this->view->errorNoUsers = true;
        }
        $userScheduleMapper = new Application_Model_UserScheduleMapper();
        $acceptedUsers = $userScheduleMapper->fetchUsers($scheduleId);

        //удаляем с общего списка юзеров отмеченных 
        if ($acceptedUsers != null) {
            foreach ($acceptedUsers as $uexam) {
                if ($users != null) {
                    foreach ($users as $key => $user) {
                        if ($uexam->getUser()->getId() === $user->getId()) {
                            unset($users[$key]);
                        }
                    }
                }
            }
        }
        $form = new Application_Form_AddUsersSchedule($users, $acceptedUsers, $scheduleId);
        $this->view->form = $form;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
    }

    /**
     * Api сохранения пользователей на экзамен
     * 
     */
    public function checkAddUsersAction() {
        $this->_helper->viewRenderer->setNoRender();
        $scheduleId = $this->_getParam('scheduleId');
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
        
        $scheduleAttempt = $this->service->find($scheduleId)->getAttempt();
        $userExamMapper = new Application_Model_UserScheduleMapper();       
        // обновление таблицы расписания- пользователи
        $acceptedUsers = $userExamMapper->refreshUsers($UsersToAdd, $usersToDelete, $scheduleId, $scheduleAttempt);

        $this->_redirect($refUrl);
    }

    
    /**
     * Редактирование расписания
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_Schedule($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $schedule = $this->service->find($id);
        $dataExams = Services_ExamService::getInstance()->fetchExamsByPersonThemes($this->userIdentity);
        foreach ($dataExams as $exam) {
            $form->exam_id->addMultioption($exam->getId(), $exam->getFname());
        }

        $dataUsers = Services_UserService::getInstance()->fetchManagersByDivision($this->userIdentity->division_id);
        foreach ($dataUsers as $manager) {
            $form->manager_id->addMultioption($manager->getId(), $manager->getFIO());
        }


        
        
        $dataCriterions = Services_CriterionService::getInstance()->fetchAll($this->userIdentity);
        foreach ($dataCriterions as $criterion) {
            $form->criterion->addMultioption($criterion->getId(), $criterion->getValueMeasure());
        }
        
        $form->populate(array(
            'dateStart' => $schedule->getDateStart(),
            'dateEnd' => $schedule->getDateEnd(),
            'manager_id' => $schedule->getManager()->getId(),
            'comment' => $schedule->getComment(),
            'attempt' => $schedule->getAttempt(),
            'committee' => $schedule->getCommittee(),
            'time_pass' => $schedule->getTimePass(),
            'hiddenid' => $id,
        ));
        
        // не даем редактировать подразделение и отделение для добавления пользователей
        $form->removeElement('subDivision');
        $form->removeElement('division');        
        
        $form->authType->setValue($schedule->getAuthTypeId());
        $form->criterion->setValue($schedule->getCriterion()->getId());
        $form->exam_id->setValue($schedule->getExam()->getId());
        $form->exam_id->setValue($schedule->getExam()->getId());
        $form->manager_id->setValue($schedule->getManager()->getId());
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        $this->view->form = $form;
    }

    
    /**
     * Api удаления расп
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'));
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->_redirect($refUrl);
    }

    /**
     *Api сохранения 
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->save($this->_request->getPost());
        $this->_redirect('/schedule/load');
    }

}
