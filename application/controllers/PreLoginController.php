<?php
/**
 * стартовый контроллер ( работа гостем а также зарег польз)ы
 */
class PreLoginController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('main');
        parent::init();
    }
    
    
    /**
     * стартовая страница , выводятся все расписания
     */
    public function indexAction() {
        $exams = Services_ExamService::getInstance()->fetchAllWithSchedule();
        $this->view->exams = $exams;
    }

    
    /**
     * страница предзагрузки экзамена , вход по общему или индивидуальному паролю 
     * в зависимости от выбранного в расписании , вывод информации о экзамене , 
     * количестве попыток
     */
    public function preloadexamAction() {
        $scheduleId = $this->_request->getParam('scheduleId');
        $schedule = Services_ScheduleService::getInstance()->find($scheduleId);
        $exam = Services_ExamService::getInstance()->findExamSchedule($scheduleId);
        $userScheduleMapper = new Application_Model_UserScheduleMapper();
        $scheduleUsers = $userScheduleMapper->fetchUsers($scheduleId);
        switch ($schedule->getAuthType()) {
            case 'общая':
                $form = new Application_Form_UserRegistrationCommon();

                if ($scheduleUsers != null) {
                    foreach ($scheduleUsers as $scheduleUser) {
                        $form->user_id
                                ->addMultioption(
                                        $scheduleUser->getUser()->getId(), $scheduleUser->getUser()->getFIO() . '   ' .
                                        $scheduleUser->getUser()->getSubDivision()->getSname());
                    }
                } else {
                    $this->view->errorNoUsers = true;
                }
                if ($this->_request->isPost()) {
                    if ($form->isValid($this->_request->getPost())) {

                        if (sha1($_POST['passw']) == $exam->getPassword()) {
                            //уменьшаем кол-во попыток 
                            $this->reduceAttempt($userScheduleMapper, $_POST['user_id'], $scheduleUser->getSchedule()->getId());
                            $registerUserExam = new Zend_Session_Namespace('examRegistration');
                            $registerUserExam->examId = $exam->getId();
                            $registerUserExam->userId = $_POST['user_id'];
                            $registerUserExam->scheduleId = $scheduleUser->getSchedule()->getId();
                            $this->_redirect('/exam/');
                        } else {
                            echo "<script>alert('wrong password')</script>";
                        }
                    }
                }

                break;

            case 'индивидуальная':
                $form = new Application_Form_UserRegistrationIndividual();
                if ($scheduleUsers != null) {
                    foreach ($scheduleUsers as $scheduleUser) {
                        $form->user_id
                                ->addMultioption(
                                        $scheduleUser->getUser()->getId(), $scheduleUser->getUser()->getFIO() . '   ' .
                                        $scheduleUser->getUser()->getSubDivision()->getSname());
                    }
                } else {
                    $this->view->errorNoUsers = true;
                }

                if ($this->_request->isPost()) {
                    if ($form->isValid($this->_request->getPost())) {
                        $this->reduceAttempt($userScheduleMapper, $_POST['user_id'], $scheduleUser->getSchedule()->getId());
                        $authAdapter = new Zend_Auth_Adapter_DbTable($this->_config->db);


                        $authAdapter->setTableName('users')
                                ->setIdentityColumn('id')
                                ->setCredentialColumn('passw')
                                ->setCredentialTreatment('Sha1(?)');

                        $login = $this->getRequest()->getPost('user_id');
                        $passw = $this->getRequest()->getPost('passw');
// подставляем полученные данные из формы
                        $authAdapter->setIdentity($login)
                                ->setCredential($passw);

// получаем экземпляр Zend_Auth
                        $auth = Zend_Auth::getInstance();

// делаем попытку авторизировать пользователя
                        $result = $auth->authenticate($authAdapter);
                        if ($result->isValid()) {
// используем адаптер для извлечения оставшихся данных о пользователе
                            $identity = $authAdapter->getResultRowObject();

// получаем доступ к хранилищу данных Zend
                            $authStorage = $auth->getStorage();

// помещаем туда информацию о пользователе,
// чтобы иметь к ним доступ при конфигурировании Acl
                            $authStorage->write($identity);
//
                            //$user = Zend_Auth::getInstance()->getIdentity();
                            Zend_Auth::getInstance()->clearIdentity();
                            $registerUserExam = new Zend_Session_Namespace('examRegistration');
                            $registerUserExam->examId = $exam->getId();
                            $registerUserExam->userId = $identity->id;
                            $registerUserExam->scheduleId = $scheduleId;
                            $this->_redirect('/exam/');
                        }
                    } else {
                        echo "<script>alert('wrong password')</script>";
                    }
                    break;
                }
        }


// Допущенные пользователи

        $this->view->usersExam = $scheduleUsers;
        $this->view->exam = $exam;
        $this->view->schedule = $schedule;
/////////////////////////////////////

        $this->view->form = $form;
    }

    /**
     * Выборка количества попыток по user_id
     * @return json $data 
     * @throws false
     */
    public function selectAction() {
        if ($user_id = $this->_request->getParam('parent_id') and
                $schedule_id = $this->_request->getParam('schedule_id')) {
            $mapper = new Application_Model_UserScheduleMapper();
            $data = $mapper->findAttemptsByUser($user_id, $schedule_id);
            $this->getHelper('json')->sendJson($data);
        } else {
            return false;
        }
    }

    
    /**
     * Api уменьшения количества оставшихся попыток
     * @param type $userScheduleMapper   - мапер выбора пользователей с расписания
     * @param type $userId   --  пользователь
     * @param type $schedule_id  - расписание 
     */
    private function reduceAttempt($userScheduleMapper,$userId ,$schedule_id ) {
        $attempt = $userScheduleMapper->findAttemptsByUser($userId, $schedule_id);
        
       // -1 - без ограничений сдача 
       // 0 - закончились попытки
        if ($attempt != '-1' and $attempt != 0) {
            $userScheduleMapper->reduceAttempt($userId, $schedule_id);
        }
    }

}
