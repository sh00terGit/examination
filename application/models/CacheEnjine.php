<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_CacheEnjine {

    //sington
    public $cache;
    protected static $_instance;

    private function __construct() {
    }

    private function __clone() {  
    }

    private function __wakeup() {   
    }

    public static function getInstance() { // получить экземпляр данного класса
        if (self::$_instance === null) { // если экземпляр данного класса  не создан
            self::$_instance = new self;  // создаем экземпляр данного класса
        }
        return self::$_instance; // возвращаем экземпляр данного класса
    }

    public function getCache() {
        $frontendOptions = array(
            'lifetime' => 7200, // время жизни кэша - 2 часа
            'automatic_serialization' => true,
       //     'caching' => false,
        );

        $backendOptions = array(
            'cache_dir' => './tmp/' // директория, в которой размещаются файлы кэша
        );

// получение объекта Zend_Cache_Core
        $this->cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        //включение кэша метаданных таблиц для всех таблиц
        Zend_Db_Table_Abstract::setDefaultMetadataCache($this->cache);

        return $this->cache;
    }

    public function clean($userId) {
        return $this->getCache()->clean(
                        Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("$userId"));
    }

}
