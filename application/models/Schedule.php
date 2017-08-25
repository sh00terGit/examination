<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Schedule {

    public $id,
            $exam,
            $dateStart,
            $dateEnd,
            $managerId,
            $active,
            $auth_type,
            $comment,
            $subDivision,
            $password,
            $committee,
            $timePass,
            $attempt,
            $criterion;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getExam() {
        return $this->exam;
    }

    public function setExam($examId) {
        $examMapper = new Application_Model_ExamMapper();
        $this->exam = $examMapper->findById($examId);
        return $this;
    }

    public function getDateStart() {
        return $this->dateStart;
    }

    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getDateEnd() {
        return $this->dateEnd;
    }

    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function getManager() {
        return $this->manager;
    }

    public function setManager($managerId) {
        $userMapper = new Application_Model_UserMapper();
        $this->manager = $userMapper->findObject($managerId);
        return $this;
    }

    public function setActive($bool) {
        $this->active = $bool;
        return $this;
    }

    public function getActive() {
        return $this->active;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setAuthType($id) {
        $table = new Application_Model_Db_AuthType();
        $active = $table->find($id);
        $this->auth_type = $active->current();
    }

    public function getAuthType() {
        return $this->auth_type->fname;
    }

    public function getAuthTypeId() {
        return $this->auth_type->id;
    }

    public function setSubDivision($id) {
        $this->subDivision = Services_SubDivisionService::getInstance()->find($id);
        return $this;
    }

    public function getSubDivision() {
        return $this->subDivision;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($pass) {
        $this->password = $pass;
        return $this;
    }
    
     public function setTimePass($timePass) {
        $this->timePass = $timePass;
        return $this;
    }

    public function getTimePass() {
        return $this->timePass;
    }

    public function setAttempt($attempt) {
        $this->attempt = $attempt;
        return $this;
    }

    public function getAttempt() {
        return $this->attempt;
    }

    public function getCommittee() {
        return $this->committee;
    }

    public function setCommittee($committee) {
        $this->committee = $committee;
        return $this;
    }
    
     public function getCriterion() {
        return $this->criterion;
    }

    public function setCriterion($criterion) {
        $criterionMapper = new Application_Model_CriterionMapper();
        $this->criterion = $criterionMapper->find($criterion);
        return $this;
    }

}
