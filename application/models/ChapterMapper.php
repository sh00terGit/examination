<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ChapterMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Chapter');
    }

    public function find($id) {

        $chapter = new Application_Model_Chapter();
        try {
            $result = $this->getDbTable()->find($id);
            $row = $result->current();
        } catch (Exception $exc) {
            echo $exc->getMessage(). '<br> error find chapter';
        }

        

        $chapter->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setDivisionId($row->division_id)
                ->setComment($row->comment)
                ->setArchive($row->archive);
        return $chapter;
    }

    public function fetchChapters($divisionId, $roleId) {
        if ($roleId == 1) {
            $where = null;
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ? and archive = 0', $divisionId);
        }
        
        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();
        
        $result = $this->getDbTable()->fetchAll($where);
        $resultInArray = $result->toArray();
        if (!($resultInArray === $resCache->load("result_chapterForDivision_$divisionId"))) {
            $resCache->save($resultInArray, "result_chapterForDivision_$divisionId", array("$divisionId"));
        $chapters = array();
        foreach ($result as $row) {
            $chapter = new Application_Model_Chapter();
            $chapter->setId($row->id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setDivisionId($row->division_id)
                    ->setArchive($row->archive)
                    ->setComment($row->comment);
            $chapters [] = $chapter;
        }
        $resCache->save($chapters, "chapterForDivision_$divisionId", array("$divisionId"));
        } else {
            $chapters = $resCache->load("chapterForDivision_$divisionId");
        }
        return $chapters;
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
            'division_id' => $postArray['division_id']
        );
       $id =  $this->getDbTable()->insert($data);
       return $id;
    }

    public function update($postArray) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'division_id' => $postArray['division_id'],
            'archive' => $postArray['archive']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
