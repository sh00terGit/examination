<?php
/**
 * Форма сдачи экзамена
 */
class Application_Form_UserExam extends Application_Form_Ru
{

    private $_questions;
    private $_exam;

    
    /**
     *  Генерируем форму с вопросами и ответами
     * @param type $exam экзамен
     * @param type $questions вопросы на экзамен
     * @param type $options
     */
    public function __construct($exam, $questions, $options = null) {
        $this->_exam = $exam;
        $this->_questions = $questions;
        parent::__construct($options);
    }

    public function init()
    {
                $examId = new Zend_Form_Element_Hidden('examId');
                $examId->setValue( $this->_exam->getId());
                
                $countQuestions = new Zend_Form_Element_Hidden('countQuestions');
                // количество вопросов в билете
                $countQuestions->setValue(count($this->_questions));               
                $this->addElements(array($countQuestions, $examId));
                foreach ($this->_questions as $question) {
                    // чекбокс с именем = id вопроса
                    $quest = new Zend_Form_Element_MultiCheckbox($question->getId());
                    $answerMapper = new Application_Model_AnswerMapper();
                    // достаем ответы к этому вопросу
                    $answers = $answerMapper->fetchAnswers($question);
                    

                    // перемешиваем ответы
                    shuffle($answers);
                    $options = array();
                    foreach ($answers as $answer) {
                        //закидываем в массив options для мультичекбокса
                        $options[$answer->getId()] = $answer->getContent(); 
//                        echo 'вопрос'.$question->getId().": ответ ".$answer->getContent()."  </br>";
                    }

                    $quest->addMultiOptions($options);

                    $quest->setLabel($question->getText());
                    $this->addElement($quest);
                }
                $submit = new Zend_Form_Element_Submit('submit');
                $submit->setOptions(array('class' => 'btn  btn-primary '));
                $submit->setLabel('Принять');
                $this->addElement($submit);
                // рендерим форму в отдельном файле 
              
                $this->setAction('/check');
                $this->setMethod('post');
                
                  $this->setDecorators(array(array('ViewScript', array('viewScript' => 'exam/questForm.phtml', 'questions' => $this->_questions, 'form' => $this))
                ));
        
         
    }
}

