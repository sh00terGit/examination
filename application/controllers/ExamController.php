<?php

/**
 *@todo Сдача экзамена и проверка 
 * 
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class ExamController extends Zend_Controller_Action {

    private $service;

    public function init() {
        $this->service = Services_ExamService::getInstance();
    }

    public function indexAction() {
        //инициализация 
        //достаем сессию 
        $registerUserExam = new Zend_Session_Namespace('examRegistration');
        $userId = $registerUserExam->userId;
        $examId = $registerUserExam->examId;
        $scheduleId = $registerUserExam->scheduleId;
        //  $scheduleId = $registerUserExam->scheduleId;
        if ($userId == null and $examId == null) {
            $this->_redirect('/pre-login');
        }

        
        ////////////////////////////////////////////
        /////////////////////////////////////////////////
        //находим юзера
        $user = Services_UserService::getInstance()->find($userId);

//        $user = Services_UserService::getInstance()->find($this->userIdentity->id);
        //находим экзамен юзера по id и закидываем в переменную exam
        $exam = $this->service->find($examId); 
        $schedule = Services_ScheduleService::getInstance()->find($scheduleId);
        
    if($this->checkCountQuestionExam($examId) == 0 ) {
        $this->_redirect('/error/no-questions');
        //выводим сообщение о ошибке если нет вопросов на экзамен
    }
        
        
        //находитм билеты в экзамене
        $examSectionMapper = new Application_Model_ExamSectionMapper();
        $sections = $examSectionMapper->fetchSection($exam);  
        
        // если нет еще билетов
        if (count($sections) == null) {
            $this->view->user = $user;
            $this->view->exam = $exam;
            //если хотя бы 1 билет есть    
        } elseif (count($sections) >= 1) {
            
            $questions = $this->getShuffleQuestionsBySection($sections);            
            $form = new Application_Form_UserExam($exam, $questions);            

            echo $form;
            $this->view->exam = $exam;
            $this->view->user = $user;
        }
        $this->view->schedule = $schedule;
    }

    public function checkAction() {
        
        
        $registerUserExam = new Zend_Session_Namespace('examRegistration');
        //защита 
        if (    !$this->_request->getPost() ||
                $registerUserExam->userId == null ||
                $registerUserExam->examId == null || 
                $registerUserExam->scheduleId == null) {
            $this->_redirect('/index');
        }
        //session
        $userId = $registerUserExam->userId;
        $examId = $registerUserExam->examId;
        $scheduleId = $registerUserExam->scheduleId;

        //очищаем сессию
        $registerUserExam->unsetAll();


        $schedule = Services_ScheduleService::getInstance()->find($scheduleId);
        if($schedule->getAuthType('индивидуальная')) {
            Zend_Auth::getInstance()->clearIdentity();
            Zend_Session::destroy();
        }
        $exam = Services_ExamService::getInstance()->find($examId);
        $user = Services_UserService::getInstance()->find($userId);

        
        // подготавливаем массив для поиска совпадений в основной таблице ResultSchedule
        $data = array(
            'committee' => (string)$schedule->getCommittee(),
            'date_start' => $schedule->getDateStart(),
            'date_end' => $schedule->getDateEnd(),
            'exam_fname' => $exam->getFname(),
            'exam_theme_fname' => $exam->getTheme()->getFname(),
            'manager_division' => $schedule->getManager()->getDivision()->getFname(),
            'manager_subdivision' => $schedule->getManager()->getSubDivision()->getFname(),
            'manager_first_name' => $schedule->getManager()->getFirst_name(),
            'manager_middle_name' => $schedule->getManager()->getMiddle_name(),
            'manager_last_name' => $schedule->getManager()->getLast_name(),
        );
        //находим есть ли запись , если нет создаем => возвращаем id
        $idResultSchedule = Services_ResultService::getInstance()->selectIdResultSchedule($data);
        if ($idResultSchedule == null) {
            $idResultSchedule = Services_ResultService::getInstance()->saveResultSchedule($data);
        }
        
        
        //вставляем данные о пользователе и результаты (оценка , затраченное время) экзамена
        $data = array(
            'criterion_value' => $schedule->getCriterion()->getValueMeasure(),
            'exam_time_pass' => '0',
            'mark' => '0',
//            'result_schedule_id' => $idResultSchedule,
            'user_division' => $user->getDivision()->getFname(),
            'user_subdivision' => $user->getSubDivision()->getFname(),
            'user_first_name' => $user->getFirst_name(),
            'user_middle_name' => $user->getMiddle_name(),
            'user_last_name' => $user->getLast_name(),
        );

        $idResultScheduleUser = Services_ResultService::saveResultScheduleUser($data, $idResultSchedule);
        //вставляем данные о вопросах
        // вставляем данные ответов (всех)
        // вставляем данные ответ пользователя
        // удаляем с поста лишние элементы 
        $remainderPost = $this->gutPost($this->_request->getPost());
        // mark - количество полностью верно отвеченных вопросов

        $mark = $this->evalQuestions($this->_request->getPost(), $remainderPost, $idResultScheduleUser);
        //неотвеченные вопросы вставляем в табличку с ответами пользователя
        $this->setQuestionsWithoutAnswers($remainderPost, $idResultScheduleUser);

        
        //вычисляем процент правильных ответов
        $percent = floor($mark * 100 / $remainderPost['countQuestions']);
        
        if ($percent < $schedule->getCriterion()->getValue())
            $finalMark = 0;  //не сдал
        else {
            $finalMark = 1; // сдал
            //удаляем успешно выполненный экзамен у пользователя
            Services_ResultService::getInstance()->updateResultUserPositive($idResultScheduleUser);
            $this->deleteSuccessfulExam($userId, $scheduleId);
        }
        
        $this->view->finalmark = $finalMark;
        //сообщаем результаты экзамена
        $this->view->mark = $mark;
        $this->view->countQuestions = $remainderPost['countQuestions'];
    }

    // тосуем билеты
    public function randomizeSection($sections) {
        $section = array_rand($sections, 1);
        return $sections[$section];
    }

    //выбираем билет с массива билетов который имеет вопросы ( не пустой) на данный экзамен
    public function getShuffleQuestionsBySection($sections) {        
        
        $sectionQuestionMapper = new Application_Model_SectionQuestionMapper();
        // отключаем рандом билета если билет хотя бы один раз был загружен за сессию       
        do {
            // достаем 1 билет рандомно
            $randSection = $this->randomizeSection($sections);
            //достаем вопросы
            $questions = $sectionQuestionMapper->fetchQuestions($randSection);            
            //пока вопросов нет - повторяем покуда вопросы не появяться берем другой билет
        } while (!$questions);
        //перемешаем вопросы
        shuffle($questions);
        return $questions;
    }

    public function deleteSuccessfulExam($userId, $scheduleId) {

        $table_user_schedule = new Application_Model_Db_UserSchedule();
        $where = array("user_id = ?" => $userId,
            "schedule_id = ?" => $scheduleId);

        $table_user_schedule->delete($where);
    }

    public function gutPost($my_post) {
        if (isset($my_post['submit']) &&
                isset($my_post['countQuestions']) &&
                isset($my_post['questions']) &&
                isset($my_post['examId'])) {

            $gutPost['countQuestions'] = filter_input(INPUT_POST, 'countQuestions', FILTER_VALIDATE_INT);
            //номера вопросов , ответы в evalQuestions()
            $questionArray = $my_post['questions'];    
            $gutPost['questions'] = array_flip($questionArray);
            $gutPost['examId'] = filter_input(INPUT_POST, 'examId', FILTER_VALIDATE_INT);
            $gutPost['criterion'] = filter_input(INPUT_POST, 'criterion', FILTER_VALIDATE_INT);
            unset($_POST['submit']);
            unset($_POST['countQuestions']);
            unset($_POST['examId']);
            unset($_POST['questions']);
        }
        return $gutPost;
    }

    /**
     * @todo анализ отвеченных вопросов пользователем
     * вставляем данные о вопросах
     * вставляем данные ответов (всех)
     * вставляем данные ответ пользователя
     *   
     * 
     * @param array $questionArray ( из gutPost() )
     * @param array $remainderPost обрезанный пост с номерами вопросов
     * @param int $idResultScheduleUser
     * @return int mark количество правильно отвеченных вопросов
     */
    public function evalQuestions($questionArray, &$remainderPost, $idResultScheduleUser) {
        $mark = 0; // кол-во верно отвеченных
        foreach ($questionArray as $idQuestion => $userAnswers) {
            //по одному удаляем отвеченные вопросы 
            unset($remainderPost['questions'][$idQuestion]);
            //достаем вопрос
            $negative = 0; // счетчик неправельных ответов пользователя
            $positive = 0; // счетчик правельных ответов пользователя
            //
            //сохраняем вопрос
//            echo $idQuestion.'||';
            $question = Services_QuestionService::getInstance()->find($idQuestion);
            $idResultExamQuestion = Services_ResultService::saveResultExamQuestion(array(
                        'question_text' => $question->getText(),
                        'result_schedule_user_id' => $idResultScheduleUser,
            ));
            //ответы на данный вопрос
            $answers = Services_AnswerService::getInstance()->fetchAll($question);

            $count = 0; // количество правильных ответов на вопрос (экзаменатора)
            foreach ($answers as $answer) {
                if ($answer->getPositive()) {
                    $count++;
                }
                $idAnswer = Services_ResultService::getInstance()->saveResultExamAnswer(array(
                    'answer_text' => $answer->getContent(),
                    'positive' => $answer->getPositive(),
                    'result_exam_question_id' => $idResultExamQuestion
                ));

                foreach ($userAnswers as $userAnswer => $userAnswerId) {

                    if ($userAnswerId === $answer->getId()) {
                        Services_ResultService::getInstance()
                                ->saveResultExamUserAnswer($idResultExamQuestion, $idAnswer);

                        if ($answer->getPositive()) {
                            $positive++;
                        } else {
                            $negative++;
                        }
                    }
                }
            }

            // если пользователь не допустил ошибок и количество правильных ответов юзера = в бд добавляем 1
            if ($negative == 0 && $positive == $count) {
                $mark++;
            }
        }
        return $mark;
    }

    public function setQuestionsWithoutAnswers($gutPost, $idResultScheduleUser) {
        foreach ($gutPost['questions'] as $idQuestion => $value) {
            $question = Services_QuestionService::getInstance()->find($idQuestion);
            $idResultExamQuestion = Services_ResultService::saveResultExamQuestion(array(
                        'question_text' => $question->getText(),
                        'result_schedule_user_id' => $idResultScheduleUser,
            ));
            //ответы на данный вопрос
            $answers = Services_AnswerService::getInstance()->fetchAll($question);

            foreach ($answers as $answer) {
                Services_ResultService::getInstance()->saveResultExamAnswer(array(
                    'answer_text' => $answer->getContent(),
                    'positive' => $answer->getPositive(),
                    'result_exam_question_id' => $idResultExamQuestion
                ));
            }
        }
    }
    
    
    public function checkCountQuestionExam($examId){
        return Services_QuestionService::getInstance()->fetchByExam($examId);
       
    }
    
    

}
