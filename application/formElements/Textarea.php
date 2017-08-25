<?php

/*
 * Элемет формы textarea  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class CustomElement_Textarea extends Zend_Form_Element_Textarea {

    public function init() {
        parent::init();
        $this->setOptions(array('class' => 'form-control'));
        $this->setAttrib('cols', '10')
                ->setAttrib('rows', '3')
                ->setAttrib('style', 'resize: none;');
    }

    public function __construct($options = null, $required = false) {
        if ($required == true) {
            $this->setRequired(TRUE);
            $this->setAttrib('required', 'required');
            $this->setAttrib('placeholder', 'Обязательное поле');
        }
        parent::__construct($options);
    }

}
