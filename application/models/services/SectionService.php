<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_SectionService {

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
        self::$mapper = new Application_Model_SectionMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }

    public function refreshBilet($postArray){
        
        //get submitted bilet id 
        $submit = array_search('изменить', $postArray);
        $processed = explode('_', $submit);
        $biletId = $processed[1];

        // get bilet feathure
        $biletName = "bilet_$biletId";
        $bilet = $_POST[$biletName];
        if ($bilet) {
            foreach ($bilet as $destinationQuestion => $val) {
                if ($val) {
                    $processed = explode('_', $destinationQuestion);
                    $question_id = $processed[1];
                    $destinationToadd[] = $question_id;
                }
            }
        }
        //get added question ids

        $sourceQuestions = preg_grep("/source_/", array_keys($postArray));
        if (count($sourceQuestions) != null) {
            $questionsToAdd = array();
            foreach ($sourceQuestions as $question) {
                $id = substr($question, 7);
                $questionsToAdd[] = $id;
            }
        }
        
        
        return self::$mapper->refreshBilet($biletId, $questionsToAdd, $destinationToadd);
    }

        public function fetchAll($userIdenity) {
        $userId = $userIdenity->id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchSections($userId, $roleId);
    }
    
     public function fetchByExam($examId, $userIdenity) {
        return self::$mapper->fetchSections($examId, $userIdenity);
    }

    public function find($id) {
        return self::$mapper->find($id);
    }
    

    public function delete($id, $archive = false, $where = null) {
        if ($where == null) {
            
            $table_exam_quest = new Application_Model_Db_ExamQuest();
            $whereSectionID = array("exam_section_id = ?" => $id);
            $table_exam_quest->delete($whereSectionID);

            return self::$mapper->deleteById($id, $archive);
            
        } else {
            return self::$mapper->deleteByExamId($id, $archive);
        }
    }

    public function save($postArray) {
        if ($postArray['hiddenid']) {
            self::$mapper->update($postArray);
        } else {
            self::$mapper->insert($postArray);
        }
    }

}
