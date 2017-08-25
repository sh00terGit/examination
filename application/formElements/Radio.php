<?php

/*
 * Элемет формы Radiobutton  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class CustomElement_Radio extends Zend_Form_Element_Radio {

    public function init() {
        parent::init();
    }

    public function __construct($options = null, $required = false) {
        if ($required == true) {
            $this->setRequired(TRUE);
            $this->setAttrib('required', 'required');
        }
        parent::__construct($options);
    }

}
