<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultScheduleUserMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ResultScheduleUser');
    }

    public function fetchResultUsers($idResultSchedule) {
        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();
        $result = $this->getDbTable()->fetchAll(array("result_schedule_id = ?" => $idResultSchedule),$order = 'date_pass DESC',$limit = 100);
        $resultInArray = $result->toArray();
        if (!($resultInArray === $resCache->load("result_resultSchedule_$idResultSchedule"))) {
            $resCache->save($resultInArray, "result_resultSchedule_$idResultSchedule");
            $resultScheduleUsers = array();
            foreach ($result as $row) {
                $resultScheduleUser = new Application_Model_ResultScheduleUser();
                $resultScheduleUser->setId($row->id)
                        ->setCriterionValue($row->criterion_value)
                        ->setDatePass($row->date_pass)
                        ->setExamTimePass($row->exam_time_pass)
                        ->setMark($row->mark)
                        //      ->setResultSchedule($row->result_schedule_id)
                        ->setUserDivision($row->user_division)
                        ->setUserSubdivision($row->user_subdivision)
                        ->setUserFirstName($row->user_first_name)
                        ->setUserMiddleName($row->user_middle_name)
                        ->setUserLastName($row->user_last_name);
                $resultScheduleUsers [] = $resultScheduleUser;
            }
            $resCache->save($resultScheduleUsers, "resultSchedule_$idResultSchedule");
        } else {
            $resultScheduleUsers = $resCache->load("resultSchedule_$idResultSchedule");
        }
        return $resultScheduleUsers;
    }

    public function find($id) {

        $resultScheduleUser = new Application_Model_ResultScheduleUser();
        $result = $this->getDbTable()->find($id);

        $row = $result->current();
        $resultScheduleUser->setId($row->id)
                ->setCriterionValue($row->criterion_value)
                ->setDatePass($row->date_pass)
                ->setExamTimePass($row->exam_time_pass)
                ->setMark($row->mark)
                //  ->setResultSchedule($row->result_schedule_id)
                ->setUserDivision($row->user_division)
                ->setUserSubdivision($row->user_subdivision)
                ->setUserFirstName($row->user_first_name)
                ->setUserMiddleName($row->user_middle_name)
                ->setUserLastName($row->user_last_name);

        return $resultScheduleUser;
    }

    public function insert($postArray, $result_schedule_id) {
        $data = array(
            'criterion_value' => $postArray['criterion_value'],
            'exam_time_pass' => $postArray['exam_time_pass'],
            'mark' => $postArray['mark'],
            'result_schedule_id' => $result_schedule_id,
            'user_division' => $postArray['user_division'],
            'user_subdivision' => $postArray['user_subdivision'],
            'user_first_name' => $postArray['user_first_name'],
            'user_middle_name' => $postArray['user_middle_name'],
            'user_last_name' => $postArray['user_last_name'],
        );
        $id = $this->getDbTable()->insert($data);
        return $id;
    }

    public function updateResultUserPositive($idResultScheduleUser) {
        $this->getDbTable()->update(
                array('mark' => '1'), array("id = ?" => $idResultScheduleUser)
        );
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

}
