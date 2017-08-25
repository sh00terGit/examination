<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultScheduleUser {

    public $id,
            $userFirstName,
            $userMiddleName,
            $userLastName,
            $userDivision,
            $usersubdivision,
            $datePass,
            $criterionValue,
            $examTimePass,
            $mark,
            $resultSchedule;
    
    
    
        public function getUserFullInfo($short = FALSE) {
        if ($short) {
            return $this->userLastName . ' ' .
                    mb_substr($this->userFirstName, 0, 1, 'utf-8') . '.' .
                    mb_substr($this->userMiddleName, 0, 1, 'utf-8');
        } else {
            return $this->userLastName . ' ' .
                    $this->userFirstName . ' ' .
                    $this->userMiddleName;
        }
    }
    

    public function getId() {
        return $this->id;
    }

    public function getUserFirstName() {
        return $this->userFirstName;
    }

    public function getUserMiddleName() {
        return $this->userMiddleName;
    }

    public function getUserLastName() {
        return $this->userLastName;
    }

    public function getUserDivision() {
        return $this->userDivision;
    }

    public function getUserSubdivision() {
        return $this->usersubdivision;
    }

    public function getDatePass() {
        return $this->datePass;
    }

    public function getCriterionValue() {
        return $this->criterionValue;
    }

    public function getExamTimePass() {
        return $this->examTimePass;
    }

    public function getMark() {
        return $this->mark;
    }

    public function getResultSchedule() {
        return $this->resultSchedule;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setUserFirstName($userFirstName) {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    public function setUserMiddleName($userMiddleName) {
        $this->userMiddleName = $userMiddleName;
        return $this;
    }

    public function setUserLastName($userLastName) {
        $this->userLastName = $userLastName;
        return $this;
    }

    public function setUserDivision($userDivision) {
        $this->userDivision = $userDivision;
        return $this;
    }

    public function setUserSubdivision($usersubdivision) {
        $this->usersubdivision = $usersubdivision;
        return $this;
    }

    public function setDatePass($datePass) {
        $this->datePass = $datePass;
        return $this;
    }

    public function setCriterionValue($criterionValue) {
        $this->criterionValue = $criterionValue;
        return $this;
    }

    public function setExamTimePass($examTimePass) {
        $this->examTimePass = $examTimePass;
        return $this;
    }

    public function setMark($mark) {
        $this->mark = $mark;
        return $this;
    }

    public function setResultSchedule($resultScheduleId) {
        $mapper = new Application_Model_ResultScheduleMapper();
        $this->resultSchedule = $mapper->find($resultScheduleId);
        return $this;
    }


}
