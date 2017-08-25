<?php

/*
 * форма общей регистрации
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_UserRegistrationCommon extends Application_Form_Ru {

    public function init() {
        // указываем имя формы
        $this->setName('form_userRegistration');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-default')->setLabel('Войти');
        $submit->setDecorators(array(
                        'ViewHelper',
                        array('HtmlTag', array('tag' => 'div' ,'class' => 'form-group')),
            ));
        $userName = new CustomElement_Select('user_id','parent');   
        $userName->setDecorators(array(
                        'ViewHelper',
                        array('HtmlTag', array('tag' => 'div' ,'class' => 'form-group')),
            ));
        $password = new Zend_Form_Element_Password('passw');
        $password->setRequired(true)
            ->setOptions(array('class' => 'form-control'))
            ->addFilter('StripTags')
            ->setAttrib('placeholder', 'Пароль')
            ->addFilter('StringTrim')
            ->setDecorators(array(
                        'ViewHelper',
                        array('HtmlTag', array('tag' => 'div' ,'class' => 'form-group')),
            ));
            $this->setOptions(array('class' => 'form-inline',  'role'=>'form'));

        // добавляем элементы в форму
        $this->addElements(array(
            $userName,
            $password,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
//        $this->setAction('/checkpart');
    }

}
