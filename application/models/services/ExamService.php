<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_ExamService {

    protected static $_instance;
    public static $mapper;

    private function __construct() {
        
    }
    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

    public static function getInstance() { // получить экземпляр данного класса
        if (self::$_instance === null) { // если экземпляр данного класса  не создан
            self::$_instance = new self;
            // создаем экземпляр данного класса
        }
        self::$mapper = new Application_Model_ExamMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    

    public static function fetchExamsByUser(Application_Model_User $user) {
        $userExamMapper = new Application_Model_UserExamMapper();
        return $userExamMapper->fetchExams($user);
    }
    
    public function fetchExamsByPersonThemes($userIdenity) {
        $themesId = Services_UserThemeService::getInstance()->fetchByManagerInArray($userIdenity);
        if ($themesId != null) {
            $themesString = implode(',',$themesId) ;
        }  
        return self::$mapper->fetchExamsByThemes($themesString , $archive = 0);
        
        
        
    }

    
    public function fetchAllThemes() {
        $examThemeMapper = new Application_Model_ExamThemeMapper();
        return $examThemeMapper->fetchAll();
    }
    
    public function fetchThemesByManager($userIdentity){
        $themesId = Services_UserThemeService::getInstance()->fetchByManagerInArray($userIdentity);
        $mapper = new Application_Model_ExamThemeMapper();
        $themes = $mapper->fetchThemesById(implode(',',$themesId));        
        return $themes;
    }

    

    public function restore($id) {
        self::$mapper->updateArchive($id);
    }

    public function fetchAllDivision($userIdenity) {
        $roleId = $userIdenity->role_id;
        $divisionId = $userIdenity->division_id;
        $userId = $userIdenity->id;
        $archive = 0;
        return self::$mapper->fetchExamsByDivision($userId, $divisionId, $roleId, $archive);
    }

    public function fetchAllPreLoginByDivision($division_id) {
        return self::$mapper->fetchAllPreLoginByDivision($division_id);
    }

    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        $archive = 0;
        return self::$mapper->fetchExamsByManager($divisionId, $roleId, $archive);
    }

    public function fetchAllWithSchedule() {
        return self::$mapper->fetchAllWithSchedule();
    }

    function fetchAllArchive($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        $archive = 1;
        return self::$mapper->fetchExamsByManager($divisionId, $roleId, $archive);
    }

    public function find($id) {
        return self::$mapper->findById($id);
    }
    
    public function findTheme($id) {
        $examThemeMapper = new Application_Model_ExamThemeMapper();
        return $examThemeMapper->findTheme($id);
    }
    
    public function findExamSchedule($scheduleId){
        return self::$mapper->findExamSchedule($scheduleId);
    }

    public function delete($id, $archive = false, $where = null) {
        if ($where === null) {
            $where = array("exam_id = ?" => $id);
            if ($archive == FALSE) {

                $exam = self::getInstance()->find($id);

                if (strtotime($exam->getDate()) <= strtotime(date('Y-m-d', strtotime('-25 month')))) {
                    //удалить билеты 
                    Services_SectionService::getInstance()->delete($id, false, $where);
                    //удалить ответы юзера
//                    $userAnswerMapper = new Application_Model_UserExamAnswerMapper();
//                    $userAnswerMapper->deleteByExamId($id);

                    //удалить сданные экзамены юзера
//                    $examResMapper = new Application_Model_ExamResultMapper();
//                    $examResMapper->deleteByExamId($id);


                    $table_examChapter = new Application_Model_Db_ExamChapter();
                    $table_examChapter->delete($where);
                }
            }

           
            // удаление самого экзамена
            return self::$mapper->deleteById($id, $archive);
        } else {
            return self::$mapper->deleteByQuestionId($id, $archive);
        }
    }

    public function save($postArray) {
        $table_examChapter = new Application_Model_Db_ExamChapter();
        if ($postArray['hiddenid']) {

            $table_examChapter->delete(array('exam_id = ?' => $postArray['hiddenid']));
            foreach ($postArray['chapters'] as $chapter) {
                $table_examChapter->insert(array('exam_id' => $postArray['hiddenid'], 'chapter_id' => $chapter));
            }
            self::$mapper->update($postArray);
        } else {
            $id = self::$mapper->insert($postArray);
            //автоматом добавляем всех пользователей на новый экзамен
//            $userExamMapper = new Application_Model_UserExamMapper();
//            $userExamMapper->addAllUsersOnExam($id, Zend_Auth::getInstance()->getIdentity());
            //добавляем главы на экзамен
            foreach ($postArray['chapters'] as $chapter) {
                $table_examChapter->insert(array('exam_id' => $id, 'chapter_id' => $chapter));
            }

            // создаем билет автомат
            if ($postArray['type'] == 0) {
                $table_examSection = new Application_Model_Db_ExamSection();
                $data = array(
                    'fname' => 'auto',
                    'sname' => 'auto',
                    'exam_id' => $id,
                );
                $table_examSection->insert($data);
            }
        }
    }
    
    public function saveTheme($postArray) {        
        $examThemeMapper = new Application_Model_ExamThemeMapper();
        if ($postArray['hiddenid']) {
            $examThemeMapper->update($postArray);
        }else {
            $examThemeMapper->insert($postArray);
        }
    }
    
    public function deleteTheme($id) {
        $examThemeMapper = new Application_Model_ExamThemeMapper();
        $examThemeMapper->deleteById($id);
        
    }

    
    public function overrideManager($examId, $managerId) {
        //переопеределяем расписание 
        Services_ScheduleService::getInstance()->overrideManager($examId, $managerId);
        //переопределяем владельца экзамена
        self::$mapper->updateManager($examId, $managerId);
    }

    

}
