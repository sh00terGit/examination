<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_UserMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Users');
    }


    public function find($id, Application_Model_User $user) {

        $table_posts = new Application_Model_Db_Posts();
        $table_divisions = new Application_Model_Db_Divisions();
        $table_role = new Application_Model_Db_Role();


        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            echo 'Такого юзера не существует';
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                ->setArchive($row->archive)
                ->setPost($row->findParentRow($table_posts)->fname)
                ->setDivision($row->findParentRow($table_divisions)->sname)
                ->setSubDivision($row->subdivision_id)
                ->setRole($row->findParentRow($table_role)->fname)
                ->setFirst_name($row->first_name)
                ->setLast_name($row->last_name)
                ->setPassw($row->passw)
                ->setLogin($row->login)
                ->setMiddle_name($row->middle_name);
    }

    public function findObject($id) {

        $user = new Application_Model_User();
        $result = $this->getDbTable()->find($id);
        $row = $result->current();
        $user->setId($row->id)
                ->setArchive($row->archive)
                ->setDivision($row->division_id)
                ->setSubDivision($row->subdivision_id)
                ->setFirst_name($row->first_name)
                ->setMiddle_name($row->middle_name)
                ->setLast_name($row->last_name)
                ->setLogin($row->login)
                // ->setPassw($row->passw)
                ->setPost($row->post_id)
                ->setRole($row->role_id);
        return $user;
    }

//    public function getDivisionIdByName(Application_Model_User $user) {
//        $divisionName = $user->getDivision();
//        $table_divisions = new Application_Model_Db_Divisions();
//        $where = $table_divisions->getAdapter()->quoteInto('fname= ?', $user->getDivision());
//        $divisions = $table_divisions->fetchRow($where);
//        return $divisions->id;
//    }

    public function loginExists($login) {
        $query = sprintf('select count(*) from users where login = ?');

        $result = $this->getDbTable()->getAdapter()->fetchOne($query, $login);

        return $result > 0;

        return $result > 0;
    }

    public function fetchUsers($divisionId, $role_id) {
        switch ($role_id) {
            case 1:
                $where = null;
                break;
            case 2 :
                $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ? and archive = 0 and role_id > 2', $divisionId);
                break;

            case 3:
                exit('Заблокировано');
            case 4 :
                $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ? and archive = 0 and role_id = 3', $divisionId);

                break;

            default:
                break;
        }
        
        $result = $this->getDbTable()->fetchAll($where);
        $users = array();
        foreach ($result as $row) {
            $user = new Application_Model_User();
            $user->setId($row->id)
                    ->setArchive($row->archive)
                    ->setDivision($row->division_id)
                    ->setSubDivision($row->subdivision_id)
                    ->setFirst_name($row->first_name)
                    ->setMiddle_name($row->middle_name)
                    ->setLast_name($row->last_name)
                    ->setLogin($row->login)
                    // ->setPassw($row->passw)
                    ->setPost($row->post_id)
                    ->setRole($row->role_id);


            $users [] = $user;
        }
        return $users;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    public function insert($postArray) {
        $data = array(
            'first_name' => $postArray['first_name'],
            'middle_name' => $postArray['middle_name'],
            'last_name' => $postArray['last_name'],
            'login' => $postArray['login'],
            'passw' =>  ($postArray['passw'] != NULL) ? sha1($postArray['passw']) : '',
            'role_id' => $postArray['role_id'],
            'post_id' => $postArray['post_id'],
            'division_id' => $postArray['division_id'],
            'subdivision_id' => $postArray['subDivision_id'],
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'first_name' => $postArray['first_name'],
            'middle_name' => $postArray['middle_name'],
            'last_name' => $postArray['last_name'],
            'login' => $postArray['login'],
            'passw' => ($postArray['passw'] != NULL) ? sha1($postArray['passw']) : '',
            'role_id' => $postArray['role_id'],
            'post_id' => $postArray['post_id'],
            'division_id' => $postArray['division_id'],
            'subdivision_id' => $postArray['subDivision_id'],
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

    public function fetchUsersByManager($subDivisions, $role_id) {
        switch ($role_id) {
            case 1:
                $where = null;
                break;
            case 2 :
                if (!empty($subDivisions)) {
                    $where = $this->getDbTable()->getAdapter()
                            ->quoteInto("archive = 0 and role_id > 2 and subdivision_id IN ($subDivisions)", $subDivisions);
                } 
                break;

            case 3:
                exit('Заблокировано');
            case 4 :
                $where = $this->getDbTable()->getAdapter()->quoteInto('subdivision_id= ? and archive = 0 and role_id = 3', $subDivisions);

                break;

            default:
                break;
        }
        
        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();
        $str = strlen($subDivisions);
        
        $result = $this->getDbTable()->fetchAll($where); 
        $resultInArray = $result->toArray();
        if (!( $resultInArray === $resCache->load("result_fetchUsersByManager_$str"))) {            
            $resCache->save($resultInArray , "result_fetchUsersByManager_$str", array("$str"));
            
        $users = array();
        
        foreach ($result as $row) {
            $user = new Application_Model_User();
            $user->setId($row->id)
                    ->setArchive($row->archive)
                    ->setDivision($row->division_id)
                    ->setSubDivision($row->subdivision_id)
                    ->setFirst_name($row->first_name)
                    ->setMiddle_name($row->middle_name)
                    ->setLast_name($row->last_name)
                    ->setLogin($row->login)
                    // ->setPassw($row->passw)
                    ->setPost($row->post_id)
                    ->setRole($row->role_id);


            $users [] = $user;
        }
        
        
        $resCache->save($users, "fetchUsersByManager_$str", array("$str"));
        } else {
            $users = $resCache->load("fetchUsersByManager_$str");
        }
        return $users;
    }
    
    
    
    public function fetchManagersByDivision($division_id) {
        
        $where = $this->getDbTable()->getAdapter()
                            ->quoteInto("archive = 0 and role_id = 2 and division_id = ?", $division_id);
        
        $result = $this->getDbTable()->fetchAll($where);
        $users = array();
        foreach ($result as $row) {
            $user = new Application_Model_User();
            $user->setId($row->id)
                    ->setArchive($row->archive)
                    ->setDivision($row->division_id)
                    ->setSubDivision($row->subdivision_id)
                    ->setFirst_name($row->first_name)
                    ->setMiddle_name($row->middle_name)
                    ->setLast_name($row->last_name)
                    ->setLogin($row->login)
                    // ->setPassw($row->passw)
                    ->setPost($row->post_id)
                    ->setRole($row->role_id);


            $users [] = $user;
        }
        return $users;
    }
    
    public function fetchUsersBySubdivision($subDivisionId) {
        $where = $this->getDbTable()->getAdapter()
                            ->quoteInto("archive = 0 and role_id = 3 and subdivision_id = ?", $subDivisionId);
                    
        $result = $this->getDbTable()->fetchAll($where);        
        $users = array();
        foreach ($result as $row) {
            $user = new Application_Model_User();
            $user->setId($row->id)
                    ->setArchive($row->archive)
                    ->setDivision($row->division_id)
                    ->setSubDivision($row->subdivision_id)
                    ->setFirst_name($row->first_name)
                    ->setMiddle_name($row->middle_name)
                    ->setLast_name($row->last_name)
                    ->setLogin($row->login)
                    // ->setPassw($row->passw)
                    ->setPost($row->post_id)
                    ->setRole($row->role_id);


            $users [] = $user;
        }
        return $users;
    }

}
