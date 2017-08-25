<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Question {
    
    public $id,
            $chapter,
            $part,
            $text,
//            $image,
            $archive,
            $checked;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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

    public function getPart() {
        return $this->part;
    }

    public function setPart($partid) {
        $PartMapper = new Application_Model_PartMapper();
        $this->part = $PartMapper->find($partid);
        return $this;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getArchive() {
        return $this->archive;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
        return $this;
    }
    
    public function setChecked($bool) {
        $this->checked = $bool;
        return $this;
    }
    
    public function getChecked(){
        return $this->checked;
    }

}
