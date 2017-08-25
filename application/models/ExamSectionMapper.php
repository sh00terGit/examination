<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ExamSectionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ExamSection');
    }

    public function fetchSection(Application_Model_Exam $exam) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('exam_id= ?', $exam->getId());
        $result = $this->getDbTable()->fetchAll($where);
        $sections = array();
        foreach ($result as $row) {
            $examSection = new Application_Model_ExamSection();
            $examSection->setId($row->id)
                    ->setExam($row->exam_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setComment($row->comment);
            $sections[] = $examSection;
        }
        return $sections;
    }

    public function find($id, Application_Model_ExamSection $examSection) {

        $row = $this->getDbTable()->fetchRow("id = $id");
        $examSection->setId($row->id)
                ->setExam($row->exam_id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setComment($row->comment);

        return $examSection;
    }
    
    public function findByExam ($examId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('exam_id= ?', $examId);
        $result = $this->getDbTable()->fetchAll($where);
        $sections = array();
        foreach ($result as $row) {
            $examSection = new Application_Model_ExamSection();
            $examSection->setId($row->id)
                    ->setExam($row->exam_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setComment($row->comment);
            $sections[] = $examSection;
        }
        return $sections;
    }

}
