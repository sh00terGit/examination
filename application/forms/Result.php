<?php

/**
 * НЕ ИСПОЛЬЗУЕТСЯ 
 */
class Application_Form_Result extends Application_Form_Ru {


    public function init() {


     $examFname = new CustomElement_Select('exam_id');
     $themeFname = new CustomElement_Select('theme_id');
     $dateStart = new CustomElement_Text('dateStart');
     $dateEnd = new CustomElement_Text('dateEnd');
     $manager = new CustomElement_Select('manager');
        // добавляем элементы в форму
        $this->addElements(array(
            $hiddenid,
            $question_id,
            $content,
            $positive,
            $submit));
        
        // указываем метод передачи данных
        $this->setMethod('post');
    }

}
