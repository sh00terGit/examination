<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ScheduleMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Schedule');
    }

    public function fetchSchedules($userId, $roleId) {
        if ($roleId == 1) {
            $where = null;
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('manager_id = ?', $userId);
        }
        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();
        $result = $this->getDbTable()->fetchAll($where);
        $resultInArray = $result->toArray();
        if (!($resultInArray === $resCache->load("result_fetchSchedules_$userId"))) {
            $resCache->save($resultInArray, "result_fetchSchedules_$userId", array("schedule_$userId"));
            $schedules = array();
            foreach ($result as $row) {
                $schedule = new Application_Model_Schedule();
                $schedule->setId($row->id)
                        ->setCommittee($row->committee)
                        ->setAttempt($row->attempt)
                        ->setTimePass($row->time_pass)
                        ->setCriterion($row->criterion_id)
                        ->setExam($row->exam_id)
                        ->setDateStart($row->date_start)
                        ->setDateEnd($row->date_end)
                        ->setManager($row->manager_id)
                        ->setActive($row->active_key)
                        ->setComment($row->comment)
                        ->setPassword($row->password)
                        ->setSubDivision($row->subdiv_id)
                        ->setAuthType($row->auth_type_id);

                $schedules[] = $schedule;
            }
            $resCache->save($schedules, "fetchSchedules_$userId", array("schedule_$userId"));
        } else {
            $schedules = $resCache->load("fetchSchedules_$userId");
        }
        return $schedules;
    }

    /**
     * Выборка расписаний по темам экзаменов
     * 
     * @param string $themesId  - ( темы экзамена через ,  напр (2,4))
     * @return schedules
     */
    public function fetchSchedulesByPersonThemes($themesId, $managerId) {
        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from('schedule')
                ->joinleft('exam', 'schedule.exam_id = exam.id', null)
                ->where('exam.archive = 0 and exam.exam_theme_id IN (?)', $themesId)
                ->where('schedule.manager_id = ?', $managerId)
                ->order('schedule.date_start ASC');
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
        $schedules = array();
        foreach ($result as $row => $value) {            
            $schedule = new Application_Model_Schedule();
            $schedule->setId($value['id'])
                    ->setExam($value['exam_id'])
                    ->setDateStart($value['date_start'])
                    ->setDateEnd($value['date_end'])
                    ->setCriterion($value['criterion_id'])
                    //   ->setManager($value['manager_id'])
                    ->setActive($value['active_key'])
                    ->setSubDivision($value['subdiv_id'])
                    ->setAuthType($value['auth_type_id']);

            $schedules[] = $schedule;
        }
        return $schedules;
    }

    public function fetchDivisionScheduleByPersonThemes($themesId, $divisionId) {
        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from('schedule')
                ->joinleft('exam', 'schedule.exam_id = exam.id', null)
                ->where('exam.archive = 0 and exam.exam_theme_id IN (?)', $themesId)
                ->joinleft('users', 'exam.manager_id = users.id', null)
                ->where('users.division_id =?', $divisionId)
                ->order('schedule.date_start ASC');
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
        $schedules = array();
        foreach ($result as $row => $value) {
            $schedule = new Application_Model_Schedule();
            $schedule->setId($value['id'])
                    ->setExam($value['exam_id'])
                    ->setDateStart($value['date_start'])
                    ->setDateEnd($value['date_end'])
                    ->setManager($value['manager_id'])
                    ->setCriterion($value['criterion_id'])
                    ->setActive($value['active_key'])
                    ->setSubDivision($value['subdiv_id'])
                    ->setAuthType($value['auth_type_id']);

            $schedules[] = $schedule;
        }
        return $schedules;
    }

    public function find($id) {

        $schedule = new Application_Model_Schedule();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $schedule->setId($row->id)
                ->setCommittee($row->committee)
                ->setAttempt($row->attempt)
                ->setTimePass($row->time_pass)
                ->setCriterion($row->criterion_id)
                ->setExam($row->exam_id)
                ->setDateStart($row->date_start)
                ->setDateEnd($row->date_end)
                ->setManager($row->manager_id)
                ->setSubDivision($row->subdiv_id)
                ->setActive($row->active_key)
                ->setComment($row->comment)
                ->setPassword($row->password)
                ->setAuthType($row->auth_type_id);

        return $schedule;
    }

    public function checkInSchedule($exam) {
        $currentDate = strtotime(date('Y-m-d'));
        $where = $this->getDbTable()->getAdapter()->quoteInto('exam_id = ?', $exam->getId());
        $result = $this->getDbTable()->fetchAll($where);
        foreach ($result as $row) {
            $exam->setScheduleDateStart($row->date_start)->setScheduleDateEnd($row->date_end);
            if (( $currentDate >= strtotime($row->date_start) ) && ($currentDate <= strtotime($row->date_end))) {
                $exam->schedule = 'current';
            } elseif ($currentDate < strtotime($row->date_start)) {
                $exam->schedule = 'future';
            } elseif ($currentDate > strtotime($row->date_end)) {
                $exam->schedule = 'post';
            }
        }
        return $exam;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    public function deleteByExamId($id, $archive = false) {

        $whereId = array("exam_id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    /**
     * 
     * 
     * `id` INT(11) NOT NULL AUTO_INCREMENT,
      `date_start` DATE NOT NULL,
      `date_end` DATE NOT NULL,
      `exam_id` INT(11) NOT NULL,
      `subdiv_id` INT(11) NOT NULL,
      `active_key` TINYINT(1) NOT NULL DEFAULT '1',
      `manager_id` INT(11) NOT NULL,
      `auth_type_id` INT(11) NOT NULL,
      `comment` VARCHAR(255) NOT NULL,
      `password` VARCHAR(255)  NULL,
     * 
     * 
     * 
     */
    public function insert($postArray) {
        $data = array(
            'exam_id' => $postArray['exam_id'],
            'date_start' => $postArray['dateStart'],
            'date_end' => $postArray['dateEnd'],
            'manager_id' => $postArray['manager_id'],
            'password' => ($postArray['authType'] === '1') ? sha1($postArray['password']) : 'null',
            'active_key' => $postArray['active'],
            'subdiv_id' => $postArray['subDivision'],
            'auth_type_id' => $postArray['authType'],
            'comment' => $postArray['comment'],
            'criterion_id' => $postArray['criterion'],
            'attempt' => $postArray['attempt'],
            'time_pass' => $postArray['time_pass'],
            'committee' => $postArray['committee']
        );
        $id = $this->getDbTable()->insert($data);
        return $id;
    }

    public function update($postArray) {
        $data = array(
            'exam_id' => $postArray['exam_id'],
            'date_start' => $postArray['dateStart'],
            'date_end' => $postArray['dateEnd'],
            'manager_id' => $postArray['manager_id'],
            'password' => ($postArray['authType'] === '1') ? sha1($postArray['password']) : 'null',
            'active_key' => $postArray['active'],
            'subdiv_id' => $postArray['subDivision'],
            'auth_type_id' => $postArray['authType'],
            'comment' => $postArray['comment'],
            'criterion_id' => $postArray['criterion'],
            'attempt' => $postArray['attempt'],
            'time_pass' => $postArray['time_pass'],
            'committee' => $postArray['committee']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

    public function updateManager($examId, $managerId) {
        $data = array('manager_id' => $managerId);
        $where = array('exam_id = ?' => $examId);
        $id = $this->getDbTable()->update($data, $where);
        return $id;
    }

}
