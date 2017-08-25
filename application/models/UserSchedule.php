<?php

class Application_Model_UserSchedule {

    public $id,
            $user,
            $try,
            $schedule;
    
    
    
    public function getTry() {
        return $this->try;
    }

    public function setTry($try) {
        $this->try = $try;
        return $this;
    }

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }
    
    public function setUser($id) {
        $userMapper = new Application_Model_UserMapper();
        $this->user = $userMapper->findObject($id);
        return $this;
    }
    
    public function getSchedule() {
        return $this->schedule;
    }
    
    public function setSchedule($scheduleId) {
        $scheduleMapper = new Application_Model_ScheduleMapper();
        $this->schedule = $scheduleMapper->find($scheduleId);
        return $this;
    }
}
