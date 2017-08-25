<?php

/*
 * Форма отделений
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Division extends Application_Form_Ru  {

    public function init() {
        // указываем имя формы
        $this->setName('form_division');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_division');
        $fname = new CustomElement_Text('fname',TRUE);
        $sname = new CustomElement_Text('sname',TRUE);
        $comment = new CustomElement_Textarea('comment');


        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование отделения');
        $sname->setLabel('Короткое наименование отделения');
        $comment->setLabel('Комментарий');


        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $fname,
            $sname,
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }
}


