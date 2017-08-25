<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_SubDivisionTypeMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_SubDivisionType');
    }

    public function fetchTypes($division_id = null) {
        if ($division_id != null) {
            $where = array("division_id = ?" => $division_id);
        }
        $result = $this->getDbTable()->fetchAll($where);
        $kinds = array();
        foreach ($result as $row) {
            $currentKind = new Application_Model_SubDivisionType();
            $currentKind->setId($row->id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setDivision($row->division_id);
            $kinds[] = $currentKind;
        }
        return $kinds;
    }

    public function find($id) {
        $currentKind = new Application_Model_SubDivisionType();
        try {
            $result = $this->getDbTable()->find($id);
            $row = $result->current();
        } catch (Exception $exc) {
            exit($exc->getMessage().'<br>error find subdivisionType');
        }
        $currentKind->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setDivision($row->division_id);

        return $currentKind;
    }

//
    public function delete($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

//
    public function insert($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'division_id' => $postArray['division_id']
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'division_id' => $postArray['division_id']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
