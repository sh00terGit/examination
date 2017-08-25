<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserDictionaryMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_UserDictionary');
    }

    public function findByUserId($userId) {


        $where = $this->getDbTable()->getAdapter()->quoteInto('user_id= ?', $userId);
        
        $row = $this->getDbTable()->fetchRow($where);
        $userDictionary = new Application_Model_UserDictionary();

        $userDictionary
                ->setId($row->id)
                ->setChapter_name($row->chapter_name_id)
                ->setPart_name($row->part_name_id)
                ->setUser_id($row->user_id);
       
        return $userDictionary;
    }

    public function deleteByUserId($id) {
        $whereId = array("user_id = ?" => $id);
        $this->getDbTable()->delete($whereId);
    }

    public function insert($postArray) {
        $data = array(
            'user_id' => $postArray['user_id'],
            'part_name_id' => $postArray['level2'],
            'chapter_name_id' => $postArray['level1']
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'part_name_id' => $postArray['level2'],
            'chapter_name_id' => $postArray['level1']
        );
        $where = array('user_id = ?' => $postArray['user_id']);
        $this->getDbTable()->update($data, $where);
    }

}
