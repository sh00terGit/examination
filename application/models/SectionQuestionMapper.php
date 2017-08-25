<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_SectionQuestionMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_ExamQuest');
    }

    public function fetchQuestions(Application_Model_ExamSection $section) {
        $table_question = new Application_Model_Db_Question();
        $table_section= new Application_Model_Db_ExamSection();
        
        $sectionDb = $table_section->find($section->getId())->current();
        
        $result = $sectionDb->findManyToManyRowset($table_question, $this->getDbTable());
        if (0 == count($result)) {
            return;
        }   else {
            $questions = array();
        }
        foreach ($result as $row){
            $question = new Application_Model_Question();
            $question   ->setId($row->id)
                    ->setChapter($row->chapter_id)
                    ->setPart($row->part_id)
                    ->setText($row->text)
                    //->setImage($row->image)
                    ->setArchive($row->archive);
            $questions [] = $question;
        }
        return $questions;
    }
    

    

  


}
