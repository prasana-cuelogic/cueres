<?php
/**
 * Created by PhpStorm.
 * User: cuelogic
 * Date: 3/3/16
 * Time: 5:11 PM
 */

class Booking_slots extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database();
    }



    public function getBookedSlotsByUid($uid){
        return $query = $this->db->query('SELECT * FROM bookedslots WHERE uid='.$uid)->result();
    }

    public function getBookedSlotsByDate($date){
        return $query = $this->db->query('SELECT * FROM bookedslots WHERE book_from ='.$date)->result();
    }

} 