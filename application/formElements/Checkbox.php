<?php

/* 
* Элемет формы checkbox  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */


class CustomElement_CheckBox extends Zend_Form_Element_Checkbox {
    
    public function init() {
        parent::init();
        $this->setDecorators(array(
                        'ViewHelper',
                        array('Label', array('placement' => 'prepend','style' => 'padding-right:30px;')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'form-element')),
                    ));
    }
}
