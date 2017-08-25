<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_PartName {

    public $id,
            $fname,
            $sname,
            $comment,
            $multiple,
            $fname_roditelni,
            $fname_datelni,
            $fname_vinitel,
            $fname_tvoritelni,
            $fname_predlojni,
            $multiple_roditelni,
            $multiple_datelni,
            $multiple_vinitelni,
            $multiple_tvoritelni,
            $multiple_predlojni;

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
    public function getMultiple() {
        return $this->multiple;
    }

    public function getFname_roditelni() {
        return $this->fname_roditelni;
    }

    public function getFname_datelni() {
        return $this->fname_datelni;
    }

    public function getFname_vinitel() {
        return $this->fname_vinitel;
    }

    public function getFname_tvoritelni() {
        return $this->fname_tvoritelni;
    }

    public function getFname_predlojni() {
        return $this->fname_predlojni;
    }

    public function getMultiple_roditelni() {
        return $this->multiple_roditelni;
    }

    public function getMultiple_datelni() {
        return $this->multiple_datelni;
    }

    public function getMultiple_vinitelni() {
        return $this->multiple_vinitelni;
    }

    public function getMultiple_tvoritelni() {
        return $this->multiple_tvoritelni;
    }

    public function getMultiple_predlojni() {
        return $this->multiple_predlojni;
    }

    public function setMultiple($multiple) {
        $this->multiple = $multiple;
        return $this;
    }

    public function setFname_roditelni($fname_roditelni) {
        $this->fname_roditelni = $fname_roditelni;
        return $this;
    }

    public function setFname_datelni($fname_datelni) {
        $this->fname_datelni = $fname_datelni;
        return $this;
    }

    public function setFname_vinitel($fname_vinitel) {
        $this->fname_vinitel = $fname_vinitel;
        return $this;
    }

    public function setFname_tvoritelni($fname_tvoritelni) {
        $this->fname_tvoritelni = $fname_tvoritelni;
        return $this;
    }

    public function setFname_predlojni($fname_predlojni) {
        $this->fname_predlojni = $fname_predlojni;
        return $this;
    }

    public function setMultiple_roditelni($multiple_roditelni) {
        $this->multiple_roditelni = $multiple_roditelni;
        return $this;
    }

    public function setMultiple_datelni($multiple_datelni) {
        $this->multiple_datelni = $multiple_datelni;
        return $this;
    }

    public function setMultiple_vinitelni($multiple_vinitelni) {
        $this->multiple_vinitelni = $multiple_vinitelni;
        return $this;
    }

    public function setMultiple_tvoritelni($multiple_tvoritelni) {
        $this->multiple_tvoritelni = $multiple_tvoritelni;
        return $this;
    }

    public function setMultiple_predlojni($multiple_predlojni) {
        $this->multiple_predlojni = $multiple_predlojni;
        return $this;
    }


   

}
