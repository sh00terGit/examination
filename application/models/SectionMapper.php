<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_SectionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ExamSection');
    }

    public function find($id) {

        $bilet = new Application_Model_Section();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $bilet->setExamId($row->exam_id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setComment($row->comment)
                ->setId($row->id);
        return $bilet;
    }

    public function fetchSections($examId, $userIdentity) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('exam_id= ?', $examId);
        $result = $this->getDbTable()->fetchAll($where);

        $questionMapper = new Application_Model_QuestionMapper();
        $questions = $questionMapper->fetchQuestions($userIdentity->division_id, $userIdentity->role_id);

        $table_exam_quest = new Application_Model_Db_ExamQuest();


        $bilets = array();
        foreach ($result as $row) {
            $bilet = new Application_Model_Section();
            $bilet->setExamId($row->exam_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setComment($row->comment)
                    ->setId($row->id);
            $sectionQuestions = $table_exam_quest->fetchAll(array('exam_section_id = ?' => $bilet->getId()));
            foreach ($sectionQuestions as $sectionQuestion) {
                $bilet->addQuestion($sectionQuestion->question_id);
            }
            $bilets[] = $bilet;
        }
        return $bilets;
    }

    public function refreshBilet($biletId, $source, $destination) {
        $table_exam_quest = new Application_Model_Db_ExamQuest();

        $table_exam_quest->delete(array('exam_section_id = ?' => $biletId));
        if ($source) {
            foreach ($source as $question => $val) {
                $data = array('exam_section_id' => $biletId,
                    'question_id' => $val);
                $table_exam_quest->insert($data);
            }
        }
        if ($destination) {
            foreach ($destination as $question => $val) {

                $data = array('exam_section_id' => $biletId,
                    'question_id' => $val);
                $table_exam_quest->insert($data);
            }
        }
        return true;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
    public function deleteByExamId($id, $archive = false) {
        $whereId = array("exam_id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
    
    public function insert($postArray, $divId) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],            
            'exam_id' => $postArray['examId']
            
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray, $divId) {
        $data = array(
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
