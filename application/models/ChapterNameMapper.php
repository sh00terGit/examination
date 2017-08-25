<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ChapterNameMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ChapterName');
    }

    public function find($id) {

        $chapterName = new Application_Model_ChapterName();
        $row = $this->getDbTable()->fetchRow("id = $id");

        if ($row == null) {
            $row->id = '0';
            $row->multiple = 'Главы';
            $row->fname = 'Глава';
            $row->sname = 'Глава';
            $row->fname_roditelni = 'главы';
            $row->multiple_roditelni = 'глав';
            $row->fname_datelni = 'главе';
            $row->fname_vinitel = 'главу';  
            $row->fname_tvoritelni = 'главой'; 
            $row->fname_predlojni = 'главе';
            $row->multiple_datelni = 'главам';
            $row->multiple_vinitelni = 'главы';
            $row->multiple_tvoritelni = 'главами';
            $row->multiple_predlojni = 'главах';
        }



        $chapterName->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setMultiple($row->multiple)
                ->setFname_roditelni($row->fname_roditelni)
                ->setMultiple_roditelni($row->multiple_roditelni);
        return $chapterName;
    }

    public function fetchAll() {

        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from('chapter_name', array('id', 'fname', 'sname', 'multiple', 'fname_roditelni', 'multiple_roditelni'));

        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));

        $chapterNames = array();
        foreach ($result as $row => $value) {
            $chapterName = new Application_Model_ChapterName();
            $chapterName->setId($value['id'])
                    ->setFname($value['fname'])
                    ->setSname($value['sname'])
                    ->setMultiple($value['multiple'])
                    ->setFname_roditelni($value['fname_roditelni'])
                    ->setMultiple_roditelni($value['multiple_roditelni']);
            $chapterNames [] = $chapterName;
        }
        return $chapterNames;
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
