<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserScheduleMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_UserSchedule');
    }


    public function fetchUsers($scheduleId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('schedule_id= ?', $scheduleId);
        $result = $this->getDbTable()->fetchAll($where);
        $userSchedule = array();
        foreach ($result as $row) {
            $userSchedule = new Application_Model_UserSchedule();
            $userSchedule
                    ->setSchedule($row->schedule_id)
                    ->setUser($row->user_id)
                    ->setTry($row->try)
                    ->setId($row->id);
            $userSchedules[] = $userSchedule;
        }
        return $userSchedules;
    }
    
    
    /**
     * Обновляет таблицу связки пользователей на экзамен (добавляет , удаляет отмеченных)
     * @param type $usersToAdd  к добавлению
     * @param type $usersToDelete  к удалению
     * @param type $scheduleId id расписания
     * @param type $attempt количество попыток
     */
    public function refreshUsers($usersToAdd, $usersToDelete, $scheduleId, $attempt) {          
        $try = ($attempt > 0 ) ? $attempt : '-1';        
        if (count($usersToDelete)) {
            foreach ($usersToDelete as $userId) {
                $result = $this->getDbTable()->delete(array("user_id = ?" => $userId));
            }
        }
        if (count($usersToAdd)) {
            foreach ($usersToAdd as $userId) {
                $data = array(
                    'schedule_id' => $scheduleId,
                    'try' => $try,
                    'user_id' => $userId);
                $result = $this->getDbTable()->insert($data);
            }
        }
    }
    
    /**
     *  Добавляет пользователей отделения на экзамен с кол-вом попыток
     * @param int $scheduleId id расписания
     * @param int $attempt   количество попыток
     * @param Zend_Auth $userIdentity   
     */ 
    public function addAllUsersOnExam($scheduleId,$attempt ,$userIdentity){
        $usersOfDivision = Services_UserService::getInstance()->fetchAll($userIdentity);
        $try = ($attempt > 0 ) ? $attempt : '-1';   // если количество попыток не указано  -1 в базу как бесконечность
        foreach ($usersOfDivision as $user ) {
            $data = array(
                    'schedule_id' => $scheduleId,
                    'try' => $try,
                    'user_id' => $user->getId());
            $result = $this->getDbTable()->insert($data);
        }
    }
    
    /**
     *  Добавляет пользователей предприятия на экзамен с кол-вом попыток
     * @param int $scheduleId id расписания
     * @param int $attempt   количество попыток
     * @param int $subDivisionId id предприятия
     */ 
    public function addSubdivisionUsersOnExam($scheduleId,$attempt , $subDivisionId) {
        $usersOfSubdivision = Services_UserService::fetchUsersBySubDivision($subDivisionId);
        $try = ($attempt > 0 ) ? $attempt : '-1';  
        foreach ($usersOfSubdivision as $user ) {
            $data = array(
                    'schedule_id' => $scheduleId,
                    'try' => $try,
                    'user_id' => $user->getId());
            $result = $this->getDbTable()->insert($data);
        }
    }

        public function deleteByScheduleId($id, $archive = false) {
        
        $whereId = array("exam_id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    /**
     * Показывает количество попыток пользователя
     * @param ind $userId пользователь
     * @param int $scheduleId  расписание
     * @return string $row->try  количество попыток
     */    
    public function findAttemptsByUser ($userId,$scheduleId) {
        $row = $this->getDbTable()->fetchRow("user_id = $userId and schedule_id = $scheduleId");
        return $row->try;
    }
    
    public function reduceAttempt($userId,$scheduleId){
        $this->getDbTable()->update(array('try' => new Zend_Db_Expr('try -1')),
                "user_id = $userId and 
                schedule_id = $scheduleId and
                try > 0 ");
    }

}
