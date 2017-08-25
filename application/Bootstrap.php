<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_administrator = '1';
    private $_examiner = '2';
    private $_user = '3';
    private $_editor = '4';
    
    
    protected function _initZFDebug()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'plugins' => array(
                'Variables',
                'File' => array('base_path' => APPLICATION_PATH.'/library'),
                'Memory',
                'Time',
                'Registry',
                'Exception',
                'Html',
            )
        );

        // Настройка плагина для адаптера базы данных
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $db = $this->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        // Настройка плагина для кеша
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache = $this-getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }
    

    protected function _initAutoload() {

        $services = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . '/models'
        ));
        $services->addResourceType('classes_type', 'services', 'services');
        
        $interface = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . '/models/services'
        ));
        $interface->addResourceType('interface_type', 'interfaces', 'IService');

        $forms = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH,
        ));
        $forms->addResourceType('classes_type', 'forms', 'Form');


        $customElement = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH,
        ));
        $customElement->addResourceType('classes_type', 'formElements', 'customElement');
    }

    public function _initRoute() {
        // Получаем маршрут, по-умолчанию
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addDefaultRoutes();

        $route = new Zend_Controller_Router_Route(
                'preloadexam/:scheduleId', array(
            'controller' => 'preLogin',
            'action' => 'preloadexam'
                )
        );
        $router->addRoute('getExam', $route);

        
        ///////////////////////////////////////////////////
        
//         $route = new Zend_Controller_Router_Route(
//                'exam/:id', array(
//            'controller' => 'exam',
//            'action' => 'index'
//                )
//        );
//
//        $router->addRoute('exam', $route);

        
        //////////////////////////////////////////////////////
         $route = new Zend_Controller_Router_Route(
                'result/load/:id', array(
            'controller' => 'result',
            'action' => 'result-users'
                )
        );

        $router->addRoute('scheduleResult', $route);
        
        
         $route = new Zend_Controller_Router_Route(
                'result/load/:id/:scheduleUserId', array(
            'controller' => 'result',
            'action' => 'result-view'
                )
        );

        $router->addRoute('scheduleResultView', $route);
        
        
        
        $route = new Zend_Controller_Router_Route(
                'chapter/edit/:id', array(
            'controller' => 'chapter',
            'action' => 'edit'
                )
        );

        $router->addRoute('chapterEdit', $route);
        $route = new Zend_Controller_Router_Route(
                'chapter/delete/:id', array(
            'controller' => 'chapter',
            'action' => 'delete'
                )
        );

        $router->addRoute('chapterDelete', $route);

        $route = new Zend_Controller_Router_Route_Static(
                '/check', array(
            'controller' => 'exam',
            'action' => 'check'
                )
        );

        $router->addRoute('check', $route);



        $route = new Zend_Controller_Router_Route(
                'part/delete/:id', array(
            'controller' => 'part',
            'action' => 'delete'
                )
        );

        $router->addRoute('partDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'part/edit/:id', array(
            'controller' => 'part',
            'action' => 'edit'
                )
        );

        $router->addRoute('partEdit', $route);


        $route = new Zend_Controller_Router_Route(
                'question/delete/:id', array(
            'controller' => 'question',
            'action' => 'delete'
                )
        );

        $router->addRoute('questionDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'question/edit/:id', array(
            'controller' => 'question',
            'action' => 'edit'
                )
        );

        $router->addRoute('questionEdit', $route);

        $route = new Zend_Controller_Router_Route(
                '/checkquestion', array(
            'controller' => 'question',
            'action' => 'checkquestion'
                )
        );
        $router->addRoute('questionCheck', $route);

        $route = new Zend_Controller_Router_Route(
                'answer/add/:id', array(
            'controller' => 'answer',
            'action' => 'add'
                )
        );
        $router->addRoute('answerAdd', $route);

        $route = new Zend_Controller_Router_Route(
                'answer/edit/:id', array(
            'controller' => 'answer',
            'action' => 'edit'
                )
        );

        $router->addRoute('answerEdit', $route);

        $route = new Zend_Controller_Router_Route(
                '/checkanswer', array(
            'controller' => 'answer',
            'action' => 'checkanswer'
                )
        );
        $router->addRoute('answerCheck', $route);

        $route = new Zend_Controller_Router_Route(
                'answer/delete/:id', array(
            'controller' => 'answer',
            'action' => 'delete'
                )
        );

        $router->addRoute('answerDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'user/edit/:id', array(
            'controller' => 'user',
            'action' => 'edit'
                )
        );

        $router->addRoute('userEdit', $route);


        $route = new Zend_Controller_Router_Route(
                'user/delete/:id', array(
            'controller' => 'user',
            'action' => 'delete'
                )
        );

        $router->addRoute('userDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'schedule/edit/:id', array(
            'controller' => 'schedule',
            'action' => 'edit'
                )
        );

        $router->addRoute('scheduleEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'schedule/delete/:id', array(
            'controller' => 'schedule',
            'action' => 'delete'
                )
        );

        $router->addRoute('scheduleDelete', $route);


        $route = new Zend_Controller_Router_Route(
                'adminExam/edit/:id', array(
            'controller' => 'adminExam',
            'action' => 'edit'
                )
        );

        $router->addRoute('adminExamEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'adminExam/delete/:id/:archive', array(
            'controller' => 'adminExam',
            'action' => 'delete'
                )
        );

        $router->addRoute('adminExamDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'adminExam/restore/:id/', array(
            'controller' => 'adminExam',
            'action' => 'restore'
                )
        );

        $router->addRoute('adminExamRestore', $route);


        $route = new Zend_Controller_Router_Route(
                'adminExam/filling/:id/:typeBilet', array(
            'controller' => 'adminExam',
            'action' => 'filling'
                )
        );

        $router->addRoute('adminExamFilling', $route);

        $route = new Zend_Controller_Router_Route(
                'schedule/add-users/:scheduleId', array(
            'controller' => 'schedule',
            'action' => 'add-users'
                )
        );

        $router->addRoute('scheduleAddUsers', $route);


        $route = new Zend_Controller_Router_Route(
                'criterion/edit/:id', array(
            'controller' => 'criterion',
            'action' => 'edit'
                )
        );

        $router->addRoute('criterionEdit', $route);



        $route = new Zend_Controller_Router_Route(
                'criterion/delete/:id', array(
            'controller' => 'criterion',
            'action' => 'delete'
                )
        );

        $router->addRoute('criterionDelete', $route);


        $route = new Zend_Controller_Router_Route(
                'section/add/:examId', array(
            'controller' => 'section',
            'action' => 'add'
                )
        );

        $router->addRoute('sectionAdd', $route);

        $route = new Zend_Controller_Router_Route(
                'section/edit/:examId/:id', array(
            'controller' => 'section',
            'action' => 'edit'
                )
        );

        $router->addRoute('sectionEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'section/delete/:id', array(
            'controller' => 'section',
            'action' => 'delete'
                )
        );

        $router->addRoute('sectionDelete', $route);


        $route = new Zend_Controller_Router_Route(
                'post/edit/:id', array(
            'controller' => 'post',
            'action' => 'edit'
                )
        );

        $router->addRoute('postEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'post/delete/:id', array(
            'controller' => 'post',
            'action' => 'delete'
                )
        );

        $router->addRoute('postDelete', $route);

        $route = new Zend_Controller_Router_Route(
                'division/edit/:id', array(
            'controller' => 'division',
            'action' => 'edit'
                )
        );

        $router->addRoute('divisionEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'division/delete/:id', array(
            'controller' => 'division',
            'action' => 'delete'
                )
        );

        $router->addRoute('divisionDelete', $route);



        $route = new Zend_Controller_Router_Route(
                'subDivision/edit/:id', array(
            'controller' => 'subDivision',
            'action' => 'edit'
                )
        );

        $router->addRoute('subDivisionEdit', $route);

        $route = new Zend_Controller_Router_Route(
                'subDivision/delete/:id', array(
            'controller' => 'subDivision',
            'action' => 'delete'
                )
        );

        $router->addRoute('subDivisionDelete', $route);


        $route = new Zend_Controller_Router_Route(
                'adminExam/override-manager-exam/:id', array(
            'controller' => 'adminExam',
            'action' => 'override-manager-exam'
                )
        );

        $router->addRoute('adminExamOverrideManagerExam', $route);

        $route = new Zend_Controller_Router_Route(
                'adminExam/clone-exam-data/:id', array(
            'controller' => 'adminExam',
            'action' => 'clone-exam-data'
                )
        );
        $router->addRoute('adminExamCloneExamData', $route);
        
        $route = new Zend_Controller_Router_Route(
                'adminExam/edit-theme/:id', array(
            'controller' => 'adminExam',
            'action' => 'edit-theme'
                )
        );
        
        $router->addRoute('adminExamEditTheme', $route);
        
        $route = new Zend_Controller_Router_Route(
                'subDivision/add-type/:id', array(
            'controller' => 'subDivision',
            'action' => 'add-type'
                )
        );
        
        $router->addRoute('subDivisionAddType', $route);
        
        
        $route = new Zend_Controller_Router_Route(
                'subDivision/edit-type/:id', array(
            'controller' => 'subDivision',
            'action' => 'edit-type'
                )
        );
        
        $router->addRoute('subDivisionEditType', $route);
        
        
        $route = new Zend_Controller_Router_Route(
                'subDivision/delete-type/:id', array(
            'controller' => 'subDivision',
            'action' => 'delete-type'
                )
        );
        
        $router->addRoute('subDivisionDeleteType', $route);
        
        
        
        $route = new Zend_Controller_Router_Route(
                'adminExam/delete-theme/:id', array(
            'controller' => 'adminExam',
            'action' => 'delete-theme'
                )
        );
        
        $router->addRoute('themeDelete', $route);



        Zend_Controller_Front::getInstance()->setRouter($router);
        return $router;
    }

    protected function _initAcl() {
        // Создаём объект Zend_Acl
        $acl = new Zend_Acl();

        $acl->addResource('index');
        $acl->addResource('result');
        $acl->addResource('preLogin');
        $acl->addResource('admin');
        $acl->addResource('auth');
        $acl->addResource('error');
        $acl->addResource('post');
        $acl->addResource('division');
        $acl->addResource('subDivision');
        $acl->addResource('PersonCompare');
        $acl->addResource('chapter');
        $acl->addResource('part');
        $acl->addResource('question');
        $acl->addResource('answer');
        $acl->addResource('user');
        $acl->addResource('section');
        $acl->addResource('login', 'auth');
        $acl->addResource('exam');
        $acl->addResource('criterion');
        $acl->addResource('schedule');
        $acl->addResource('adminExam');
        $acl->addResource('logout', 'auth');

        $acl->addRole($this->_user);

        $acl->allow($this->_user, 'index');
        $acl->allow($this->_user, 'exam');
        $acl->allow($this->_user, 'auth', array('index', 'login', 'logout'));

        // администратор, который наследует доступ от гостя
        $acl->addRole($this->_administrator, $this->_user);


        $acl->addRole('guest');
        $acl->allow('guest', 'auth', array('index', 'login', 'logout'));
        
        $acl->allow('guest','preLogin');


        //Разрешаем админу просматривать ресурс таблиц и форм
        $acl->allow($this->_administrator, 'post');
        $acl->allow($this->_administrator, 'preLogin');
        $acl->allow($this->_administrator, 'division');
        $acl->allow($this->_administrator, 'subDivision');
        $acl->allow($this->_administrator, 'PersonCompare');
        $acl->allow($this->_administrator, 'user');
        $acl->allow($this->_administrator, 'section');
        $acl->allow($this->_administrator, 'schedule');
        $acl->allow($this->_administrator, 'admin');
        $acl->allow($this->_administrator, 'criterion');
        $acl->allow($this->_administrator, 'adminExam');
        $acl->allow($this->_administrator, 'chapter');
        $acl->allow($this->_administrator, 'part');
        $acl->allow($this->_administrator, 'question');
        $acl->allow($this->_administrator, 'answer');
        $acl->allow($this->_administrator, 'result');
        $acl->addRole($this->_examiner, $this->_administrator);
        $acl->deny($this->_examiner, array('division', 'subDivision', 'post'));
        //тут дени на экзаменатора
        $acl->addRole($this->_editor, $this->_administrator);
        $acl->deny($this->_editor, array('division', 'subDivision', 'post', 'adminExam', 'schedule', 'section', 'criterion'));
        // тут дени для редактора
        // получаем экземпляр главного контроллера
        $fc = Zend_Controller_Front::getInstance();

        // регистрируем плагин с названием AccessCheck, в который передаём
        // на ACL и экземпляр Zend_Auth
        $fc->registerPlugin(new Application_Plugin_AccessCheck($acl, Zend_Auth::getInstance()));
        
        ///включение кеширования метаданных кэша
        $cache = Application_Model_CacheEnjine::getInstance();
        $resCache = $cache->getCache();
//        
    }

}
