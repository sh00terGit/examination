<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_InfoExam {

    public $userNegativeAns, $userPositiveAns, $examPositiveAns , $examNegativeAns ;
    public $question ;


    public function __construct() {
        $this->question = null;
        $this->userNegativeAns = null;
        $this->userPositiveAns = null;
        $this->examPositiveAns = null;
        $this->examNegativeAns = null;
        
    }

        /**
     * Читает вопросы .... 
     * 
     * @param ид квешн
     * @return зис
     */
        public function setQuestion($question) {
       $this->question = $question;
       
       
       return $this;
    }

    public function getQuestion() {
        return $this->question;
    }


    public function getUserPositiveAns() {
        return $this->userPositiveAns;
    }

    public function addUserPositiveAns($ans) {
        $this->userPositiveAns[] = $ans;
        return $this;
    }

    public function getUserNegativeAns() {
        return $this->userNegativeAns;
    }

    public function addUserNegativeAns($answ) {
        $this->userNegativeAns[] = $answ;
        return $this;
    }

    public function getExamPositiveAns() {
        return $this->examPositiveAns;
    }

    public function addExamPositiveAns($ans) {
        $this->examPositiveAns[] = $ans;
        return $this;
    }
    
    
      public function getExamNegativeAns() {
        return $this->examNegativeAns;
    }

    public function addExamNegativeAns($ans) {
        $this->examNegativeAns[] = $ans;
        return $this;
    }

}
