<?php

class Application_Model_Db_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'users';
    protected $_primary = array('id');
    protected $_referenceMap = array(
        'Division' => array(
            'columns' => 'division_id',
            'refTableClass' => 'Application_Model_Db_Divisions',
            'refColumns' => 'id',
        ),
        'Post' => array(
            'columns' => array('post_id'),
            'refTableClass' => 'Application_Model_Db_Posts',
            'refColumns' => array('id')
        ), 
        'Role' => array(
            'columns' => array('role_id'),
            'refTableClass' => 'Application_Model_Db_Role',
            'refColumns' => array('id')));
     

}
