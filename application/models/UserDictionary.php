<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserDictionary {
    
    public  $id,
            $user_id,
            $chapter_name,
            $part_name;
    
    
    public function getId() {
        return $this->id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getChapter_name() {
        return $this->chapter_name;
    }

    public function getPart_name() {
        return $this->part_name;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setUser_id($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setChapter_name($chapter_name_id) {
        $mapper = new Application_Model_ChapterNameMapper();
        $this->chapter_name = $mapper->find($chapter_name_id);
        return $this;
    }

    public function setPart_name($part_name_id) {
        $mapper = new Application_Model_PartNameMapper();
        $this->part_name = $mapper->find($part_name_id);
        return $this;
    }




  
   

}
