<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Answer {

    public $id,
            $question,
            $postive,
            $image,
            $content,
            $archive;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setQuestion($questionId) {
        $questionMapper = new Application_Model_QuestionMapper();
        $this->question = $questionMapper->find($questionId);
        return $this;
    }

    public function getPositive() {
        return $this->positive;
    }

    public function setPositive($positive) {
        $this->positive = $positive;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
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
