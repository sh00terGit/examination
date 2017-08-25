<?php

class AuthController extends Zend_Controller_Action {

    public function indexAction() {
        $this->_helper->redirector('login');
    }

    /**
     * Api входа 
     */
    public function loginAction() {
        $this->_helper->layout->setLayout('login');
        // проверяем, авторизирован ли пользователь
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // если да, то делаем редирект, чтобы исключить многократную авторизацию
            $identity = Zend_Auth::getInstance()->getIdentity();
            if ($identity->role_id == 3) {
                $this->_helper->redirector('index', 'pre-login');
            } else {
                $this->_helper->redirector('index', 'admin');
            }
        }

        // создаём форму и передаём её во view
        $form = new Application_Form_Login();
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Получаем адаптер подключения к базе данных
                // $authAdapter = new Zend_Auth_Adapter_DbTable(Application_Model_DBAdapter::getadapter());

                $authAdapter = new Zend_Auth_Adapter_DbTable($this->_config->db);

                // указываем таблицу, где необходимо искать данные о пользователях
                // колонку, где искать имена пользователей,
                // а также колонку, где хранятся пароли
                $authAdapter->setTableName('users')
                        ->setIdentityColumn('login')
                        ->setCredentialColumn('passw')
                        ->setCredentialTreatment('Sha1(?)');

                // получаем введённые данные
                $login = $this->getRequest()->getPost('login');
                $passw = $this->getRequest()->getPost('passw');
                // подставляем полученные данные из формы
                $authAdapter->setIdentity($login)
                        ->setCredential($passw);

                // получаем экземпляр Zend_Auth
                $auth = Zend_Auth::getInstance();

                // делаем попытку авторизировать пользователя
                $result = $auth->authenticate($authAdapter);
                // если авторизация прошла успешно
                if ($result->isValid()) {
                    // используем адаптер для извлечения оставшихся данных о пользователе
                    $identity = $authAdapter->getResultRowObject();

                    // получаем доступ к хранилищу данных Zend
                    $authStorage = $auth->getStorage();

                    // помещаем туда информацию о пользователе,
                    // чтобы иметь к ним доступ при конфигурировании Acl
                    $authStorage->write($identity);

                    $user = Zend_Auth::getInstance()->getIdentity();
                    //role_id = 3- пользователь
                    //role_id = 1 - админ
                    //role_id = 2 - экзаменатор
                    //role_id = 4 - редактор
                    if ($user->role_id != '3') {
                        // Используем библиотечный helper для редиректа
                        // на controller = index, action = index
                        //записываем в сессию перевод для первого и второго уровня (chapter_name , part_name)
                        $this->writeInSessionDictionary($user->id);

                        $this->_helper->redirector('index', 'admin');
                    } else {
                        $this->_helper->redirector('logout-user', 'auth');
                    }
                } else {
                    $this->_helper->redirector('logout-user', 'auth');
                }
            } else {
                $this->view->errMessage = 'Вы ввели неверное имя пользователя или неверный пароль';
                $tag = $form->passw->getDecorator('HtmlTag');
                $tag->setOption('class', 'form-group has-error');
                $tag = $form->login->getDecorator('HtmlTag');
                $tag->setOption('class', 'form-group has-error');
            }
        }
    }

    public function logoutAction() {
        // уничтожаем информацию об авторизации пользователя
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        // и отправляем его на главную
        $this->_helper->redirector('index', 'pre-login');
    }

    public function logoutLoginAction() {
        // уничтожаем информацию об авторизации пользователя
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        // и отправляем его на главную
        $this->_helper->redirector('login', 'auth');
    }

    public function logoutUserAction() {
        $this->_helper->layout->setLayout('login');
        // уничтожаем информацию об авторизации пользователя
        Zend_Auth::getInstance()->clearIdentity();
        // и отправляем его на главную
    }

    /**
     * Записываем в сессию словарь (chapter_name , part_name) для пользователя по его id
     * 
     * @param user_id
     * @return serialize(dic)
     */
    public function writeInSessionDictionary($userId) {
        $mapper = new Application_Model_UserDictionaryMapper();
        $personDictionary = $mapper->findByUserId($userId);
        $sessionDictionary = new Zend_Session_Namespace('dictionary');
        $sessionDictionary->dictionary = serialize($personDictionary);
    }

}
