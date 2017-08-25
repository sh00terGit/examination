<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_InfoExamMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Question');
    }

    public function fetch($id) {
        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from(array('questions' => 'result_exam_questions'), array('question_text' => 'questions.question_text'))
                ->joinLeft(array('answers' => 'result_exam_answers'), 'answers.result_exam_question_id = questions.id ', array('answer_text' => 'answers.answer_text',
                    'positive' => 'answers.positive'))
                ->joinLeft(array('user_answers' => 'result_exam_answers_user'), 'user_answers.result_exam_answer_id = answers.id ', array('user_answer_id' => 'user_answers.id'))
                ->where('questions.result_schedule_user_id = ?', $id)
                ->order('questions.id');
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));

        $infoExams = array(); // вопросы с ответами экзаменатора и пользователя
        $currentQuestion = null;  // настоящий вопрос , для слежения изменения (записи отфильтрованы по вопросам)
        foreach ($result as $row => $value) {
            //если вопрос изменился то закидываем новый вопро
            if ($currentQuestion != $value['question_text']) {
                //сохраняем предыдущий вопрос 
                if ($currentQuestion != null) {
                    $infoExams[] = $question;
                }
                
                $question = new Application_Model_InfoExam();
                $currentQuestion = $value['question_text'];
                $question->setQuestion($value['question_text']);


                switch ($value['positive']) {
                    case 1:
                        if ($value['user_answer_id'] != null) {
                            $question->addUserPositiveAns($value['answer_text']);
                        }
                        $question->addExamPositiveAns($value['answer_text']);
                        break;
                    case 0 :
                        if ($value['user_answer_id'] != null) {
                            $question->addUserNegativeAns($value['answer_text']);
                        }
                        $question->addExamNegativeAns($value['answer_text']);
                        break;
                }
                // если вопрос не менялся , закидываем новые ответы
            } else {

                switch ($value['positive']) {
                    case 1:
                        if ($value['user_answer_id'] != null) {
                            $question->addUserPositiveAns($value['answer_text']);
                        }
                        $question->addExamPositiveAns($value['answer_text']);
                        break;
                    case 0 :
                        if ($value['user_answer_id'] != null) {
                            $question->addUserNegativeAns($value['answer_text']);
                        }
                        $question->addExamNegativeAns($value['answer_text']);
                        break;
                }
            }
            //проверяем есть ли еще вопросы , если нет , то сохраняем последний вопрос с ответами
            if($result[$row + 1] === null) {
                $infoExams[] = $question;
            }
        }
        return $infoExams;
    }

}
