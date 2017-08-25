<?php

class Application_Form_Question extends Application_Form_Ru {

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
        $text = new CustomElement_Textarea('text');
        $submit = new CustomElement_Submit('form_question');
        
        // data-type  - parent - селект по которому выбираем
        //data-type   - child  - селект который выбираем по parent_id
        $chapter_id = new CustomElement_Select('chapter_id' , $dataType = 'parent');
        $part_id = new CustomElement_Select('part_id' , $dataType = 'child');
        
     
        


        $submit->setLabel('Зарегистрировать');
        $text->setLabel('Текст вопроса')->setAttrib('required', 'required');
        
        
        $chapter_id->setLabel(Services_DictionaryService::getDictionaryChapterFromSession()->getFname());
        $part_id->setLabel(Services_DictionaryService::getDictionaryPartFromSession()->getFname());
//        $image->setLabel('Картинка');

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $text,
            $chapter_id,
            $part_id,
//            $image,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
//        $this->setAction('/checkquestion');
    }

}
