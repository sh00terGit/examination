<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_PostMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Posts');
    }

    public function fetchPosts($divisionId,$roleId) {
         if ($roleId == 1 ){
         $where = null;   
         } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ?', $divisionId); 
         }
        $result = $this->getDbTable()->fetchAll($where);
        $posts = array();
        foreach ($result as $row ) {
            $post = new Application_Model_Post();
            $post->setId($row->id)
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
            $posts[] = $post;
        }
        return $posts;
    }

   public function find($id) {

        $post = new Application_Model_Post();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $post->setId($row->id)
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
        
        return $post;
    }
    
    
     public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
        public function deleteByDivisionId($id, $archive = false) {
        $whereId = array("division_id = ?" => $id);
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
            'division_id' => $postArray['division_id'],
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'division_id' => $postArray['division_id'],
            
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
