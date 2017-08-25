<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_ResultService {

    protected static $_instance;

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
        return self::$_instance; // возвращаем экземпляр данного класса
    }

    public function saveResultSchedule($postArray) {
        $resultScheduleMapper = new Application_Model_ResultScheduleMapper();
        $resultScheduleId = $resultScheduleMapper->insert($postArray);

        return $resultScheduleId;
    }
    
    public function findResultSchedule($id) {
        $resultScheduleMapper = new Application_Model_ResultScheduleMapper();
        return $resultScheduleMapper->find($id);
    }

        public function fetchAllResultSchedule() {
        $resultScheduleMapper = new Application_Model_ResultScheduleMapper();
        return $resultScheduleMapper->fetchAll();
    }
    
    public function fetchResultUsers($idResultSchedule) {
        $resultScheduleUserMapper = new Application_Model_ResultScheduleUserMapper();
        return $resultScheduleUserMapper->fetchResultUsers($idResultSchedule);
    }
    
    public function findResultUser($id) {
        $resultScheduleUserMapper = new Application_Model_ResultScheduleUserMapper();
        return $resultScheduleUserMapper->find($id);
    }

    

    public function fetchResultUserQuestions($idResultScheduleUser) {
        $resultExamQuestionMapper = new Application_Model_ResultExamQuestionMapper();
        return $resultExamQuestionMapper->fetchResultUserQuestions($idResultScheduleUser);
    }

    public function selectIdResultSchedule($data) {
        $resultScheduleMapper = new Application_Model_ResultScheduleMapper();
        $id = $resultScheduleMapper->selectIdResultSchedule($data);
        return $id;
    }

    public function saveResultScheduleUser($postArray,$resultScheduleId) {
        $resultScheduleUserMapper = new Application_Model_ResultScheduleUserMapper();
        $resultScheduleUserId = $resultScheduleUserMapper->insert($postArray, $resultScheduleId);
        return $resultScheduleUserId;
    }

    public function saveResultExamQuestion($postArray) {
        $resultExamQuestionMapper = new Application_Model_ResultExamQuestionMapper();
        $resultExamQuestionId = $resultExamQuestionMapper->insert($postArray);
        return $resultExamQuestionId;
    }

    public function saveResultExamAnswer($postArray) {
        $resultExamAnswerMapper = new Application_Model_ResultExamAnswerMapper();
        $resultExamAnswerId = $resultExamAnswerMapper->insert($postArray);
        return $resultExamAnswerId;
    }

    public function saveResultExamUserAnswer($resultExamQuestionId,$resultExamAnswerId) {
        $resultExamUserAnswerMapper = new Application_Model_ResultExamAnswerUserMapper();
        $resultExamUserAnswerId = $resultExamUserAnswerMapper->insert($resultExamQuestionId, $resultExamAnswerId);
        return $resultExamUserAnswerId;
    }
    
    
    public function updateResultUserPositive ($idResultScheduleUser) {
        $resultScheduleUserMapper = new Application_Model_ResultScheduleUserMapper();
        $resultScheduleUserMapper->updateResultUserPositive($idResultScheduleUser);
    }

}
