<?php

class AdminController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();
     
        
}
}