<?php

/** Принимает не обязательный параметр $divisionFieldType (null - hidden , !null - select) 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * @param 
 * @return
 */

class Application_Form_DivisionTypes extends Application_Form_Ru {

    private $divisionFieldType;

    public function __construct($divisions = null) {
        $this->divisionFieldType = $divisions;
        parent::__construct();
    }

    public function init() {
        // указываем имя формы
        $this->setName('form_divisionTypes');
        $submit = new CustomElement_Submit('form_division');
        $redirectUrl = new Zend_Form_Element_Hidden('redirectUrl');
        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');

        $fname = new CustomElement_Text('fname', TRUE);
        $sname = new CustomElement_Text('sname', TRUE);
        $comment = new CustomElement_Textarea('comment');
        if ($this->divisionFieldType == null) {
            $redirectUrl->setValue('/subDivision/add');
            $division = new Zend_Form_Element_Hidden('division_id');
        }
        else {
             $division = new CustomElement_Select('division_id');
             $redirectUrl->setValue('subDivision/load-types');
             $division->setLabel('Отделение');
        }



        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное название типа');
        $sname->setLabel('Краткое название типа');
        $comment->setLabel('Комментарий');


        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $division,
            $redirectUrl,
            $fname,
            $sname,
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
        $this->setAction('/subDivision/checktypeform');
    }

}
