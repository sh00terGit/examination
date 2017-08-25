<?php

/**
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * @method abstract protected classname() - обязательная функция, получение имени объекта с главного контроллера
 * @method abstract protected subClasses() - обязательная функция, получение названий зависимых таблиц и полей для выборки
 * 
 * зависимый объект, таблица и др ( объект считается зависимым если в основном объекте есть ссылка типа *_id   
 * (напр question_id)  
 * 
 */
abstract class Zend_Controller_AEssential extends Zend_Controller_Action {

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

    /**
     * перенаправляет сразу на экшн add
     * 
     * @param 
     * @return redirector('add')
     */
    public function indexAction() {

        $this->_helper->redirector('add');
    }

    /**
     * Добавление в базу сущности
     * 

     * @return view
     */
    public function addAction() {
        // @param string $classname - название сущности с которой работаем прим ( answer,question ..)
        $classname = $this->classname();
        // @param  array $subClasses   - приходит в виде двумерного массива , по умолчанию не существует
        // например  public $subClasses = array('question' => array('id', 'text') );
        $subClasses = $this->subClasses();
        //     Получаем название формы в которой будем работаем передав название контроллера ( Название контроллера должно быть
        //       обрезано до наименования сущности прим( AnswerController => answer) Форма будет выглядеть Form_answer
        $form = $this->getForm($classname);
        //указатель на адаптер базы
        //$db = Application_Model_DBAdapter::getadapter();

        //   Получаем основной объкт с которым работаем через DataObject ( напр DataObject_Answer)
        $generalDbObject = $this->getGeneralDbObject($classname);

        //проверяем на существование зависимых классов переданных в массиве $subClasses 
        //зависимый ( для селектов и прочего ) датаОБъект включающий зависимые классы!(DataObject_Question и др
        // обращаться $subDbObjects[0] = первый  и тд...
        if ($subClasses != NULL) {
            $subDbObjects = $this->getSubDbObject($subClasses, $db);
        }
        //ожидаем ответ
        $request = $this->getRequest();
        //работаем через пост
        if ($request->isPost()) {
          //  if ($form->isValid($request->getPost())) {
                //получаем объект FormProcessor , напр FormProcessor_Answer
                $fp = $this->getFormProcessor($classname, $db);
                //валидуем передаем ответ и объект ( если обновляем), $fp - массив ошибок
                $fp = $fp->process($request, $generalDbObject);
                //выводим ошибки 
                $this->view->fp = $fp;
                //обнуляем форму
                $form->reset();
            //}
        }
        // проверяем существуют ли зависимые классы ( поля которые описаны *_id в бд таблице )
        if ($subClasses != NULL) {
            //$subClasses выглядит двумерным массивом  a[зависимый класс DataObject (папример (string) question)][array(поля для селекта или др) ]
            foreach ($subClasses as $subClass => $subFields) {
                //берем id с зависимых классов как храниться в базе !
                $subClassId = $subClass . "_id";
                //вытягиваем в массив и устанавливаем значения в поля $subName_id   .. .  . 
                $form->$subClassId->setMultiOptions($this->issueSubClass($subDbObjects[$subClass], $subFields));
            }
        }
        //рисуем готовую форму
        $this->view->form = $form;
    }

    /**
     * Просмотр ( с возможностью удаления, редактирования)
     * 
     * @param array $selectFields - default-все(Null)! - поля для выборки для просмотра
     * @param boolean  $flagArchive   default=FALSE - поля для выборки для просмотра     
     * @return view
     */
    public function loadAction(array $selectFields = NULL, $flagArchive = FALSE) {

        //* @param string $classname - название сущности с которой работаем прим ( answer,question ..)
        $classname = $this->classname();
        //  * @param  array $subClasses   - приходит в виде двумерного массива , по умолчанию не существует 
        $subClasses = $this->subClasses();

        // указатель на базу
      

//        //   Получаем основной объкт с которым работаем через DataObject ( напр DataObject_Answer)
        $generalDbObject = $this->getGeneralDbObject($classname, $db);
//
//
//        //загружаем все записи с таблицы 
//        //$selectFields - выбранные поля
//        // $flagArchive - архимные тоже выбирать?
        $generalDbObjects = $generalDbObject->fetchAll();
//        print_r($generalDbObjects);
//        //выкидываем в въюху массив записей 
//        $this->view->$classname = $generalDbObjects;
//
//
//        // ожидаем ответ
//        $request = $this->getRequest();
//        //от поста
//        if ($this->getRequest()->isPost()) {
//            //приходит (сheckid и update|delete ) или form_name (напр form_answer ) от сабмита и hidden id
//            if (isset($_POST['delete'])) {
//                $generalDbObject->load($_POST['checkedId']);
//                // если передали архивный флаг то не удаляем
//                if ($flagArchive) {
//                    $generalDbObject->archive = 1;
//                    $generalDbObject->save();
//                } else {
//                //если пришло удаление , загружаем запись через Data_Object и удаляем ее, редирект для обновления страницы
//                $generalDbObject->delete();
//                }
//                $this->_helper->redirector($request->getActionName(), $request->getControllerName());
//            } else if (isset($_POST['update'])) {
//                //создаем форму класса classname ( напр Form_answer)
//                $form = $this->getForm($classname);
//                //goto к edit
//                $this->_forward('edit');
//            } else if (isset($_POST["form_$classname"])) {
//                //если пришел пост с form_name ( значит мы уже отредактировали страницу и отправляем её на сервер
//               // $form = $this->getForm($classname);
//               // if ($form->isValid($request->getPost())) {
//                    
//                    //получаем объект из hidden поля (id в бд)
//                    $generalDbObject->load($_POST['hiddenid']);
//
//
//                    $fp = $this->getFormProcessor($classname, $db);
//                    // отправляем ответ пользователя и отредактированный объект на валидацию и редактирование , если 
//                    //  не передадим $generalDbObject то создасться новый объект
//                    $fp = $fp->process($request, $generalDbObject);
//                    if($fp ) {
//                        $this->view->fp = $fp;
//                    }
//                    else {
//                    //редирект на просмотр 
//                        $this->_helper->redirector($request->getActionName(), $request->getControllerName());
//                    }
//                    
//                //}
//            }
//        }
    }

