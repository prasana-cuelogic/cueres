<?php

class Login extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database();
    }


    public function userLogin($cuid, $password){
        $query = $this->db->query('SELECT cuid FROM bookedslots WHERE uid='.$uid)->result();
    }
} 