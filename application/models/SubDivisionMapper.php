<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_SubDivisionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_SubDivisions');
    }

    public function fetchDivisions($divisionId,$roleId) {
         if ($roleId == 1 ){
            $where = null;   
         } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ?', $divisionId); 
         }
        $result = $this->getDbTable()->fetchAll($where);
        $divisions = array();
        foreach ($result as $row) {
            $division = new Application_Model_SubDivision();
            $division->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setDivision($row->division_id)
                    ->setOwner($row->subdiv_type_id);
            $divisions[] = $division;
        }
        return $divisions;
    }
    
    
        public function fetchDivisionsByManager($subDivisions,$roleId) {
         if ($roleId == 1 ){
            $where = null;   
         } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto(" id IN ($subDivisions)", $subDivisions); 
         } 
        $result = $this->getDbTable()->fetchAll($where);
        $divisions = array();
        foreach ($result as $row) {
            $division = new Application_Model_SubDivision();
            $division->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setDivision($row->division_id)
                    ->setOwner($row->subdiv_type_id);
            $divisions[] = $division;
        }
        return $divisions;
    }
    

    public function find($id ) {

        $division = new Application_Model_SubDivision();
        try{
        $result = $this->getDbTable()->find($id);
        $row = $result->current();
        } catch (Exception $e) {
            exit($e->getMessage(). "<br> subDivision find error <br> id = 0 ");
        }
        $division->setId($row->id)
                ->setComment($row->comment)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setDivision($row->division_id)
                ->setOwner($row->subdiv_type_id);
        
        return $division;
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
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'division_id' => $postArray['division_id'],
            'subdiv_type_id' => $postArray['owner_id']
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'division_id' => $postArray['division_id'],
            'subdiv_type_id' => $postArray['owner_id']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
