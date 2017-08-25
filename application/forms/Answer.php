<?php

class Application_Form_Answer extends Application_Form_Ru {

    private $noAdmin;

    public function __construct($noAdmin = false, $options = null) {

        $this->noAdmin = $noAdmin;
        parent::__construct($options);
    }

    public function init() {


        if ($this->noAdmin == FALSE) {
            $archive = new CustomElement_Select('archive');

            $archive->addMultiOptions(array(
                '1' => 'архивный',
                '0' => 'не архивный'
            ))->setLabel('Архивный');
            $this->addElement($archive);
        }
        $this->setName('registration');

        // сообщение о незаполненном поле
        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $content = new CustomElement_Textarea('content',true);
        $submit = new CustomElement_Submit('form_answer');
        $question_id = new Zend_Form_Element_Hidden('question_id');
        $positive = new CustomElement_Radio('positive',true);

        $positive->addMultiOptions(array(
            '1'  => 'правильный',
            '0'  => 'не правильный'
        ));
        $submit->setLabel('Зарегистрировать');
        $content->setLabel('Текст ответа');
        
        $positive->setLabel('Оценка ответа');
//        $image->setLabel('Картинка');


        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $question_id,
            $content,
            $positive,
            $submit));
        
        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
