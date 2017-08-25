<?php

 class Application_Model_Db_Posts extends Zend_Db_Table_Abstract {
     protected $_name = 'posts';
    // protected $_dependentTables = array('Application_Model_db_Divisions');
      protected $_referenceMap = array(
        'Division' => array(
            'columns' => array('division_id'),
            'refTableClass' => 'Application_Model_Db_Divisions',
            'refColumns' => array('id')
        ));
    
 }