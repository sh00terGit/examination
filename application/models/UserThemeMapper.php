<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserThemeMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_UserTheme');
    }

    public function checkAcceptedThemesByManager($managerId , &$themes) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('manager_id= ?', $managerId);
        $result = $this->getDbTable()->fetchAll($where);
        foreach ($result as $row) {
            foreach ($themes as $theme) {
                if($theme->getId() == $row->exam_theme_id) {
                    $theme->accepted = true;
                }
            }
        }
        return $themes;
    }
    
    public function fetchByManager($managerId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('manager_id= ?', $managerId);
        $result = $this->getDbTable()->fetchAll($where);       
        return $result;
    }

    public function deleteByManagerId($managerId) {
            $whereThemeID = array("manager_id = ?" => $managerId);
            $this->getDbTable()->delete($whereThemeID);
        }
        
        public function insert($data) {
            $this->getDbTable()->insert($data);
        }
    
}