<?php

 class Application_Model_Db_ExamSection extends Zend_Db_Table_Abstract {
     protected $_name = 'exam_section';
     protected $_referenceMap = array(
         'Exam' =>array(
            'columns' => array('exam_id'),
            'refTableClass' => 'Application_Model_Db_Exam',
            'refColumns' => array('id')
         ));

     
 }

