<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Criterion extends Application_Form_Ru {

    
    private $divisionId;
    /**
     * 
     * @param division $division_id - создающее отделение
     * @param type $options
     */
    public function __construct($division_id, $options = null) {
        $this->divisionId = $division_id;
        parent::__construct($options);
    }

    public function init() {


        
        // указываем имя формы
        $this->setName('form_chapter');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_criterion');
        $measure = new CustomElement_Text('measure',true);
        $value = new CustomElement_Text('value',true);
        $division_id = new Zend_Form_Element_Hidden('division_id');
        $division_id->setValue($this->divisionId);

        $submit->setLabel('Зарегистрировать');
        $measure->setLabel('Измерение');
        $value->setLabel('Значение');
       

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $value,
            $measure,
            $division_id,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
