<?php

/**
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * @see СhapterName defaults value define in ChapterNameMapper
 * @see PartName defaults value define in PartNameMapper
 */
class Services_DictionaryService {

    protected static $_instance;
    public static $mapper;

    private function __construct() {
        
    }

    public static function fetchAll() {
        $chapterNamesMapper = new Application_Model_ChapterNameMapper();
        $chapterNames = $chapterNamesMapper->fetchAll();
        $partNamesMapper = new Application_Model_PartNameMapper();
        $partNames = $partNamesMapper->fetchAll();
        $dictionary['chapterName'] = $chapterNames;
        $dictionary ['partName'] = $partNames;
        return $dictionary;
    }

//    public static function findByUserId($id) {
//        $mapper = Application_Model_UserDictionaryMapper();
//        $mapper->
//    }

    public static function save($postArray, $userIdenity) {
        $mapper = new Application_Model_UserDictionaryMapper();
        $postArray['user_id'] = $userIdenity->id;
        //delete 
        $mapper->deleteByUserId($userIdenity->id);
        //insert

        $mapper->insert($postArray);
    }

    public static function getDictionaryFromSession() {
        $sessionDictionary = new Zend_Session_Namespace('dictionary');
        return unserialize($sessionDictionary->dictionary);
    }

    /**
     *@see public function getId() 
     *@see public function getMultiple()  
     *@see public function getFname_roditelni() 
     *@see public function getFname_datelni() 
     *@see public function getFname_vinitel()
     *@see public function getFname_tvoritelni() 
     *@see public function getFname_predlojni() 
     *@see public function getMultiple_roditelni()
     *@see public function getMultiple_datelni() 
     *@see public function getMultiple_vinitelni() 
     *@see public function getMultiple_tvoritelni() 
     *@see public function getMultiple_predlojni() 
     *
     */
    public static function getDictionaryChapterFromSession() {
        $sessionDictionary = new Zend_Session_Namespace('dictionary');
        return unserialize($sessionDictionary->dictionary)->getChapter_name();
    }

    /**
     *@see public function getId() 
     *@see public function getMultiple()  
     *@see public function getFname_roditelni() 
     *@see public function getFname_datelni() 
     *@see public function getFname_vinitel()
     *@see public function getFname_tvoritelni() 
     *@see public function getFname_predlojni() 
     *@see public function getMultiple_roditelni()
     *@see public function getMultiple_datelni() 
     *@see public function getMultiple_vinitelni() 
     *@see public function getMultiple_tvoritelni() 
     *@see public function getMultiple_predlojni() 
     *
     */
    public static function getDictionaryPartFromSession() {
        $sessionDictionary = new Zend_Session_Namespace('dictionary');
        return unserialize($sessionDictionary->dictionary)->getPart_name();
    }

}
