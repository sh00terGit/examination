<?php

/*
 *  Форма ассоциации разделов НСИ
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_Dictionary extends Application_Form_Ru {


    public function init() {


        // указываем имя формы
        $this->setName('dictionary');

        $submit = new CustomElement_Submit('submit');
        $level1 = new CustomElement_Radio('level1',true);
        $level2 = new CustomElement_Radio('level2',true);

        $submit->setLabel('Принять');
        $level1->setLabel('Первый уровень -- ');
        $level2->setLabel('Второй уровень -- ');
        // добавляем элементы в форму
        $this->addElements(array(
            $level1,
            $level2,
            $submit));

        // указываем метод передачи данных
        $this->setMethod('post');
//        $this->setAction('/checkform');
    }

}
