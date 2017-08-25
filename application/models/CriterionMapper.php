<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_CriterionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Criterion');
    }

    public function fetchCriteries($division_id, $role) {
        if ($role == 1) {
            $where = null;
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id= ?', $division_id);
        }
        $result = $this->getDbTable()->fetchAll($where);
        $criterions = array();
        foreach ($result as $row) {
            $criterion = new Application_Model_Criterion();
            $criterion->setId($row->id)
                    ->setMeasure($row->measure)
                    ->setValue($row->value)
                    ->setDivision($row->division_id);

            $criterions [] = $criterion;
        }
        return $criterions;
    }

    public function find($id) {

        $criterion = new Application_Model_Criterion();
        $row = $this->getDbTable()->fetchRow("id = $id");
        $criterion->setId($row->id)
                ->setMeasure($row->measure)
                ->setValue($row->value)
                ->setDivision($row->division_id);


        return $criterion;
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
//            return $this->getDbTable()->delete($whereId);
            try {
                return $this->getDbTable()->delete($whereId);
            } catch (Exception $exc) {
                throw new Exception('Удаление невозможно');
               
            }
            
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }
    
    
    public function insert($postArray , $divId ) {
        $data = array(
            'value' => $postArray['value'],
            'measure' => $postArray['measure'],
            'division_id' => $postArray['division_id']
        );
        $this->getDbTable()->insert($data);
    }

    public function update($postArray) {
        $data = array(
            'value' => $postArray['value'],
            'measure' => $postArray['measure'],
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

}
