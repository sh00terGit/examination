<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_PartMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Part');
    }

    public function find($id) {

        $part = new Application_Model_Part();
        try {
            $result = $this->getDbTable()->find($id);
            $row = $result->current();
        } catch (Exception $exc) {
            echo $exc->getMessage() . '<br> error find Part';
        }
        $part->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setChapter($row->chapter_id)
                ->setComment($row->comment)
                ->setArchive($row->archive);
        return $part;
    }

    public function fetchPartsByChapter($chapterId) {

        $where = $this->getDbTable()->getAdapter()->quoteInto("chapter_id= ? and archive != 1", $chapterId);

        $result = $this->getDbTable()->fetchAll($where);
        $parts = array();
        foreach ($result as $row) {
            $part = new Application_Model_Part();
            $part->setId($row->id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setChapter($row->chapter_id)
                    ->setComment($row->comment)
                    ->setArchive($row->archive);
            $parts [] = $part;
        }
        return $parts;
    }

    public function fetchParts($divisionId, $role_id) {
        if ($role_id == 1) {
            $select = $this->getDbTable()->getAdapter()
                    ->select()
                    ->from(array('p' => 'parts'))
                    ->joinLeft(array('c' => 'chapters'), 'p.chapter_id = c.id', array('chapterfname' => 'fname'))
                    ->where("p.comment <> 'auto' and p.fname <> '' ");
        } else {
            $select = $this->getDbTable()->getAdapter()
                    ->select()
                    ->from(array('p' => 'parts'))
                    ->joinLeft(array('c' => 'chapters'), 'p.chapter_id = c.id', array('chapterfname' => 'fname'))
                    ->where('c.division_id = ? and p.archive = 0', $divisionId)
                    ->where("p.comment <> 'auto' and p.fname <> '' ");
        }
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
        $parts = array();
        foreach ($result as $row => $value) {
            $part = new Application_Model_Part();
            $part->setId($value['id'])
                    ->setFname($value['fname'])
                    ->setSname($value['sname'])
                    ->setChapter($value['chapter_id'])
                    ->setComment($value['comment'])
                    ->setArchive($value['archive']);

            $parts [] = $part;
        }
        return $parts;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    public function deleteByChapterId($id, $archive = false) {
        $whereChapterID = array("chapter_id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereChapterID);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereChapterID);
        }
    }

    public function insert($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'chapter_id' => $postArray['chapter_id'],
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'chapter_id' => $postArray['chapter_id'],
            'archive' => $postArray['archive']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

    public function findAuto($chapterId) {
        $where = array("chapter_id = ? and comment ='auto' and archive ='0' " => $chapterId);
        $result = $this->getDbTable()->fetchRow($where);
        return $result;
    }

}
