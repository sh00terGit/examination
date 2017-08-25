<?php

/*
 * Элемет формы select  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class CustomElement_Select extends Zend_Form_Element_Select {

    public function init() {
        parent::init();
        $this->addMultiOption(null, 'Выберите..');
        $this->setRegisterInArrayValidator(FALSE);
        $this->setOptions(array('class' => 'form-control'));
    }
    
    public function __construct($options = null, $dataType = null, $required = true) {
        if ($dataType != null) {
            //для AJAX     
            // data-type  - parent - селект по которому выбираем
            //data-type   - child  - селект который выбираем по parent_id
            $this->setAttrib('data-type', $dataType);
        }
        if ($required === true) {
            $this->setRequired(TRUE);
            $this->setAttrib('required', 'required');
        }

        parent::__construct($options);
    }

}
