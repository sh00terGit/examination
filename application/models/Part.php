<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Part {

    public $id,
            $fname,
            $sname,
            $comment,
            $chapter,
            $archive;            

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

    public function getChapter() {
        return $this->chapter;
    }

    public function setChapter($chapterId) {

        $chapterMapper = new Application_Model_ChapterMapper();
        $this->chapter = $chapterMapper->find($chapterId);

        return $this;
    }
    
        public function getArchive() {
        return $this->archive;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
        return $this;
    }


}
