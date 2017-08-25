<?php

/*
 *  Форма выбора персональных тем 
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

class Application_Form_UserTheme extends Application_Form_Ru {

    protected $themes, $acceptedThemes, $managerId ;

    /**
     *  
     * @param type $themes темы
     * @param type $manager экзаменатор
     */
    public function __construct($themes, $manager) {
        $this->themes = $themes;
        $this->managerId = $manager->id;
        parent::__construct();
    }

    public function init() {
        $managerId = new Zend_Form_Element_Hidden('manager_id');
        $managerId->setValue($this->managerId);
        $this->addElement($managerId);
        foreach ($this->themes as $theme) {

            $themeCheckbox = new CustomElement_CheckBox('theme' . $theme->getId());
            $themeCheckbox->setLabel($theme->getSname());
            if ($theme->accepted) {
                $themeCheckbox->setChecked(TRUE);
            } 
            $this->addElement($themeCheckbox);
        }
        $submit = new CustomElement_Submit('submit');
        $submit->setLabel('Принять');
        $this->addElement($submit);
        $this->setMethod('post');
        $this->setAction('/PersonCompare/checkform-theme');
    }

}
