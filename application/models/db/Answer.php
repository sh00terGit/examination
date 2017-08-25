<?php

class Application_Model_Db_Answer extends Zend_Db_Table_Abstract {

    protected $_name = 'answers';
    protected $_primary = array('id');
    protected $_referenceMap = array(
        'Question' => array(
            'columns' => array('question_id'),
            'refTableClass' => 'Application_Model_Db_Question',
            'refColumns' => array('id')
       )
         );
   


}
