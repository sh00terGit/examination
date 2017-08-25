<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_QuestionService {

    protected static $_instance ;
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
        self::$mapper = new Application_Model_QuestionMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    
    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchQuestions($divisionId, $roleId);
    }
    
    public function fetchByChapter($examId,$userIdentity ){
        $divisionId = $userIdentity->division_id;
        $roleId = $userIdentity->role_id;
        $table_examChapter = new Application_Model_Db_ExamChapter();
        $whereExamId = array("exam_id = ?" => $examId); 
        $examChapters = $table_examChapter->fetchAll($whereExamId);
        $questions = array();
        foreach ($examChapters as $examChapter) {
            $questions  =array_merge_recursive(self::$mapper->fetchByChapter($examChapter->chapter_id,$divisionId, $roleId),$questions);
        }
        return $questions;
    }

        public function find($id) {
        return self::$mapper->find($id);
    }
    
    public function delete($id , $archive) {
        Services_AnswerService::getInstance()->delete($id , $archive , true);
        
        return self::$mapper->deleteById($id , $archive);
    }
    
    public function save($postArray){
         if ($postArray['hiddenid']) {
             self::$mapper->update($postArray);
         }  else {
             self::$mapper->insert($postArray);
         }
    }
    
    
    public function fetchByExam($examId) {
         return self::$mapper->fetchByExam($examId);
    }

}
