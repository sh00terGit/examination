<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_AnswerMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Answer');
    }

    public function fetchAnswers(Application_Model_Question $question) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('question_id = ?', $question->getId());
        $result = $this->getDbTable()->fetchAll($where);
        $answers = array();
        foreach ($result as $row ) {            ;
            $answer = new Application_Model_Answer();
            $answer->setId($row->id)
                    ->setContent($row->content)
                    ->setPositive($row->positive)
                    ->setQuestion($row->question_id);
            $answers [] = $answer;
        }
        return $answers;
    }
    
    public function getCountPositive($idquestion) {
        $query = "select count(*) from answers where question_id = $idquestion and positive = 1";
        $count = $this->getDbTable()->getAdapter()->fetchOne($query);
        return $count;
    }
    
    

   public function find($id) {

        $answer = new Application_Model_Answer();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $answer->setId($row->id)
                ->setContent($row->content)
//                ->setImage($row->image)
                ->setPositive($row->positive)
                ->setQuestion($row->question_id)
                ->setArchive($row->archive);
        
        return $answer;
    }
    
    
    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
        public function deleteByQuestionId($id, $archive = false) {
        $whereId = array("question_id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    

    public function insert($postArray, $divId) {
        $data = array(
            'content' => $postArray['content'],
            'question_id' => $postArray['question_id'],
            'positive' => $postArray['positive'],
//            'image' => $postArray['image'],
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray, $divId) {
        $data = array(
            'content' => $postArray['content'],
            'question_id' => $postArray['question_id'],
            'positive' => $postArray['positive'],
//            'image' => $postArray['image'],
            'archive' => $postArray['archive']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }




}
