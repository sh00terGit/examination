<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamQuestionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ResultExamQuestion');
    }

    public function fetchResultUserQuestions($idResultScheduleUser) {
        
        $result = $this->getDbTable()->fetchAll(array("result_schedule_user_id = ?" => $idResultScheduleUser));
        $examQuestions = array();
        foreach ($result as $row ) {
            $examQuestion = new Application_Model_ResultExamQuestion();
            $examQuestion->setId($row->id)
                ->setQuestionText($row->question_text);
                //->setResultScheduleUser($row->result_schedule_user_id);
            $examQuestions[] = $examQuestion;
        }
        return $examQuestions;
    }

   public function find($id) {

        $examQuestion = new Application_Model_ResultExamQuestion();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $examQuestion->setId($row->id)
                ->setQuestionText($row->question_text)
                ->setResultScheduleUser($row->result_schedule_user_id);
        
        return $examQuestion;
    }
    
    
    public function insert($data ) {
        $id = $this->getDbTable()->insert($data);
        return $id;
    }
    
    
     public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
}
