<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamAnswerUserMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ResultExamAnswerUser');
    }

    public function fetchAll() {

        $result = $this->getDbTable()->fetchAll($where);
        $examAnswersUser = array();
        foreach ($result as $row) {
            $examAnswerUser = new Application_Model_ResultExamAnswerUser();
            $examAnswerUser->setId($row->id)
                    ->setResultExamAnswer($row->result_exam_answer_id)
                    ->setResultExamQuestion($row->result_exam_question_id);
            $examAnswersUser[] = $examAnswerUser;
        }
        return $examAnswersUser;
    }

    public function find($id) {

        $examAnswerUser = new Application_Model_ResultExamAnswerUser();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $examAnswerUser->setId($row->id)
                ->setResultExamAnswer($row->result_exam_answer_id)
                ->setResultExamQuestion($row->result_exam_question_id);

        return $examAnswerUser;
    }

    public function insert($resultExamQuestionId, $resultExamAnswerId) {
        $data = array(
            'result_exam_answer_id' => $resultExamAnswerId,
            'result_exam_question_id' => $resultExamQuestionId,
        );
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
