<?php

/* 
* Элемет формы text  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */


class CustomElement_Text extends Zend_Form_Element_Text {
    
    public function init() {
        parent::init();
        $this->setOptions(array('class' => 'form-control'));
        
    }
    public function __construct($options = null,$required = false ) {        
        if($required == true) {
            $this->setRequired(TRUE);
            $this->setAttrib('required', 'required');
            $this->setAttrib('placeholder', 'Обязательное поле');
        }
        parent::__construct($options);
    }
}
