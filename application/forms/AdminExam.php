<?php

class Application_Form_AdminExam extends Application_Form_Ru {

    private $managerId, $divisionId;

    /**
     * 
     * @param userIndentity $manager - создающий экзаменатор
     * @param type $options
     */
    public function __construct($manager, $options = null) {
        $this->managerId = $manager->id;
        $this->divisionId = $manager->division_id;
        parent::__construct($options);
    }

    public function init() {
        // указываем имя формы
        $this->setName('registration');

        // сообщение о незаполненном поле
        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        
        
        $theme = new CustomElement_Select('theme');
        $fname = new CustomElement_Text('fname',true);
        $sname = new CustomElement_Text('sname');
        $typeExam =  new Zend_Form_Element_Checkbox('type');

        $division_id = new Zend_Form_Element_Hidden('division_id');
        $manager_id = new Zend_Form_Element_Hidden('manager_id');
        $manager_id->setValue($this->managerId);
        $division_id->setValue($this->divisionId);
        $comment = new CustomElement_Textarea('comment');
        
        $submit = new Zend_Form_Element_Submit('form_exam');
        

        
        $chapters = new Zend_Form_Element_Multiselect('chapters');
        $chapters->setRegisterInArrayValidator(false)
                ->setOptions(array('class' => 'form-control'));
        
      
        $typeExam->setLabel('Билетная организация вопросов');
        $submit->setLabel('Зарегистрировать');
        $fname->setLabel('Полное наименование экзамена');
        $sname->setLabel('Короткое наименование экзамена');
        $comment->setLabel('Комментарий');
//        $committee->setLabel('Комиссия');
        $theme->setLabel('Тема экзамена');
        




        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $theme,
            $fname,
            $sname,
            $comment,
//            $committee,
//            $criterion,
//            
//            $timePass,
//            $attempt,
            $chapters,
            $typeExam,
            $division_id,
            $manager_id,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
        $this->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'admin-exam/formAdminExam.phtml',
                    'form' => $this,
                ))
        ));
//        $this->setAction('/adminExam/check');
    }

}
