<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_UserThemeService {

    protected static $_instance;
    public static $mapper;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

    public static function getInstance() { // получить экземпляр данного класса
        if (self::$_instance === null) { // если экземпляр данного класса  не создан
            self::$_instance = new self;
            // создаем экземпляр данного класса
        }
        self::$mapper = new Application_Model_UserThemeMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }


    public function fetchByManagerInArray($userIdenity) {
        $themes = self::$mapper->fetchByManager($userIdenity->id);
        foreach ($themes as $theme) {
            $themesId [] = $theme->exam_theme_id;
        }
            return $themesId;
        } 
    
        public function checkAccepted($managerId , $themes) {
            return self::$mapper->checkAcceptedThemesByManager($managerId, $themes);;
        }

//    public function find($id) {
//        return self::$mapper->findObject($id);
//    }

    public function delete($userIndetity) {
        return self::$mapper->deleteByManagerId($userIndetity->id);
    }

    public function save($postArray) {
        if ($postArray['hiddenid']) {
//            self::$mapper->update($postArray);
        } else {
            self::$mapper->insert($postArray);
        }
    }

}
