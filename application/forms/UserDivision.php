<?php

/*
 * Форма персональной настройки выбранных подразделений 
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_UserDivision extends Application_Form_Ru {

    protected $subDivisions, $acceptedSubDivisions, $managerId ,$divisionId ;

    /**
     * 
     * @param type $subDivisions подразделения отделения 
     * @param type $manager   экзаменатор
     */
    public function __construct($subDivisions, $manager) {
//        $this->acceptedSubDivisions = $acceptedSubDivisions;
        $this->subDivisions = $subDivisions;
        $this->managerId = $manager->id;
        $this->divisionId = $manager->division_id;
        parent::__construct();
    }

    public function init() {
        $managerId = new Zend_Form_Element_Hidden('manager_id');
        $managerId->setValue($this->managerId);
        $this->addElement($managerId);
        foreach ($this->subDivisions as $subDivision) {

            $subDivisionCheckbox = new CustomElement_CheckBox('subDivision' . $subDivision->getId());
            $subDivisionCheckbox->setLabel($subDivision->getSname());
            if ($subDivision->accepted) {
                $subDivisionCheckbox->setChecked(TRUE);
            } 
            $this->addElement($subDivisionCheckbox);
        }
        $submit = new CustomElement_Submit('submit');
        $submit->setLabel('Принять');
        $this->addElement($submit);
        $this->setMethod('post');
        $this->setAction('/personCompare/checkform');
        $this->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'person-compare/formUserDivision.phtml',
                    'form' => $this,
                    'subDivisions' => $this->subDivisions,
                    'divisionId' =>$this->divisionId,
                ))
        ));
    }

}
