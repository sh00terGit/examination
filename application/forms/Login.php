<?php

/**
 * Форма входа в систему
 */
class Application_Form_Login extends Application_Form_Ru
{
    public function init()
    {
        // указываем имя формы
        $this->setName('login');
        
        // создаём текстовый элемент
        $username = new CustomElement_Text('login');
        
        // задаём ему label и отмечаем как обязательное поле;
        // также добавляем фильтры и валидатор с переводом
        $username->setLabel('Логин:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->setOptions(array('class' => 'form-control'))
            ->addFilter('StringTrim')
            ->setDecorators(array(
                        'ViewHelper',
                        array('Label', array('tag' => 'span', 'placement' => 'prepend')),
                        array('HtmlTag', array('tag' => 'div' ,'class' => 'control-group')),
            ));
        
        // создаём элемент формы для пароля
        $password = new Zend_Form_Element_Password('passw');
        
        // задаём ему label и отмечаем как обязательное поле;
        // также добавляем фильтры и валидатор с переводом
        $password->setLabel('Пароль:')
            ->setRequired(true)
            ->setOptions(array('class' => 'form-control'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators(array(
                        'ViewHelper',
                        array('Label', array('tag' => 'span', 'placement' => 'prepend')),
                        array('HtmlTag', array('tag' => 'div' ,'class' => 'control-group')),
            ));
        
        // создаём кнопку submit
        $submit = new Zend_Form_Element_Submit('loginka');
        $submit->setLabel('Войти в систему')->setOptions(array('class' => 'btn  btn-primary '));
        
        // добавляем элементы в форму
        $this->addElements(array($username, $password, $submit));
        
        // указываем метод передачи данных
        $this->setMethod('post');
        
        
         
    }
}

