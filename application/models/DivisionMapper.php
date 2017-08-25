<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_DivisionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Divisions');
    }

    public function fetchDivisions() {
        $result = $this->getDbTable()->fetchAll($where);
        $divisions = array();
        foreach ($result as $row ) {
            $division = new Application_Model_Division();
            $division->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
            $divisions[] = $division;
        }
        return $divisions;
    }

   public function find($id) {

        $division = new Application_Model_Division();
        try{
            $result = $this->getDbTable()->find($id);
            $row = $result->current();
        } catch (Exception $e) {
            exit($e->getMessage(). '<br>error find Division');
        }
        $division->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
        
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
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
