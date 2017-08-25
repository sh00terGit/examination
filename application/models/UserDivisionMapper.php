<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserDivisionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_UserDivision');
    }

    public function checkAcceptedSubDivisionsByManager($managerId , &$subDivisions) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('manager_id= ?', $managerId);
        $result = $this->getDbTable()->fetchAll($where);
        foreach ($result as $row) {
            foreach ($subDivisions as $subDivision) {
                if($subDivision->getId() == $row->subdivision_id) {
                    $subDivision->accepted = true;
                }
            }
        }
        return $subDivisions;
    }
    
    public function fetchByManager($managerId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('manager_id= ?', $managerId);
        $result = $this->getDbTable()->fetchAll($where,'id ASC');
        return $result;
    }

    public function deleteByManagerId($managerId) {
            $whereChapterID = array("manager_id = ?" => $managerId);
            $this->getDbTable()->delete($whereChapterID);
        }
        
        public function insert($data) {
            $this->getDbTable()->insert($data);
        }
    
}