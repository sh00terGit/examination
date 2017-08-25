<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_Criterion {
    
    public $id,
            $value,
            $measure,
            $division;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

      public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }
    
        public function getMeasure() {
        return $this->measure;
    }

    public function setMeasure($measure) {
        $this->measure = $measure;
        return $this;
    }
    
    public function getDivision() {
        return $this->division;
    }

    public function setDivision($managerId) {
        $divisionMapper = new Application_Model_DivisionMapper();
        $this->division = $divisionMapper->find($managerId);
        return $this;
    }
    
    
    public function getValueMeasure(){
        return $this->value . ' ' . $this->measure;
    }

}
