<?php

/*
 * Форма заполнения экзамена вопросами
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_FillExam extends Application_Form_Ru {

    protected $_questions, $_examId , $_bilets , $_typeBilet;

    public function __construct($typeBilet,$examId,$questions, $bilets, $options = null) {
        $this->_questions = $questions;
        $this->_examId = $examId;
        $this->_bilets = $bilets;
        $this->_typeBilet = $typeBilet;
        parent::__construct($options);
    }

    public function init() {
         $examId = new Zend_Form_Element_Hidden('exam_id');
         $examId->setValue($this->_examId);
         $this->addElement($examId);
        
        foreach ($this->_questions as $question) {
            // чекбокс с именем = id вопроса
            $quest = new Zend_Form_Element_Checkbox('question'.$question->getId());
            $quest->setLabel($question->getText());
            $this->addElement($quest);
        }
                foreach ($this->_bilets as $biletQuest) {
                    foreach ($biletQuest->getQuestions() as $question) {
            // чекбокс с именем = id вопроса
            $quest = new Zend_Form_Element_Checkbox('biletQuestion'.$question->getId());
            $quest->setLabel($question->getText());
            $this->addElement($quest);
                    }
        }
        
        $submit = new Zend_Form_Element_Submit('submit');
        $this->addElement($submit);
        // рендерим форму в отдельном файле 
         $this->setAction('/adminExam/check-filling');
        $this->setMethod('post');

        $this->setDecorators(array(
            array('ViewScript', array(
                'viewScript' => 'admin-exam/formFillingQuestions.phtml',
                'questions' => $this->_questions,
                'bilets' => $this->_bilets,
                'examId' => $this->_examId,
                'typeBilet' => $this->_typeBilet,
                'form' => $this,
                ))
          ));
    }

}
