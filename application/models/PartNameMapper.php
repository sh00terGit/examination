<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_PartNameMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_PartName');
    }

    public function find($id) {

        $partName = new Application_Model_PartName();
        $row = $this->getDbTable()->fetchRow("id = $id");
        // set default
         if($row == null) {
            $row->id = '0';
            $row->multiple = 'Пункты';
            $row->fname = 'Пункт';
            $row->sname = 'Пункт';
            $row->fname_roditelni= 'пункта';
            $row->multiple_roditelni = 'пунктов';
            $row->fname_datelni = 'пункту';
            $row->fname_vinitel = 'пункт';  
            $row->fname_tvoritelni = 'пунктом'; 
            $row->fname_predlojni = 'пункте';
            $row->multiple_datelni = 'пунктам';
            $row->multiple_vinitelni = 'пункты';
            $row->multiple_tvoritelni = 'пунктами';
            $row->multiple_predlojni = 'пунктах';
        }
        $partName->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setMultiple($row->multiple)
                ->setFname_roditelni($row->fname_roditelni)
                ->setMultiple_roditelni($row->multiple_roditelni);
        return $partName;
    }

    public function fetchAll() {

        $select = $this->getDbTable()->getAdapter()
            ->select()
            ->from('part_name', array('id','fname','sname','multiple','fname_roditelni','multiple_roditelni'));
        
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
      
        $partNames = array();
         foreach ($result as $row => $value) {
            $partName = new Application_Model_PartName();
            $partName->setId($value['id'])
                ->setFname($value['fname'])
                ->setSname($value['sname'])
                ->setMultiple($value['multiple'])
                ->setFname_roditelni($value['fname_roditelni'])
                ->setMultiple_roditelni($value['multiple_roditelni']);
            $partNames [] = $partName;
        } 
        return $partNames;
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
