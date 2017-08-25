<?php

class Application_Model_Db_Exam extends Zend_Db_Table_Abstract {

    protected $_name = 'exam';
    protected $_referenceMap = array(
        'Division' => array(
            'columns' => array('division_id'),
            'refTableClass' => 'Application_Model_Db_Divisions',
            'refColumns' => array('id')
        ),
        'Manager' => array(
            'columns' => array('manager_id'),
            'refTableClass' => 'Application_Model_Db_Users',
            'refColumns' => array('id'),
        ),
        'Criterion' => array(
            'columns' => array('criterion_id'),
            'refTableClass' => 'Application_Model_Db_Criterion',
            'refColumns' => array('id')
        ));
   


}
