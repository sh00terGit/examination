<?php
/**
 * Форма расписания
 */
class Application_Form_Schedule extends Application_Form_Ru {

    private $managerId, $divisionId;

    /**
     * 
     * @param userIndentity $manager создающий экзаменатор  
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
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';
        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $exam_id = new CustomElement_Select('exam_id');
        $dateStart = new CustomElement_Text('dateStart',TRUE);
        $dateEnd = new CustomElement_Text('dateEnd',TRUE);
        $committee = new CustomElement_Textarea('committee');
        $committee->setLabel('Комиссия')->setAttrib('placeholder','без коммиссии');
        $criterion = new CustomElement_Select('criterion');
        $criterion->setLabel('Критерий сдачи');
        $timePass = new Zend_Form_Element_Text('time_pass');
        $timePass->setLabel('Время на сдачу экзамена (мин)')->setOptions(array('class' => 'form-control'));
        $timePass->setAttrib('placeholder','без ограничений')->setValue(null);
        
        $attempt = new Zend_Form_Element_Text('attempt');
        $attempt->setLabel('Количество попыток сдачи ')->setOptions(array('class' => 'form-control'));
        $attempt->setAttrib('placeholder','без ограничений')->setValue(null);

        $manager_id = new CustomElement_Select('manager_id');
        $manager_id->setValue($this->managerId);        
      
        $subDivision =  new Zend_Form_Element_Multiselect('subDivision');
        $subDivision->setRegisterInArrayValidator(false)
                ->setOptions(array('class' => 'form-control'));
        
        $active = new CustomElement_CheckBox('active');
        $comment = new CustomElement_Textarea('comment');
        $authType = new CustomElement_Radio('authType',true);
        $division  = new CustomElement_CheckBox('division');
                
        $password = new Zend_Form_Element_Password('password');
        
        $password->setLabel('Пароль:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setOptions(array('class' => 'form-control'))
                ->setAttrib('placeholder', 'Введите пароль');
        
        $division->setLabel('Все предприятия');
        
        
        $subDivision->setLabel('Предприятие');
        $authType
                ->addMultiOptions(array('1' => 'общий', 
                                        '2' => 'индивидуальный'))
                ->setLabel('Тип входа :  ');
        
        $manager_id->setLabel('Ответственный');
        $comment->setLabel('Комментарий');
        $active->setLabel('Активация')->setChecked(true);
        $exam_id->setLabel('Экзамен');
        $dateStart->setLabel('Дата начала экзамена');
        $dateEnd->setLabel('Дата окончания экзамена');
        
        $submit = new CustomElement_Submit('form_exam');
        $submit->setLabel('Зарегистрировать экзамен');






        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $exam_id,
            $subDivision,
            $division,
            $dateStart,
            $dateEnd,
            $password,
            $manager_id,
            $active,
            $authType,
            $comment,
            $criterion,
            $attempt,
            $timePass,
            $committee,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
        
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'schedule/form.phtml', 'form' => $this))
                ));
    }

}
