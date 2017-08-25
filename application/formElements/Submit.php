<?php

/* 
*
 * * Элемет формы submit  для bootstap 3
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */


class CustomElement_Submit extends Zend_Form_Element_Submit {
    
    public function init() {
        parent::init();
        $this->setOptions(array('class' => 'btn  btn-success'));
    }
}
