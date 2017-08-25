<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_UserSubDivisionService {

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
        self::$mapper = new Application_Model_UserDivisionMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }

//    public function fetchAll($userIdenity) {
//        $divisionId = $userIdenity->division_id;
//        $roleId = $userIdenity->role_id;
//        return self::$mapper->fetchUsers($divisionId, $roleId);
//    }

    public function fetchByManagerInArray($userIdenity) {
        $subDivisions = self::$mapper->fetchByManager($userIdenity->id);
        foreach ($subDivisions as $subDivision) {
            $subDivisionId [] = $subDivision->subdivision_id;
        }
            return $subDivisionId;
        } 
    
        public function checkAccepted($managerId , $subDivisions) {
            return self::$mapper->checkAcceptedSubDivisionsByManager($managerId, $subDivisions);;
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
