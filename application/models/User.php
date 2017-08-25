<?php

class Application_Model_User {
    
    
    public $id,
            $first_name,
            $middle_name,
            $last_name,
            $division,
            $subdivision,
            $post,
            $role,
            $archive,
            $login,
            $passw;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set all properties
     *
     * @param $options
     */
    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Get all properties with values
     *
     * @return $dataArray  key-value array 'property' => 'value'
     */
    public function getOptions() {
        $methods = get_class_methods($this);
        $dataArray = array();
        foreach ($methods as $m) {
            list($property) = sscanf($m, 'get%s');
            $property = strtolower($property);
            if (!empty($property) && property_exists($this, $property))
                $dataArray[$property] = $this->$m();
        }

        return $dataArray;
    }

    /**
     * Routine end.
     * =====================================================================
     */

    /**
     *
     * @param boolean $short
     * @return if $short = true returns Last_Name F.M., else Last_Name First_Name Middle_Name
     */
    public function getFIO($short = FALSE) {
        if ($short) {
            return $this->last_name . ' ' .
                    mb_substr($this->first_name, 0, 1, 'utf-8') . '.' .
                    mb_substr($this->middle_name, 0, 1, 'utf-8');
        } else {
            return $this->last_name . ' ' .
                    $this->first_name . ' ' .
                    $this->middle_name;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getFirst_name() {
        return $this->first_name;
    }

    public function setFirst_name($f_name) {
        $this->first_name = $f_name;
        return $this;
    }

    public function getMiddle_name() {
        return $this->middle_name;
    }

    public function setMiddle_name($m_name) {
        $this->middle_name = $m_name;
        return $this;
    }

    public function getLast_name() {
        return $this->last_name;
    }

    public function setLast_name($l_name) {
        $this->last_name = $l_name;
        return $this;
    }

    public function getDivision() {
        return $this->division;
    }

    public function setDivision($divisionId) {
        $this->division = Services_DivisionService::getInstance()->find($divisionId);
        return $this;
    }

    public function getSubDivision() {
        return $this->subDivision;
    }

    public function setSubDivision($subdivisionId) {
        $subDivMapper = new Application_Model_SubDivisionMapper();
        $this->subDivision = $subDivMapper->find($subdivisionId);
        return $this;
    }

    public function getPost() {
        return $this->post;
    }

    public function setPost($post) {
        $postMapper = new Application_Model_PostMapper();
        $this->post = $postMapper->find($post);
        return $this;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($id) {
        $roleMapper = new Application_Model_RoleMapper();
        $this->role = $roleMapper->find($id);
        return $this;
    }

    //archive
    public function getArchive() {
        return $this->archive;
    }

    public function setArchive($archive) {
        $this->archive = $archive;
        return $this;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    public function getPassw() {
        return $this->passw;
    }

    public function setPassw($passw) {
        $this->passw = $passw;
        return $this;
    }

}
