<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ExamThemeMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ExamTheme');
    }

    public function fetchAll() {
        
        $result = $this->getDbTable()->fetchAll($where);
        $themes = array();
        foreach ($result as $row ) {
            $theme = new Application_Model_ExamTheme();
            $theme->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManagerSubDivision($row->manager_subdiv_id);
            $themes[] = $theme;
        }
        return $themes;
    }
    
    
    public function fetchThemesById($themesId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto(" id IN ($themesId)", $themesId);
        $result = $this->getDbTable()->fetchAll($where);
        $themes = array();
        foreach ($result as $row) {
            $theme = new Application_Model_ExamTheme();
            $theme->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManagerSubDivision($row->manager_subdiv_id);
            $themes[] = $theme;
        }
        return $themes;
    }
    

   public function findTheme($id) {

        $theme = new Application_Model_ExamTheme();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $theme->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManagerSubDivision($row->manager_subdiv_id);
        
        return $theme;
    }
    
    
     public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
//        public function deleteByDivisionId($id, $archive = false) {
//        $whereId = array("division_id = ?" => $id);
//        if ($archive == FALSE) {
//            $this->getDbTable()->delete($whereId);
//        } else if ($archive == TRUE) {
//            $this->getDbTable()->update(array('archive' => '1'), $whereId);
//        }
//    }
//
    public function insert($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'manager_subdiv_id' => $postArray['managerSubDivision_id']
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'manager_subdiv_id' => $postArray['managerSubDivision_id']
            
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
