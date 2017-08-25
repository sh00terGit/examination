<?php

 class Application_Model_Db_ExamQuest extends Zend_Db_Table_Abstract {
     protected $_name = 'exam_quest';
     protected $_referenceMap = array(
        'ExamSection' => array(
            'columns' => array('exam_section_id'),
            'refTableClass' => 'Application_Model_Db_ExamSection',
            'refColumns' => array('id')
        ),
        'Question' => array(
            'columns' => array('question_id'),
            'refTableClass' => 'Application_Model_Db_Question',
            'refColumns' => array('id'),
        ));

     
 }

