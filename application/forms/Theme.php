<?php

/*
 * Форма тем
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Theme extends Application_Form_Ru {

    
    private $managerSubDivisionId;
    /**
     * 
     * @param userIndentity $manager - создающий экзаменатор
     * @param type $options
     */
    public function __construct($manager) {
        $this->managerSubDivisionId = $manager->subdivision_id;
        parent::__construct();
    }


    public function init() {


        // указываем имя формы
        $this->setName('form_chapter');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        
        $managerSubDiv = new Zend_Form_Element_Hidden('managerSubDivision_id');
        $managerSubDiv->setValue($this->managerSubDivisionId);
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_chapter');
        $fname = new CustomElement_Text('fname', true);
        $sname = new CustomElement_Text('sname',true);
        $comment = new CustomElement_Textarea('comment');


        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование');
        $sname->setLabel('Короткое наименование')->setOptions(array('class' => 'form-control'));
        $comment->setLabel('Комментарий');

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $managerSubDiv,
            $fname,
            $sname,
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
//        $this->setAction('/checkform');
    }

}
