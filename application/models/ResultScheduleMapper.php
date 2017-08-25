<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ResultScheduleMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ResultSchedule');
    }

    public function find($id) {

        $resultSchedule = new Application_Model_ResultSchedule();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $resultSchedule->setId($row->id)
                ->setCommittee($row->committee)
                ->setDateEnd($row->date_end)
                ->setDateStart($row->date_start)
                ->setExamFname($row->exam_fname)
                ->setExamThemeFname($row->exam_theme_fname)
                ->setManagerDivision($row->manager_division)
                ->setManagerSubdivision($row->manager_subdivision)
                ->setManagerFirstName($row->manager_first_name)
                ->setManagerMiddleName($row->manager_middle_name)
                ->setManagerLastName($row->manager_last_name);

        return $resultSchedule;
    }

    public function fetchAll() {

        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();

        $result = $this->getDbTable()->fetchAll($where ,$order = 'date_start DESC',$limit = 100);
        $resultInArray = $result->toArray();
        if (!($resultInArray === $resCache->load("result_resultSchedules"))) {
            $resCache->save($resultInArray, "result_resultSchedules");
            $resultSchedules = array();
            foreach ($result as $row) {
                $resultSchedule = new Application_Model_ResultSchedule();
                $resultSchedule->setId($row->id)
                        ->setCommittee($row->committee)
                        ->setDateEnd($row->date_end)
                        ->setDateStart($row->date_start)
                        ->setExamFname($row->exam_fname)
                        ->setExamThemeFname($row->exam_theme_fname)
                        ->setManagerDivision($row->manager_division)
                        ->setManagerSubdivision($row->manager_subdivision)
                        ->setManagerFirstName($row->manager_first_name)
                        ->setManagerMiddleName($row->manager_middle_name)
                        ->setManagerLastName($row->manager_last_name);
                $resultSchedules [] = $resultSchedule;
            }
            $resCache->save($resultSchedules, "resultSchedules");
        } else {
            $resultSchedules = $resCache->load("resultSchedules");
        }
        return $resultSchedules;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    public function insert($postArray) {
        $data = array(
            'committee' => $postArray['committee'],
            'date_start' => $postArray['date_start'],
            'date_end' => $postArray['date_end'],
            'exam_fname' => $postArray['exam_fname'],
            'exam_theme_fname' => $postArray['exam_theme_fname'],
            'manager_division' => $postArray['manager_division'],
            'manager_subdivision' => $postArray['manager_subdivision'],
            'manager_first_name' => $postArray['manager_first_name'],
            'manager_middle_name' => $postArray['manager_middle_name'],
            'manager_last_name' => $postArray['manager_last_name'],
        );
        $id = $this->getDbTable()->insert($data);
        return $id;
    }

    public function update($postArray) {
        $data = array(
            'committee' => $postArray[''],
            'date_start' => $postArray[''],
            'date_end' => $postArray[''],
            'exam_fname' => $postArray[''],
            'exam_theme_fname' => $postArray[''],
            'manager_division' => $postArray[''],
            'manager_subdivision' => $postArray[''],
            'manager_first_name' => $postArray[''],
            'manager_middle_name' => $postArray[''],
            'manager_last_name' => $postArray[''],
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

    public function selectIdResultSchedule($data) {
        $sql = $this->getDbTable()->getAdapter()
                ->select()
                ->from('result_schedule', array('count' => 'count(*)', 'id'))
                ->where('exam_theme_fname =?', $data['exam_theme_fname'])
                ->where('exam_fname =?', $data['exam_fname'])
                ->where('date_start =?', $data['date_start'])
                ->where('date_end =?', $data['date_end'])
                ->where('manager_first_name =?', $data['manager_first_name'])
                ->where('manager_middle_name =?', $data['manager_middle_name'])
                ->where('manager_last_name =?', $data['manager_last_name'])
                ->where('manager_division =?', $data['manager_division'])
                ->where('manager_subdivision =?', $data['manager_subdivision'])
                ->where('committee =?', $data['committee']);
        $result = $this->getDbTable()->getAdapter()->fetchRow($sql);
        return $result['id'];
    }

}
