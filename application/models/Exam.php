<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Exam {
    
    public $id,
            $division,
            $subdivision,
            $chapters,
            $manager,
            $fname,
            $sname,
            $comment,
            $criterion,
            $type,
            $archive,
            $date,
            $timePass,
            $atempt,
            $committee,
            $dateStart,
            $dateEnd,
            $active,
            $theme;
    
    
    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDivision() {
        return $this->division;
    }

    public function setDivision($division) {
        $this->division = Services_DivisionService::getInstance()->find($division);
        return $this;
    }
    
     public function getSubDivision() {
        return $this->subdivision;
    }

    public function setSubDivision($division) {
        $this->subdivision = Services_SubDivisionService::getInstance()->find($division);
        return $this;
    }
    
    

    public function getChapters() {
        return $this->chapters;
    }

    public function addChapter($chapter_id) {
        $this->chapters[] = $chapter_id;
        return $this;
    }

    public function getManager() {
        return $this->manager;
    }

    public function setManager($manager) {
        $userMapper = new Application_Model_UserMapper();
        $this->manager = $userMapper->findObject($manager);
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

   

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getArchive() {
        return $this->archive;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
        return $this;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }
  
    
    public function setScheduleDateStart($date_start) {
        $this->dateStart = $date_start;
        return $this;
    }
    
    public function getScheduleDateStart() {
        return $this->dateStart;
    }

    public function setScheduleDateEnd($date_end) {
        $this->dateEnd = $date_end;
        return $this;
    }
    
    public function getScheduleDateEnd() {
        return $this->dateEnd;
    }
    
    public function getActive() {
        return $this->active;
    }
    
    public function setActive($active_key) {
        $this->active = $active_key;
        return $this;
    }
    
    public function getTheme() {
        return $this->theme;
    }
    
    public function setTheme($themeId) {
        $this->theme = Services_ExamService::getInstance()->findTheme($themeId);
        return $this;
    }
    
    
}
