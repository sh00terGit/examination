<?php
/**
 * сохранение результатов экзаменов в текстовом виде
 */
class ResultController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
    }

   /**
    * Вывод результатов stage 1
    */
    public function loadAction() {
        $results = Services_ResultService::getInstance()->fetchAllResultSchedule();
        $this->view->results = $results;
         
    }
    /**
    * Вывод результатов stage 2
     * вывод попыток пользователей и оценки 
    */
    public function resultUsersAction(){
        $resultScheduleId = $this->_getParam('id'); 
        $results = Services_ResultService::getInstance()->fetchResultUsers($resultScheduleId);
        $this->view->results = $results;
        $this->view->resultScheduleId = $resultScheduleId;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
    }
    
    
    /**
    * Вывод результатов stage 3
     * Лог сдачи вопросов , ответов , ответов экзаменатора
    */
     public function resultViewAction(){    
        $resultScheduleId = $this->_getParam('id'); 
        $resultScheduleUserId = $this->_getParam('scheduleUserId');
        $scheduleInfo = Services_ResultService::getInstance()->findResultSchedule($resultScheduleId);
        $userInfo = Services_ResultService::getInstance()->findResultUser($resultScheduleUserId);
        $mapper = new Application_Model_InfoExamMapper();
        $result = $mapper->fetch($resultScheduleUserId);
        $this->view->result = $result;
        $this->view->infoSchedule = $scheduleInfo;
        $this->view->userInfo = $userInfo;
        $refUrl = $this->getRequest()->getServer('HTTP_REFERER');
        $this->view->refUrl = $refUrl;
        
    }
}