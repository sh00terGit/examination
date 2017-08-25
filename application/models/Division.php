<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Division {
    
    public $id,
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
