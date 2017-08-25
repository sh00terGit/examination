<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_SubDivisionService {

    protected static $_instance ;
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
        self::$mapper = new Application_Model_SubDivisionMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    
    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchDivisions($divisionId, $roleId);
    }
    
    
    public function fetchByManager($userIdenity) {
        $roleId = $userIdenity->role_id;
        $subDivisions = Services_UserSubDivisionService::getInstance()->fetchByManagerInArray($userIdenity);
        return self::$mapper->fetchDivisionsByManager(implode(',',$subDivisions), $roleId);
    }
    
        public function fetchAllAccepted($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        $subDivisions = self::$mapper->fetchDivisions($divisionId, $roleId);
        $acceptedSubDivisions = Services_UserSubDivisionService::getInstance()->checkAccepted($userIdenity->id,$subDivisions );
        return $acceptedSubDivisions;
    }
    
    
    public function fetchSubdivisionTypes($division_id) {
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();
        return $subOwnerMapper->fetchTypes($division_id);
    }
    
        public function fetchAllTypes() {
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();
        return $subOwnerMapper->fetchTypes();
    }
    
    public function saveType ($postArray) {
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();
        if ($postArray['hiddenid']) {
             $subOwnerMapper->update($postArray);
         }  else {
             $subOwnerMapper->insert($postArray);
         }
    }
    public function findType($id) {
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();
        return $subOwnerMapper->find($id);
    }
    
    public function find($id) {
        return self::$mapper->find($id);
    }
    
    public function delete($id , $archive = false) { 
        Services_PostService::getInstance()->delete($id,false, true);
        return self::$mapper->deleteById($id , $archive);
    }
    
    public function deleteType($id) { 
        $subOwnerMapper = new Application_Model_SubDivisionTypeMapper();        
        return $subOwnerMapper->delete($id);
    }
    
    
    public function save($postArray){
         if ($postArray['hiddenid']) {
             self::$mapper->update($postArray);
         }  else {
             self::$mapper->insert($postArray);
         }
    }

}
