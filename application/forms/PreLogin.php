<?php

/**
 *  Форма входа на экзамен 
 */
class Application_Form_PreLogin extends Application_Form_Ru
{
    public function init()
    {
        // указываем имя формы
        $this->setName('prelogin');
        
        $division = new CustomElement_Select('division_id', 'parent');
        $division->setLabel('Отделение');
        
        
        
        
        $exam = new CustomElement_Select('exam_id','child');
        $exam->setLabel('Экзамен')->setAttrib('disabled', 'true');
        
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Войти в систему')->setOptions(array('class' => 'btn  btn-primary '));
        
        // добавляем элементы в форму
        $this->addElements(array($division, $exam, $submit));
        // указываем метод передачи данных
        $this->setMethod('post');
        
        
         
    }
}

