<?php

class Login extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database();
    }


    public function userLogin($cuid, $password){
        
        $objResult = $this->db->query("SELECT uid FROM user WHERE cuid='$cuid' AND password = '$password'")->result();
        if($objResult) {
            foreach ($objResult as $row) {
                return $row->uid;
            }
        }
        return false;
    }
} 