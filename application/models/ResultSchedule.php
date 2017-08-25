<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultSchedule {
    
    public  $id,
            $examThemeFname,
            $examFname,
            $dateStart,
            $dateEnd,
            $managerFirstName,
            $managerMiddleName,
            $managerLastName,
            $managerDivision,
            $managerSubdivision,
            $committee;
    
    
    
        public function getManagerFullInfo($short = FALSE) {
        if ($short) {
            return $this->managerLastName . ' ' .
                    mb_substr($this->managerFirstName, 0, 1, 'utf-8') . '.' .
                    mb_substr($this->managerMiddleName, 0, 1, 'utf-8');
        } else {
            return $this->managerLastName . ' ' .
                    $this->managerFirstName . ' ' .
                    $this->managerMiddleName;
        }
    }
    

        public function getId() {
        return $this->id;
    }

    public function getExamThemeFname() {
        return $this->examThemeFname;
    }

    public function getExamFname() {
        return $this->examFname;
    }

    public function getDateStart() {
        return $this->dateStart;
    }

    public function getDateEnd() {
        return $this->dateEnd;
    }

    public function getManagerFirstName() {
        return $this->managerFirstName;
    }

    public function getManagerMiddleName() {
        return $this->managerMiddleName;
    }

    public function getManagerLastName() {
        return $this->managerLastName;
    }

    public function getManagerDivision() {
        return $this->managerDivision;
    }

    public function getManagerSubdivision() {
        return $this->managerSubdivision;
    }

    public function getCommittee() {
        return $this->committee;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setExamThemeFname($examThemeFname) {
        $this->examThemeFname = $examThemeFname;
        return $this;
    }

    public function setExamFname($examFname) {
        $this->examFname = $examFname;
        return $this;
    }

    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function setManagerFirstName($managerFirstName) {
        $this->managerFirstName = $managerFirstName;
        return $this;
    }

    public function setManagerMiddleName($managerMiddleName) {
        $this->managerMiddleName = $managerMiddleName;
        return $this;
    }

    public function setManagerLastName($managerLastName) {
        $this->managerLastName = $managerLastName;
        return $this;
    }

    public function setManagerDivision($managerDivision) {
        $this->managerDivision = $managerDivision;
        return $this;
    }

    public function setManagerSubdivision($managerSubdivision) {
        $this->managerSubdivision = $managerSubdivision;
        return $this;
    }

    public function setCommittee($committee) {
        $this->committee = $committee;
        return $this;
    }



}
