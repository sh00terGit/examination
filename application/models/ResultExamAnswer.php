<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamAnswer {
    
    public  $id,
            $answerText,
            $positive,
            $resultExamQuestion;
    
    public function getId() {
        return $this->id;
    }

    public function getAnswerText() {
        return $this->answerText;
    }

    public function getPositive() {
        return $this->positive;
    }

    public function getResultExamQuestion() {
        return $this->resultExamQuestion;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setAnswerText($answerText) {
        $this->answerText = $answerText;
        return $this;
    }

    public function setPositive($positive) {
        $this->positive = $positive;
        return $this;
    }

    public function setResultExamQuestion($resultExamQuestion) {
        $mapper = new Application_Model_ResultExamQuestionMapper();
        $this->resultExamQuestion = $mapper->find($resultExamQuestion);
        return $this;
    }



    
    

}
