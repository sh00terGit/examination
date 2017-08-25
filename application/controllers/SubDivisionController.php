<?php
/**
 * Подразделения (работа админом )
 */
class SubDivisionController extends Zend_Controller_Action {

    private $userIdentity, $flagArchive, $service;

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        $this->service = Services_SubDivisionService::getInstance();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * Добавление типа предприятия
     * 
     * @param  id - ид отделения (hidden)
     * @return $this->checktypeform()
     */
    public function addTypeAction() {

        $id = $this->_getParam('id');
        if ($id != null) {
            $form = new Application_Form_DivisionTypes();
            $divisionSname = Services_DivisionService::getInstance()->find($id)->getSname();
            $this->_helper->layout->setLayout('modal');
            $form->division_id->setValue($id);
            $this->view->division = $divisionSname;
            $this->view->form = $form;
        } else {

            $form = new Application_Form_DivisionTypes('select divisions');
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
            $this->view->form = $form;
            $this->render('add-type-all');
        }
    }

    /**
     * редактирование типа предприятия
     */
    public function editTypeAction() {
        $id = $this->_getParam('id');
        $form = new Application_Form_DivisionTypes('select divisions');
        $data = Services_DivisionService::getInstance()->fetchAll();
        foreach ($data as $division) {
            $form->division_id->addMultioption($division->getId(), $division->getSname());
        }
        $currentType = Services_SubDivisionService::getInstance()->findType($id);
        $form->populate(array(
            'fname' => $currentType->getFname(),
            'sname' => $currentType->getSname(),
            'comment' => $currentType->getComment(),
            'hiddenid' => $id
        ));
        $form->division_id->setValue($currentType->getDivision()->getId());
        $this->view->form = $form;
    }

    /**
     * Сохранение в базу типа предприятия
     * 
     * @param   POST : division_id , fname ,sname , comment
     * @return /subDivision/add
     */
    public function checktypeformAction() {
        if ($this->_request->isPost()) {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $this->service->saveType($this->_request->getPost());
            $this->_redirect($this->_getParam('redirectUrl'));
        }
    }

    /**
     * загрузка типов 
     */
    public function loadTypesAction() {
        $types = $this->service->fetchAllTypes();
        $this->view->types = $types;
    }
    /**
     * Api удаления
     */
    public function deleteTypeAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->deleteType($this->_getParam('id'));
        $this->_redirect('/subDivision/load-types');
    }
    
    
    

    /**
     * Просмотр ( с возможностью удаления, редактирования)
     * 
     * @param array $selectFields - default-все! - поля для выборки для просмотра
     * @param boolean  $flagArchive   default=FALSE - поля для выборки для просмотра     
     * @return view
     */
    public function loadAction() {
        $divisions = $this->service->fetchAll($this->userIdentity);
        $this->view->divisions = $divisions;
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {
        $form = new Application_Form_SubDivision($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }

        $data = $this->service->fetchSubdivisionTypes($this->userIdentity->division_id);
        foreach ($data as $kind) {
            $form->owner_id->addMultioption($kind->getId(), $kind->getFname());
        }
        $form->owner_id->setAttrib('disabled', true);
        if ($this->userIdentity->role_id == '1') {
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
        }
        $this->view->form = $form;
//        $formTypes = new Application_Form_DivisionTypes();
//        $this->view->formTypes = $formTypes;
    }

    /**
     * Редактирование подразделения ( работа админом)
     * @return type
     */
    public function editAction() {
        $form = new Application_Form_SubDivision($this->userIdentity);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                return $this->_forward("checkform");
            }
        }
        $data = $this->service->fetchSubdivisionTypes($this->userIdentity->division_id);
        foreach ($data as $kind) {
            $form->owner_id->addMultioption($kind->getId(), $kind->getFname());
        }
        if ($this->userIdentity->role_id == '1') {
            $data = Services_DivisionService::getInstance()->fetchAll();
            foreach ($data as $division) {
                $form->division_id->addMultioption($division->getId(), $division->getSname());
            }
        }


        $id = $this->_getParam('id');
        $subDivision = $this->service->find($id);
        $form->populate(array(
            'fname' => $subDivision->getFname(),
            'sname' => $subDivision->getSname(),
            'comment' => $subDivision->getComment(),
            'hiddenid' => $id
        ));
        $form->division_id->setValue($subDivision->getDivision()->getId());
        $form->owner_id->setValue($subDivision->getOwner()->getId());

        $this->view->form = $form;
    }
    
/**
     * Api удаления
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->service->delete($this->_getParam('id'));
        $this->_redirect('/subDivision/load');
    }
/**
     * Api сохранения
     */
    public function checkformAction() {
        $this->service->save($this->_request->getPost());
        $this->_redirect('/subDivision/load');
    }
     /**
     * Api Ajax выбор типов подразделений отделения
     */
    public function selectAction() {
        if ($division_id = $this->_request->getParam('parent_id')) {
            $data = $this->service->fetchSubdivisionTypes($division_id);
            $this->getHelper('json')->sendJson($data);
        } else {
            return false;
        }
    }

}
