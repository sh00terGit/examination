<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_PartService {

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
        self::$mapper = new Application_Model_PartMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }

    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchParts($divisionId, $roleId);
    }

    public function find($id) {
        return self::$mapper->find($id);
    }

    public function fetchPartsByChapter($chapterId) {
        return self::$mapper->fetchPartsByChapter($chapterId);
    }

    public function delete($id, $archive = false, $where = null) {
        if ($where === null) {
            return self::$mapper->deleteById($id, $archive);
        } else {
            return self::$mapper->deleteByChapterId($id, $archive);
        }
    }

    public function save($postArray) {
        if ($postArray['hiddenid']) {
            self::$mapper->update($postArray);
        } else {
//            if (  ($res = self::$mapper->findAuto($postArray['chapter_id']) ) == 1) {
//                $postArray['hiddenid'] = $res->id;
//                self::$mapper->update($postArray);
//                
//            } else {
                self::$mapper->insert($postArray);
            //}
        }
    }

}
