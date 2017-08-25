<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_UserService {

    protected static $_instance = null;
    public static $mapper ;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

    public static function getInstance() { // получить экземпляр данного класса
        if (self::$_instance === null) { // если экземпляр данного класса  не создан
            self::$_instance = new self;
            // создаем экземпляр данного классаself::$mapper = new Application_Model_UserMapper();
        }
        self::$mapper = new Application_Model_UserMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    
     public function fetchUsersBySubDivision($subDivisionId) { 
         $mapper = new Application_Model_UserMapper();
         return $mapper->fetchUsersBySubdivision($subDivisionId);
    }

    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchUsers($divisionId, $roleId);
    }

    public function fetchByManager($userIdenity) {
        $roleId = $userIdenity->role_id;
        $subDivisionsId = Services_UserSubDivisionService::getInstance()->fetchByManagerInArray($userIdenity);
        if ($subDivisionsId == null) {
            //вывод сообщения о том что нету записей
            return null;
        } else {
            return self::$mapper->fetchUsersByManager(implode(',', $subDivisionsId), $roleId);
        }
    }

    public function find($id) {
        return self::$mapper->findObject($id);
    }

    public function delete($id, $archive) {
        return self::$mapper->deleteById($id, $archive);
    }

    public function save($postArray, $userIdenity) {
        if ($postArray['hiddenid']) {
            self::$mapper->update($postArray, $userIdenity->division_id);
        } else {
            self::$mapper->insert($postArray, $userIdenity->division_id);
        }
    }
    
    public function fetchManagersByDivision($division_id) {
        return self::$mapper->fetchManagersByDivision($division_id);
    }
    
    
   

}
