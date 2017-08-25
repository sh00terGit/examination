<?php

/*
 * Форма подразделений
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_SubDivision extends Application_Form_Ru {

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
//        if ($this->roleId == '1') {
            $division = new CustomElement_Select('division_id',$dataType = 'parent');
            $division->setLabel('Отделение');
//        } else {
//            $division = new Zend_Form_Element_Hidden('division_id');
//            $division->setValue($this->divisionId);
//        }
        // указываем имя формы
        $this->setName('form_subdivision');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        $type = new CustomElement_Select('owner_id', $dataType = 'child');
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_division');
        $fname = new CustomElement_Text('fname', TRUE);
        $sname = new CustomElement_Text('sname', TRUE);
        $comment = new CustomElement_Textarea('comment');

        $type->setLabel('Тип предприятия');
        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование подразделения');
        $sname->setLabel('Короткое наименование подразделения');
        $comment->setLabel('Комментарий');


        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $division,
            $type,
            $fname,
            $sname,
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
