<?php

/*
 * Форма добавления пользователей на расписание  
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_AddUsersSchedule extends Application_Form_Ru {

    protected $users, $acceptedUsers, $scheduleId;

    /**
     * Принимаем 
     * @param type $users - пользователи с отмеченных подразделений
     * @param type $acceptedUsers - добавленные пользователи
     * @param type $scheduleId - id расписания
     * @param type $options
     */
    public function __construct($users, $acceptedUsers, $scheduleId, $options = null) {
        $this->acceptedUsers = $acceptedUsers;
        $this->users = $users;
        $this->scheduleId = $scheduleId;
        parent::__construct($options);
    }

    public function init() {
        $scheduleId = new Zend_Form_Element_Hidden('schedule_id');
        $scheduleId->setValue($this->scheduleId);
        $this->addElement($scheduleId);

        if ($this->users != null) {
            foreach ($this->users as $user) {
                // чекбокс с именем = id вопроса
                $userCheckbox = new Zend_Form_Element_Checkbox('user' . $user->getId());
                $userCheckbox->setLabel($user->getFIO());
                $this->addElement($userCheckbox);
            }
        }
        if ($this->acceptedUsers != null) {
            foreach ($this->acceptedUsers as $auser) {
                // чекбокс с именем = id вопроса
                $userCheckbox = new Zend_Form_Element_Checkbox('acceptedUser' . $auser->getUser()->getId());
                $userCheckbox->setLabel($auser->getUser()->getFIO());
                $this->addElement($userCheckbox);
            }
        }

        $submit = new Zend_Form_Element_Submit('submit');
        $this->addElement($submit);


        // рендерим форму в отдельном файле 
        $this->setAction('/schedule/check-add-users');
        $this->setMethod('post');

        $this->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'schedule/formAddUsersSchedule.phtml',
                    'users' => $this->users,
                    'acceptedUsers' => $this->acceptedUsers,
                    'scheduleId' => $this->scheduleId,
                    'form' => $this,
                ))
        ));
    }

}
