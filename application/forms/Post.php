<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Post extends Application_Form_Ru {

    private $divisionId, $roleId;
    /**
     * 
     * @param userIndentity $manager - создающий экзаменатор
     * @param type $options
     */
    public function __construct($manager) {
        $this->divisionId = $manager->division_id;
        $this->roleId = $manager->role_id;
        parent::__construct();
    }

    public function init() {
        if ($this->roleId == '1') {
            $division_id = new CustomElement_Select('division_id');
            $division_id->setLabel('Отделение');
        } else {
            
            $division_id = new Zend_Form_Element_Hidden('division_id');
            $division_id->setValue($this->divisionId);
        }

        // указываем имя формы
        $this->setName('form_post');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_post');
        $fname = new CustomElement_Text('fname', true);
        $sname = new CustomElement_Text('sname', true);

        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование должности');
        $sname->setLabel('Короткое наименование должности');
//        $division_id->setLabel('Отделение');
        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $division_id,
            $fname,
            $sname,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
