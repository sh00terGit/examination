<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_CriterionService {

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
        self::$mapper = new Application_Model_CriterionMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }
    
    public function fetchAll($userIdenity) {
        $divisionId = $userIdenity->division_id;
        $roleId = $userIdenity->role_id;
        return self::$mapper->fetchCriteries($divisionId, $roleId);
    }

    
    public function find($id) {
        return self::$mapper->find($id);
    }
    
    public function delete($id , $archive = false) {        
        return self::$mapper->deleteById($id , $archive);
    }
    
    public function save($postArray){
         if ($postArray['hiddenid']) {
             self::$mapper->update($postArray);
         }  else {
             self::$mapper->insert($postArray);
         }
    }

}
