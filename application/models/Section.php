<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Section {

    public $questions = array();
    public $examId, $fname, $sname, $comment;

    public function getQuestions() {
        return $this->questions;
    }

    public function addQuestion($questionId) {
        $questionMapper = new Application_Model_QuestionMapper();
        $this->questions [$questionId] = $questionMapper->find($questionId);
        return $this;
    }

    public function removeQuestion($questionId) {
        unset($this->questions[$questionId]);
        return $this;
    }

    public function getExamId() {
        return $this->examId;
    }

    public function setExamId($examId) {
        $this->examId = $examId;
        return $this;
    }

    public function getFname() {
        return $this->fname;
    }

    public function setFname($fname) {
        $this->fname = $fname;
        return $this;
    }

    public function getSname() {
        return $this->sname;
    }

    public function setSname($sname) {
        $this->sname = $sname;
        return $this;
    }
    
    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

}
