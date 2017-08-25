<?php

/*
 * Форма для пунктов   
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Part extends Application_Form_Ru {

    private $noAdmin;

    /** 
     * админ может сохранять в архив
     */
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
        // указываем имя формы
        $this->setName('form_part');

        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $submit = new CustomElement_Submit('form_part');
        $fname = new CustomElement_Text('fname',true);
        $sname = new CustomElement_Text('sname');
        $chapter_id = new CustomElement_Select('chapter_id');
        $comment = new CustomElement_Textarea('comment');

        $submit->setLabel('Зарегистрировать');
        
        $fname->setLabel('Полное наименование '.Services_DictionaryService::getDictionaryPartFromSession()->getFname_roditelni());
        
        $sname->setLabel('Короткое наименование '.Services_DictionaryService::getDictionaryPartFromSession()->getFname_roditelni());
        
        $chapter_id->setLabel(Services_DictionaryService::getDictionaryChapterFromSession()->getFname());
        
        $comment->setLabel('Комментарий');

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $chapter_id,
            $fname,
            $sname,            
            $comment,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
//        $this->setAction('/checkpart');
    }

}
