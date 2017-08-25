<?php

/*
 * форма билетов
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Section extends Application_Form_Ru {

    private $noAdmin;
    private $refUrl;
    private $examId;

    /**
     * 
     * @param type $refUrl  - реферальская ссылка для редиректа
     * @param type $examId  - ид экзамена
     * @param type $noAdmin  - админ или нет
     * @param type $options
     */
    public function __construct($refUrl,$examId, $noAdmin = false, $options = null) {

        $this->noAdmin = $noAdmin;
        $this->refUrl = $refUrl;
        $this->examId = $examId;
        parent::__construct($options);
    }

    public function init() {
        $this->setName('form_section');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        $refUrl = new Zend_Form_Element_Hidden('refUrl');
        $refUrl->setValue($this->refUrl);
        
        $examId = new Zend_Form_Element_Hidden('examId');
        $examId->setValue($this->examId);
        
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_chapter');
        $fname = new CustomElement_Text('fname',true);
        $sname = new CustomElement_Text('sname');
        $comment = new CustomElement_Textarea('comment');


        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование');
        $sname->setLabel('Короткое наименование');
        $comment->setLabel('Комментарий');

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $refUrl,
            $examId,
            $fname,
            $sname,
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
