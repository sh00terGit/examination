<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamAnswerUser {
    
    public  $id,
            $resultExamQuestion,
            $resultExamAnswer;
  
    public function getId() {
        return $this->id;
    }

    public function getResultExamQuestion() {
        return $this->resultExamQuestion;
    }

    public function getResultExamAnswer() {
        return $this->resultExamAnswer;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setResultExamQuestion($resultExamQuestion) {
        $this->resultExamQuestion = $resultExamQuestion;
        return $this;
    }

    public function setResultExamAnswer($resultExamAnswer) {
        $this->resultExamAnswer = $resultExamAnswer;
        return $this;
    }


    
    

}
