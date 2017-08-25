<?php

/*
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Model_ExamMapper extends Application_Model_MapperNp {

    public function __construct() {
        $this->setDbTable('Application_Model_Db_Exam');
    }

    public function find($id, Application_Model_Exam $exam) {

        $table_criterion = new Application_Model_Db_Criterion();
        $table_divisions = new Application_Model_Db_Divisions();
        $table_users = new Application_Model_Db_Users();


        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            echo 'Такого экзамена не существует';
            return;
        }
        $row = $result->current();
        $exam->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setDivision($row->findParentRow($table_divisions)->fname)
                ->setManager($row->manager_id)
                ->setComment($row->comment)
                ->setTheme($row->exam_theme_id);
        return $exam;
    }

    public function findById($id) {

        $exam = new Application_Model_Exam();
        $row = $this->getDbTable()->fetchRow("id = $id");
        
        $exam->setId($row->id)
                ->setFname($row->fname)
                ->setSname($row->sname)
                ->setDivision($row->division_id)
                ->setManager($row->manager_id)
                ->setComment($row->comment)
                ->setType($row->bilet)                
                ->setTheme($row->exam_theme_id);
        $table_exam_chapter = new Application_Model_Db_ExamChapter();
        $exam_chapters = $table_exam_chapter
                ->fetchAll($where = array('exam_id = ?' => $row->id),$order = 'chapter_id ASC');
        foreach ($exam_chapters as $exam_chapter) {
            $exam->addChapter($exam_chapter->chapter_id);
        }

        return $exam;
    }

    public function fetchExamsByManager($division_id, $roleId, $archive = 0) {
        if ($roleId == 1) {
            $where = $this->getDbTable()->getAdapter()->quoteInto('archive = ? ', $archive);
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id = ? AND ', $division_id) .
                    $this->getDbTable()->getAdapter()->quoteInto('archive = ? ', $archive);
        }
        $result = $this->getDbTable()->fetchAll($where);
        $exams = array();
        foreach ($result as $row) {
            $exam = new Application_Model_Exam();
            $exam->setId($row->id)
                    ->setComment($row->comment)                    
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManager($row->manager_id)
                    ->setType($row->bilet)
                    ->setDate($row->date)
                    ->setTheme($row->exam_theme_id);
            $exams [] = $exam;
        }
        return $exams;
    }
    
    
        public function fetchExamsByThemes($themesId, $archive = 0) {
            
            $where = $this->getDbTable()->getAdapter()->quoteInto("exam_theme_id IN ($themesId) AND ", $themesId) .
                $this->getDbTable()->getAdapter()->quoteInto('archive = ? ', $archive);           
        $result = $this->getDbTable()->fetchAll($where,'exam_theme_id ASC');
        $exams = array();  
        foreach ($result as $row) {            
            $exam = new Application_Model_Exam();
            $exam->setId($row->id)
                    ->setComment($row->comment)
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManager($row->manager_id)
                    ->setType($row->bilet)
                    ->setDate($row->date)
                    ->setTheme($row->exam_theme_id);
           
            $exams [] = $exam;
        }       
        return $exams;
    }

    public function fetchExamsByDivision($userId, $divisionId, $roleId, $archive = 0) {
        if ($roleId == 1) {
            $where = $this->getDbTable()->getAdapter()->quoteInto('archive = ? ', $archive);
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto('division_id = ? AND ', $divisionId) .
                    $this->getDbTable()->getAdapter()->quoteInto('manager_id <> ? AND ', $userId) .
                    $this->getDbTable()->getAdapter()->quoteInto('archive = ? ', $archive);
        }
        $result = $this->getDbTable()->fetchAll($where);
        $exams = array();
        foreach ($result as $row) {
            $exam = new Application_Model_Exam();
            $exam->setId($row->id)
                    ->setComment($row->comment)
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManager($row->manager_id)
                    ->setType($row->bilet)
                    ->setDate($row->date)
                    ->setTheme($row->exam_theme_id);
            $exams [] = $exam;
        }
        return $exams;
    }

    public function fetchAllPreLoginByDivision($divisionId) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('division_id = ? AND archive = 0', $divisionId);
        $result = $this->getDbTable()->fetchAll($where);
        $exams = array();
        foreach ($result as $row) {
            $exam = new Application_Model_Exam();
            $exam->setId($row->id)
                    ->setComment($row->comment)
                    ->setDivision($row->division_id)
                    ->setFname($row->fname)
                    ->setSname($row->sname)
                    ->setManager($row->manager_id)
                    ->setType($row->bilet)
                    ->setDate($row->date)
                    ->setTheme($row->exam_theme_id);
            $exams [] = $exam;
        }
        return $exams;
    }

    public function fetchAllWithSchedule() {
        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from(array('e' => 'exam'),array('e.fname','e.sname'))
                ->joinLeft(array('sch' => 'schedule'), 
                        'sch.exam_id = e.id', 
                        array(  'sch.subdiv_id', 
                                'sch.date_start',
                                'sch.date_end', 
                                'sch.active_key', 
                                'scheduleId' => 'sch.id',
                                'scheduleManager'=> 'sch.manager_id'
                            ))
                ->joinLeft(array('auth' => 'auth_type'),
                         'sch.auth_type_id = auth.id',
                            array('auth_type'=> 'auth.fname')
                        )
                ->where('sch.date_start != 0 and sch.date_end >= CURRENT_DATE')
                ->order('sch.date_start ASC')
                ->order('e.fname ASC');
        $result = array_values($this->getDbTable()->getAdapter()->fetchAll($select));
        $exams = array();
        foreach ($result as $row => $value) {
           
            $exam = new Application_Model_ExamSchedule();
            $exam->setId($value['id'])
                    ->setScheduleDateStart($value['date_start'])
                    ->setScheduleDateEnd($value['date_end'])
                    ->setSubDivision($value['subdiv_id'])
                    ->setActive($value['active_key'])
                    ->setDivision($value['division_id'])
                    ->setFname($value['fname'])
                    ->setSname($value['sname'])
                    ->setManager($value['scheduleManager'])
                    ->setScheduleId($value['scheduleId'])
                    ->setTypeAuth($value['auth_type']);

            $exams [] = $exam;
            
        }
        return $exams;
    }

    public function findExamSchedule($scheduleId) {
        $select = $this->getDbTable()->getAdapter()
                ->select()
                ->from(array('e' => 'exam'),array('e.id','e.fname','e.sname'))
                ->joinLeft(array('sch' => 'schedule'), 'sch.exam_id = e.id', 
                        array('sch.subdiv_id',
                            'sch.date_start',
                            'sch.date_end',
                            'sch.committee',
                            'sch.active_key',
                            'scheduleId' => 'sch.id' ,
                            'auth_type_id' => 'sch.auth_type_id',
                            'password' => 'sch.password',
                            'scheduleManager'=> 'sch.manager_id'
                            ))
                ->where('sch.id = ?', $scheduleId)
                ->order('sch.date_start ASC')
                ->order('e.sname ASC');     
        
         $result = $this->getDbTable()->getAdapter()->fetchAll($select);
         $value = $result[0];
         
         $exam = new Application_Model_ExamSchedule();
            $exam->setId($value['id'])
                    ->setScheduleDateStart($value['date_start'])
                    ->setScheduleDateEnd($value['date_end'])
                    ->setSubDivision($value['subdiv_id'])
                    ->setActive($value['active_key'])
                    ->setDivision($value['division_id'])
                    ->setFname($value['fname'])
                    ->setSname($value['sname'])
                    ->setManager($value['scheduleManager'])
                    ->setScheduleId($value['scheduleId'])
                    ->setCommittee($value['committee'])
                    ->setTypeAuth($value['auth_type_id'])
                    ->setPassword($value['password']);
         
        return $exam;
     
    }

    public function deleteById($id, $archive = false) {
        $whereId = array("id = ?" => $id);
        if ($archive == FALSE) {
            $this->getDbTable()->delete($whereId);
        } else if ($archive == TRUE) {
            $this->getDbTable()->update(array('archive' => '1'), $whereId);
        }
    }

    public function insert($postArray) {
        $data = array(
            'division_id' => $postArray['division_id'],
            'manager_id' => $postArray['manager_id'],
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'bilet' => $postArray['type'],
            'exam_theme_id' => $postArray['theme']
        );
        $this->getDbTable()->insert($data);
        return $this->getDbTable()->getAdapter()->lastInsertId();
    }

    public function update($postArray) {
        $data = array(
            'division_id' => $postArray['division_id'],
//            'manager_id' => $PostArray['manager_id'],
            'fname' => $postArray['fname'],
            'sname' => $postArray['sname'],
            'comment' => $postArray['comment'],
            'bilet' => $postArray['type'],
            'archive' => $postArray['archive'],
            'exam_theme_id' => $postArray['theme']
        );
        $where = array('id = ?' => $postArray['hiddenid']);
        $this->getDbTable()->update($data, $where);
    }

    public function updateArchive($id) {
        $where = array('id = ?' => $id);
        $data['archive'] = '0';
        $this->getDbTable()->update($data, $where);
    }

    public function updateManager($examId, $managerId) {
        $data = array('manager_id' => $managerId);
        $where = array('id = ?' => $examId);
        $this->getDbTable()->update($data, $where);
    }

}

