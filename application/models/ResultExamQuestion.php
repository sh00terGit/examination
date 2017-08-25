<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultExamQuestion {
    
    public  $id,
            $questionText,
            $resultScheduleUser;
    
    public function getId() {
        return $this->id;
    }

    public function getQuestionText() {
        return $this->questionText;
    }

    public function getResultScheduleUser() {
        return $this->resultScheduleUser;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setQuestionText($questionText) {
        $this->questionText = $questionText;
        return $this;
    }

    public function setResultScheduleUser($resultScheduleUser) {
        $mapper = new Application_Model_ResultScheduleUserMapper();
        $this->resultScheduleUser = $mapper->find($resultScheduleUser);
        return $this;
    }


    
    

}
