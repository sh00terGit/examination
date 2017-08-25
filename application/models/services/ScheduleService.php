<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_ScheduleService {

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
        self::$mapper = new Application_Model_ScheduleMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    
    /**
     * Вытаскивает все в зависимости от роли
     * @param zend_auth $userIdenity
     * @return array $schedules   массив расписаний
     */
    public function fetchAll($userIdenity) {
        $userId = $userIdenity->id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchSchedules($userId, $roleId);
    }
    
    public function find($id) {
        return self::$mapper->find($id);
    }

    public function delete($id, $archive = false, $where = null) {
        if ($where == null) {
            return self::$mapper->deleteById($id, $archive);
        } else {
            return self::$mapper->deleteByExamId($id, $archive);
        }
    }

    public function save($postArray) { 
        if ($postArray['hiddenid']) {
            self::$mapper->update($postArray);
            $postArray['division'] != '0';             
             $scheduleId = $postArray['hiddenid'];
        } else {
             $scheduleId = self::$mapper->insert($postArray);
        } 
        if(($postArray['division'] != NULL) && ($postArray['division'] != '0')) {
            $mapper = new Application_Model_UserScheduleMapper();
            $mapper->addAllUsersOnExam($scheduleId,$_POST['attempt'],  Zend_Auth::getInstance()->getIdentity());
        }
        
        //если заполнено поле подразделение - то выбираем всех пользователей с подразделения
        if($postArray['subDivision'] != null) {
            $mapper = new Application_Model_UserScheduleMapper();
            foreach ($postArray['subDivision'] as $subDivision) {            
                $mapper->addSubdivisionUsersOnExam($scheduleId,$_POST['attempt'], $subDivision);
            }
        }
    }

    public function checkInSchedule($exams) {
        $checkedExams = array();
        foreach ($exams as $exam) {
            $checkedExams[] = self::$mapper->checkInSchedule($exam);
        }
        return $checkedExams;
    }

    public function overrideManager($examId, $managerId) {
        self::$mapper->updateManager($examId, $managerId);
    }

    public function fetchDivisionScheduleByPersonThemes($userIdenity) {
        $themesId = Services_UserThemeService::getInstance()->fetchByManagerInArray($userIdenity);
        if ($themesId != null) {
            $themesString = implode(',', $themesId);
        } else {
            $themesString = 'null';
        }
        $divisionId = $userIdenity->division_id;
        return self::$mapper->fetchDivisionScheduleByPersonThemes($themesString, $divisionId);
    }

    public function fetchSchedulesByPersonThemes($userIdenity) {
        $themesId = Services_UserThemeService::getInstance()->fetchByManagerInArray($userIdenity);
        if ($themesId != null) {
            $themesString = implode(',', $themesId);
        } else {
            $themesString = 'null';
        }
        return self::$mapper->fetchSchedulesByPersonThemes($themesString, $userIdenity->id);
    }

}
