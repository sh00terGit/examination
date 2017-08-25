<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Post {
    
    public $id,
            $fname,
            $sname,
            $division;
            

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

    public function getDivision() {
        return $this->division;
    }

    public function setDivision($divisionId) {
        $divisionMapper = new Application_Model_DivisionMapper();
        $this->division= $divisionMapper->find($divisionId);
        return $this;
    }

}
