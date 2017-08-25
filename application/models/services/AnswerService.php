<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Services_AnswerService {

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
        self::$mapper = new Application_Model_AnswerMapper();
        return self::$_instance; // возвращаем экземпляр данного класса
    }

    public function fetchAll($question) {
        return self::$mapper->fetchAnswers($question);
    }

    public function find($id) {
        return self::$mapper->find($id);
    }

    public function delete($id, $archive = false, $where) {
        if ($where === null) {
            return self::$mapper->deleteById($id, $archive);
        } else {
            return self::$mapper->deleteByQuestionId($id, $archive);
        }
    }

    public function save($postArray) {
        if ($postArray['hiddenid']) {
            self::$mapper->update($postArray);
        } else {
            self::$mapper->insert($postArray);
        }
    }

    public function getCountPositive($idquestion) {
        return self::$mapper->getCountPositive($idquestion);
    }

}
