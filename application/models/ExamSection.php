<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ExamSection {
    
    public $id,
            $exam,
            $fname,
            $sname,
            $comment;
            
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getExam() {
        return $this->exam;
    }

    public function setExam($exam) {
        $this->exam = $exam;
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


}
