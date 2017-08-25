<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_RoleMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Role');
    }

    public function fetchRoles() {
        $result = $this->getDbTable()->fetchAll($where);
        $roles = array();
        foreach ($result as $row ) {
            $role = new Application_Model_Role();
            $role->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
            $roles [] = $role;
        }
        return $roles;
    }

   public function find($id) {

        $role = new Application_Model_Role();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $role->setId($row->id)
                    ->setComment($row->comment)
                    ->setFname($row->fname)
                    ->setSname($row->sname);
        
        return $role;
    }

}
