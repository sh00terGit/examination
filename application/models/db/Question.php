<?php

class Application_Model_Db_Question extends Zend_Db_Table_Abstract {

    protected $_name = 'questions';
    protected $_primary = array('id');
//    protected $_referenceMap = array(
//        'Chapter' => array(
//            'columns' => array('chapter_id'),
//            'refTableClass' => 'Application_Model_Db_Chapters',
//            'refColumns' => array('id')
//        ),
//        'Parts' => array(
//            'columns' => array('part_id'),
//            'refTableClass' => 'Application_Model_Db_Parts',
//            'refColumns' => array('id'),
//        )
//         );
   


}
