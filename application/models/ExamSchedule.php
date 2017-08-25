<?php

/*
 *   --  Модель для Связки экзамен-расписание ----
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ExamSchedule {

    public $id,
            $division,
            $subdivision,
            $manager,
            $fname,
            $sname,
            $criterion,
            $dateStart,
            $dateEnd,
            $active,
            $scheduleId,
            $committee,
            $type_auth,
            $password;

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDivision() {
        return $this->division;
    }

    public function setDivision($division) {
//        if ($division != 0) {
        $this->division = Services_DivisionService::getInstance()->find($division);
//        }
        return $this;
    }

    public function getSubDivision() {
        return $this->subdivision;
    }

    public function setSubDivision($subdivision) {
//        if ($subdivision != 0) {
            $this->subdivision = Services_SubDivisionService::getInstance()->find($subdivision);
//        }
        return $this;
    }

    public function getManager() {
        return $this->manager;
    }

    public function setManager($manager) {
        $userMapper = new Application_Model_UserMapper();
        $this->manager = $userMapper->findObject($manager);
        return $this;
    }

    public function getFname() {
        return $this->fname;
    }

    public function setFname($fname) {
        $this->fname = $fname;
        return $this;
    }

    public function getSname() {
        return $this->sname;
    }

    public function setSname($sname) {
        $this->sname = $sname;
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

    public function setScheduleDateStart($date_start) {
        $this->dateStart = $date_start;
        return $this;
    }

    public function getScheduleDateStart() {
        return $this->dateStart;
    }

    public function setScheduleDateEnd($date_end) {
        $this->dateEnd = $date_end;
        return $this;
    }

    public function getScheduleDateEnd() {
        return $this->dateEnd;
    }

    public function getActive() {
        return $this->active;
    }

    public function setActive($active_key) {
        $this->active = $active_key;
        return $this;
    }

    public function getScheduleId() {
        return $this->scheduleId;
    }

    public function setScheduleId($id) {
        $this->scheduleId = $id;
        return $this;
    }

    public function getCommittee() {
        return $this->committee;
    }

    public function setCommittee($committee) {
        $this->committee = $committee;
        return $this;
    }

    public function getTypeAuth() {
        return $this->type_auth;
    }

    public function setTypeAuth($id) {
        $this->type_auth = $id;
        return $this;
    }

}
