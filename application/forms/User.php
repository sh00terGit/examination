<?php
/**
 * Форма пользователя
 */
class Application_Form_User extends Application_Form_Ru {

    private $role_id, $division_id;

    public function __construct($user, $options = null) {

        $this->role_id = $user->role_id;
        $this->division_id = $user->division_id;
        parent::__construct($options);
    }

    public function init() {
        
        
        $division_id = new Zend_Form_Element_Hidden('division_id');
        $division_id->setValue($this->division_id);
        
        switch ($this->role_id) {
            case 1:
            $archive = new CustomElement_Select('archive');

            $archive->addMultiOptions(array(
                '1' => 'архивный',
                '0' => 'не архивный'
            ))->setLabel('Архивный');
            
            $role_id = new CustomElement_Select('role_id');

            $role_id->addMultiOptions(array(
                '1' => 'админ',
                '2' => 'экзаменатор',
                '4' => 'редактор',
                '3' => 'экзаменуемый',
            ))->setLabel('Роль');
            
            
            $division_id = new CustomElement_Select('division_id');
            $division_id->setLabel('Отделение');
            
            $this->addElement($role_id);
            $this->addElement($archive);

                break;
            case 2:
                $role_id = new CustomElement_Select('role_id');
               $role_id->addMultiOptions(array(
                '4' => 'редактор',
                '3' => 'экзаменуемый',
            ))->setLabel('Роль');
            
                $this->addElement($role_id); 
                break;
            case 4:
               $role_id = new Zend_Form_Element_Hidden('role_id');
               $role_id->setValue(3); 
               $this->addElement($role_id);  
                break;
        }
        $this->setName('registration');

        // сообщение о незаполненном поле
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';
        $hiddenid = new Zend_Form_Element_Hidden('hiddenid');
        // создаём текстовый элемент
        $login = new CustomElement_Text('login');
        $password = new Zend_Form_Element_Password('passw');
        $submit = new CustomElement_Submit('form_user');
        $first_name = new CustomElement_Text('first_name',true);
        $last_name = new CustomElement_Text('last_name',true);
        $middle_name = new CustomElement_Text('middle_name',true);
        $subDivision = new CustomElement_Select('subDivision_id');


        //Отделение и должность
        
        $post_id = new CustomElement_Select('post_id');


        $login->setLabel('Логин:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');


        $password->setLabel('Пароль:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setOptions(array('class' => 'form-control'));
               // ->setAttrib('required', 'required')
             //   ->setRequired(true)
              //  ->setAttrib('placeholder', 'Обязательное поле');


        $submit->setLabel('Зарегистрировать');
        $subDivision->setLabel('Предприятие');
        $first_name->setLabel('Имя');
        $middle_name->setLabel('Отчество');
        $last_name->setLabel('Фамилия');
        $post_id->setLabel('Должность');

        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $subDivision,
            $post_id,
            $login,
            $password,
            $last_name,
            $first_name,
            $middle_name,            
            $division_id,
            $submit));
        

        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
