<?php
/**
 * Работа с пользователем ( кроме гостя и редактора )
 */
class UserController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_UserService::getInstance();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_User($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $form = $this->formPosts($form);
        $form = $this->formSubDivisions($form);
        if($this->userIdentity->role_id ==1) {
            $form = $this->formDivisions($form);
        }
        $this->view->form = $form;
    }

    /**
     * Просмотр ( с возможностью удаления, редактирования)
     * 
     * @param array $selectFields - default-все! - поля для выборки для просмотра
     * @param boolean  $flagArchive   default=FALSE - поля для выборки для просмотра     
     * @return view
     */
    public function loadAction() {   
        $users = $this->service->fetchByManager($this->userIdentity);
        $this->view->roleId = $this->userIdentity->role_id;
        $this->view->users = $users;
    }

    
    /**
     * Вывод экзаменаторов всех отделений ( работа админом)
     */
    public function loadexsAction() {        
        $users = $this->service->fetchAll($this->userIdentity);
        $this->view->roleId = $this->userIdentity->role_id;
        $this->view->users = $users;
    }
    /**
     * Редактирование
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_User($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $id = $this->_getParam('id');
        $user = $this->service->find($id);
        
        $form->populate(array(
            'first_name' => $user->getFirst_name(),
            'middle_name' => $user->getMiddle_name(),
            'last_name' => $user->getLast_name(),
            'login' => $user->getLogin(),
            'archive' => $user->getArchive(),
            'hiddenid' => $id,
        ));
        if($this->userIdentity->role_id == 1) {
            $form = $this->formDivisions($form);
            $form->division_id->setValue($user->getDivision()->getId());
        }
        $form = $this->formSubDivisions($form);
        $form = $this->formPosts($form);
        $form->subDivision_id->setValue($user->getSubDivision()->getId());          
        $form->post_id->setValue($user->getPost()->getId());        
        $form->role_id->setValue($user->getRole()->getId());
        $this->view->form = $form;
    }

    /**
     * Api удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'), $this->flagArchive);
        $this->_redirect('/user/load');
    }
    /**
     * Api сохранения
     */
    public function checkformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->save($this->_request->getPost(), $this->userIdentity);
        $this->_redirect('/user/load');
    }
    /**
     * Добавляем должности в форму
     * @param type $form
     * @return type
     */
    public function formPosts($form) {
        $data = Services_PostService::getInstance()->fetchAll($this->userIdentity);
        foreach ($data as $post) {
            $form->post_id->addMultioption($post->getId(), $post->getSname());
        }
        return $form;
    }
    
    
    /**
     *Добавляем отделения в форму
     */
    public function formDivisions($form) {
        $data = Services_DivisionService::getInstance()->fetchAll($this->userIdentity);
        foreach ($data as $division) {
            $form->division_id->addMultioption($division->getId(), $division->getSname());
        }
        return $form;
    }
    
    /**
     * Добавляем подразделения в форму
     * @param type $form
     * @return type
     */
    public function formSubDivisions($form) {
        $data = Services_SubDivisionService::getInstance()->fetchByManager($this->userIdentity);
        foreach ($data as $subDivision) {
            $form->subDivision_id->addMultioption($subDivision->getId(), $subDivision->getSname());
        }
        return $form;
    }

}