    /**
     * Редактирование (приходим из экшн Load)
     * отрисовываем форму с всеми полями ( поля выгружаем с бд)
     * Метод объявлен как final для невозможности редактирования его с наследуемых классов
     * @param var $_POST['checkedId'] редактируемый объект , приходит в виде id (generalDbObject)
     * @return view
     */
    protected final function editAction() {

        /* @var $db указатель для бд */
        $db = Application_Model_DBAdapter::getadapter();

        //* @param string $classname - название сущности с которой работаем прим ( answer,question ..)
        $classname = $this->classname();

        //  * @param  array $subClasses   - приходит в виде двумерного массива , по умолчанию не существует 
        $subClasses = $this->subClasses();


 
        // загружаем форму , основной объект по чекнутому айди в просмотре
        $generalDbObject = $this->getGeneralDbObject($classname, $db);
        $generalDbObject->load($_POST['checkedId']);
        $form = $this->getForm($classname);


        // проверяем есть ли в таблице зависимые поля  напр ( question_id , part_id ) и тд
        // находим зависимые поля 
        if ($subClasses != NULL) {
            $subDbObjects = $this->getSubDbObject($subClasses, $db);

            foreach ($subDbObjects as $subDbObject) {
                //берем объект типа датабайс_объект берем с него имя без префиксов, обрезаем ,уменьшаем
                $subClassOfObject = strtolower(substr(get_class($subDbObject), 15));
                //сливаем в массив пригодный для селекта
                $arrayOfIssueSubDbObject = $this->issueSubClass($subDbObject, $this->subClasses[$subClassOfObject]);
                $subClassId = $subClassOfObject . "_id";
                // в форму в зависимое поле сливаем массив данных 
                $form->$subClassId->setMultiOptions($arrayOfIssueSubDbObject);
            }
        }
        //дописываем hidden поле айдишником
        $form->hiddenid->setValue($generalDbObject->getId());
        // получаем поля с таблицы с которой работаем (Answer)
        $formFields = $generalDbObject->getSelectFields();
        foreach ($formFields as $key => $value) {
            //пропускаем поле id т.к. не редактируем его
            if ($value == 'id' || $value == 'archive' || $value == 'role_id')
                continue;
            // устанавливаем значение считанное с базы
           
            $form->$value->setValue($generalDbObject->$value);
        }
        // рисуем форму
        $this->view->form = $form;
    }

    /**
     * Выгружаем массив пригодный для формы с зависимых таблиц и полей 
     * 
     * @param Zend_DatabaseObject $subDbObject - зависимый объект ( объект считается зависимым если в 
     * основном объекте есть ссылка типа *_id   (напр question_id) 
     * @param array $subFields поля по которым делается массив для формы выглядит array[id][текстовое поле]
     * @return array $form_fields - массив для формы
     */
    protected function issueSubClass(Zend_DatabaseObject $subDbObject, array $subFields) {
        $issueSmths = $subDbObject->loadAll($subFields);
        if (!isset($selected))
            $form_fields[""] = 'выберите ...';
        foreach ($issueSmths as $key => $val) {
            $form_fields[$val[$subFields[0]]] = $val[$subFields[1]];
        }
        return $form_fields;
    }

    /**
     * получаем название формы 
     * 
     * @param string $classname - имя контроллера 
     * @return new string $formName
     */
    private function getForm($classname) {
        $formName = "Application_Form_$classname";
        return new $formName();
    }

    /**
     * получаем название валидируещего класса 
     * 
     * @param string $classname - имя контроллера 
     * @param Application_Model_DBAdapter $db ссылка на бд
     * @return new FormProcessor_Object $fpName
     */
    protected function getFormProcessor($classname, $db) {
        $fpName = "FormProcessor_" . $classname . "Validation";
        return new $fpName($db);
    }

    /**
     * получаем название главного объекта типа DatabaseObject
     * 
     * @param string $classname - имя контроллера 
     * @param Application_Model_DBAdapter $db ссылка на бд
     * @return new DatabaseObject $generalDbObjectName
     */
    protected function getGeneralDbObject($classname) {
        $generalDbObjectName = "Application_Model_Db_$classname"; 
        return new $generalDbObjectName();
    }

    /**
     * получаем массив зависимых таблиц DatabaseObject_$subClass
     * 
     * @param array $subClasses - имя зависимых таблиц DatabaseObject 
     * @param Application_Model_DBAdapter $db ссылка на бд
     * @return array $subDbObjects  
     */
    protected function getSubDbObject($subClasses, $db) {
        $subDbObjects = array();
        foreach ($subClasses as $subClass => $subFields) {
            $subDbObjectName = "DatabaseObject_$subClass";
            $subDbObjects [$subClass] = new $subDbObjectName($db);
        }
        return $subDbObjects;
    }

}
