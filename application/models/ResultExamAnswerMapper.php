<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamAnswerMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ResultExamAnswer');
    }

    public function fetchResultExamAnswer($idResultExamQuestion) {

        $result = $this->getDbTable()->fetchAll(array("result_exam_question_id = ?" => $idResultExamQuestion));
        $examAnswers = array();
        foreach ($result as $row) {
            $examAnswer = new Application_Model_ResultExamAnswer();
            $examAnswer->setId($row->id)
                    ->setAnswerText($row->answer_text)
                    ->setPositive($row->positive)
                    ->setResultExamQuestion($row->result_exam_question_id);
            $examAnswers[] = $examAnswer;
        }
        return $examAnswers;
    }

    public function find($id) {

        $examAnswer = new Application_Model_ResultExamAnswer();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $examAnswer->setId($row->id)
                ->setAnswerText($row->answer_text)
                ->setPositive($row->positive)
                ->setResultExamQuestion($row->result_exam_question_id);

        return $examAnswer;
    }

    public function insert($data) {
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
