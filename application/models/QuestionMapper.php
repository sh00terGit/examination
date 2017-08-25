<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_QuestionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Question');
    }

    public function find($id) {

        $question = new Application_Model_Question();
        $row = $this->getDbTable()->fetchRow("id =$id");
        $question->setId($row->id)
                ->setText($row->text)
                ->setPart($row->part_id)
                ->setChapter($row->chapter_id)
                ->setArchive($row->archive);

        return $question;
    }

    public function fetchQuestions($divisionId, $role_id) {
        if ($role_id == 1) {
            $select = $this->getDbTable()->getAdapter()
                    ->select()
                    ->from(array('q' => 'questions'))
                    ->joinLeft(array('c' => 'chapters'), 'q.chapter_id = c.id', array('chapterfname' => 'fname'))
                    ->joinLeft(array('p' => 'posts'), 'q.part_id = p.id', array('partfname' => 'fname'));
        } else {
            $select = $this->getDbTable()->getAdapter()
            ->select()
            ->from(array('q' => 'questions'))
            ->joinLeft(array('c' => 'chapters'),
            'q.chapter_id = c.id', array('chapterfname' => 'fname'))
            ->joinLeft(array('p' => 'posts'),
            'q.part_id = p.id', array('partfname' => 'fname' ))
            ->where('c.division_id = ? and q.archive = 0', $divisionId)
            ->order('q.chapter_id')
            ->order('q.part_id');
        }
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));

        $questions = array();
        foreach ($result as $row => $value) {
            $question = new Application_Model_Question();
            $question->setId($value['id'])
                    ->setChapter($value['chapter_id'])
                    ->setPart($value['part_id'])
                    ->setText($value['text'])
                    ->setImage($value['image'])
                    ->setArchive($value['archive']);

            $questions [] = $question;
        }
        return $questions;
    }
    
    public function fetchByExam($examId) {
         $select = $this->getDbTable()->getAdapter()
            ->select()
            ->from(array('e' => 'exam'),null)
            ->joinLeft(array('es' => 'exam_section'),
            'es.exam_id = e.id',null)
            ->joinLeft(array('eq' => 'exam_quest'),
            'eq.exam_section_id = es.id', null)
           ->joinLeft(array('q' => 'questions'),
            'eq.question_id = q.id',array('count' => 'count(q.id)'))
            ->where('e.id = ?', $examId);
         $result = $this->getDbTable()->getAdapter()->fetchRow($select);
         return $result['count'];
    }

    

    public function fetchByChapter($chapterId,$divisionId, $role_id) {
                if ($role_id == 1) {
            $select = $this->getDbTable()->getAdapter()
                    ->select()
                    ->from(array('q' => 'questions'))
                    ->joinLeft(array('c' => 'chapters'), 'q.chapter_id = c.id', array('chapterfname' => 'fname'))
                    ->joinLeft(array('p' => 'parts'), 'q.part_id = p.id', array('partfname' => 'fname'))
                    ->where('c.archive != 1');
        } else {
            $select = $this->getDbTable()->getAdapter()
            ->select()
            ->from(array('q' => 'questions'))
            ->joinLeft(array('c' => 'chapters'),
            'q.chapter_id = c.id', array('chapterfname' => 'fname'))
            ->joinLeft(array('p' => 'parts'),
            'q.part_id = p.id', array('partfname' => 'fname' ))
            ->where('c.division_id = ? and q.archive = 0', $divisionId)
            ->where('q.chapter_id = ?', $chapterId)
            ->where('c.archive != 1')
            ->order('q.chapter_id')
            ->order('q.part_id');
        }
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
        $questions = array();
        foreach ($result as $row => $value) {
            $question = new Application_Model_Question();
            $question->setId($value['id'])
                    ->setChapter($value['chapter_id'])
                    ->setPart($value['part_id'])
                    ->setText($value['text'])
                    ->setImage($value['image'])
                    ->setArchive($value['archive']);

            $questions [] = $question;
        }
        return $questions;
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
            'text' => $postArray['text'],
            'chapter_id' => $postArray['chapter_id'],
            'part_id' => $postArray['part_id'],
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'text' => $postArray['text'],
            'chapter_id' => $postArray['chapter_id'],
            'part_id' => $postArray['part_id'],          
            'archive' => $postArray['archive']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }
    

}
