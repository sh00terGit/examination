<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_SubDivision {
    
    public $id,
            $fname,
            $sname,
            $comment,
            $division,
            $type;

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

    public function getDivision() {
        return $this->division;
    }

    public function setDivision($division_id) {
        $this->division = Services_DivisionService::getInstance()->find($division_id);
        return $this;
    }

    public function getOwner() {
        return $this->type;
    }

    public function setOwner($owner_id) {
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();
        $this->type = $subOwnerMapper->find($owner_id);
        return $this;
    }

}
