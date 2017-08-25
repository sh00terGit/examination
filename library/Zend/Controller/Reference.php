<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

abstract class Zend_Controller_Reference extends Zend_Controller_Action {

    private $userIdentity, $flagArchive;

    /**
     * обрезает "контроллер" с названия класса  __class__
     *  return strtolower(substr(__CLASS__, 0, -10));
     * @return  $classname 
     */
    abstract protected function classname();

    /**
     * Возвращает субклассы с контроллера в абстрактный
     * @return $this->subClasses
     */
    abstract protected function subClasses();

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $this->userIdentity = Zend_Auth::getInstance()->getIdentity();
        if ($this->userIdentity->role_id == 1)
            $this->flagArchive = FALSE;
        else {
            $this->flagArchive = TRUE;
        }
    }

    /**
     * перенаправляет сразу на экшн add
     * 
     * @param 
     * @return redirector('add')
     */
    public function indexAction() {

        $this->_helper->redirector('load');
    }

    /**
     * Добавление в базу сущности
     * 
     * @return view
     */
    public function addAction() {

        $form = $this->getForm($this->classname());

        if ($this->subClasses() != NULL) {
            $form = $this->addSubMultiOptionsToForm($this->subClasses(), $form, $this->userIdentity->division_id);
        }

        $this->view->form = $form;
    }

    public function loadAction() {

        $generalClassMapper = $this->getMapper($this->classname());

        if ($this->userIdentity->role_id == 1) {
            $instances = $generalClassMapper->fetchInstances($this->userIdentity->division_id, TRUE);
        } else {
            $instances = $generalClassMapper->fetchInstances($this->userIdentity->division_id, FALSE);
        }

        $this->view->roleId = $this->userIdentity->role_id;
        $this->view->instances = $instances;
    }

    public function editAction() {

        $id = $this->_getParam('id');
        $generalClassMapper = $this->getMapper($this->classname());
        $instance = $generalClassMapper->find($id);
        $form = $this->getForm($this->classname());
        $form->hiddenid->setValue($id);
        foreach ($instance->fields as $num => $field) {
            if (isset($form->$field)) {
                $getField = "get" . $field;
                $form->$field->setValue($instance->$getField());
            }
        }

        if ($this->subClasses() != NULL) {

            $form = $this->addSubMultiOptionsToForm($this->subClasses(), $form, $this->userIdentity->division_id);

            foreach ($this->subClasses() as $subClass => $options) {
                $subClassId = $subClass . "_id";
                $getSub = "get" . $subClass;
                $form->$subClassId->setValue($instance->$getSub()->getId());
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->_getParam('id');
        $table_general = $this->getTable($this->classname());
        $whereId = array("id = ?" => $id);
        //запись в архив!!!!
        if ($this->flagArchive == FALSE) {
            $table_general->delete($whereId);
        } else if ($this->flagArchive == TRUE) {
            $cols = array_flip($table_general->info('cols'));

            if (isset($cols['archive']))
                $table_general->update(array('archive' => '1'), $whereId);
            else {
                 $table_general->delete($whereId);
            }

           $this->_redirect("/".$this->classname()."/load");
        }
    }
    
        public function checkformAction() {
        if ($_POST['hiddenid']) {
            $this->instanceUpdate($_POST);
        } else {
            $this->instanceCreate($_POST);
        }
        $this->_redirect('/chapter/load');
    }
    
    
        public function instanceUpdate($PostArray) {
            
        $table_general = $this->getTable($this->classname());
        foreach ($instance->fields as $num => $field) {
            if (isset($form->$field)) {
                $getField = "get" . $field;
                $form->$field->setValue($instance->$getField());
            }
        }
       
          $data = array(
            'fname' => $PostArray['fname'],
            'sname' => $PostArray['sname'],
            'comment' => $PostArray['comment'],
            'division_id' => $this->userIdentity->division_id
        );
          if($this->userIdentity->role_id == 1){
              $data['archive'] = $PostArray['archive'];
          }
          $where = array('id = ?' => $PostArray['hiddenid']);
         $table_chapter->update($data, $where );
    }

    public function instanceCreate($PostArray) {
        $table_chapter = new Application_Model_Db_Chapter();
        $data = array(
            'fname' => $PostArray['fname'],
            'sname' => $PostArray['sname'],
            'comment' => $PostArray['comment'],
            'division_id' => $this->userIdentity->division_id
        );
        $table_chapter->insert($data);
    }
    

    /**
     * получаем название формы 
     * 
     * @param string $classname - имя контроллера 
     * @return new string $formName
     */
    private function getForm($classname) {
        $formName = "Application_Form_$classname";
        return new $formName($this->flagArchive);
    }

    private function getMapper($classname) {
        $mapper = "Application_Model_$classname" . "Mapper";
        return new $mapper;
    }

    private function getTable($classname) {
        $table = "Application_Model_Db_$classname";
        return new $table;
    }

    protected function addSubMultiOptionsToForm($subClasses, $form, $divisionId) {

        foreach ($subClasses as $subClass => $options) {
            $subClassMapper = $this->getMapper($subClass);
            $data = $subClassMapper->fetchInstances($divisionId, FALSE);
            $subClassId = $subClass . "_id";
            foreach ($data as $instance) {
                $instanceOption = "get" . $options[0];
                $instanceValue = "get" . $options[1];
                $form->$subClassId->addMultioption($instance->$instanceOption(), $instance->$instanceValue());
            }
        }
        return $form;
    }
    
    
    
    
    
    

}
