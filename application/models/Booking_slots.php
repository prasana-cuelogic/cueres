<?php
/**
 * Created by PhpStorm.
 * User: cuelogic
 * Date: 3/3/16
 * Time: 5:11 PM
 */

class Booking_slots extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database();
    }

    public function getBookedSlotsByUid($uid) {
        return $query = $this->db->query('SELECT b.*, c.category
                                          FROM bookedslots as b
                                          INNER JOIN categories as c on (c.category_id = b.category_id)
                                          WHERE uid='.$uid);
    }

    public function getBookedSlotsByDateCategory($date, $category) {
        return $query = $this->db->query("SELECT * FROM bookedslots WHERE book_date ='$date' AND category_id = $category");
    }

    public function bookAppointment($uid,$purpose, $date, $category, $bookStart, $bookEnd) {
        return $query = $this->db->query("INSERT INTO bookedslots (id, uid, category_id, status, purpose, book_date, book_from, book_to) VALUES (NULL, $uid, $category, 'active', '$purpose', '$date', '$bookStart', '$bookEnd');");
    }

    public function cancelAppointment($slotId, $uid) {
        return $this->db->query("UPDATE bookedslots SET status = 'cancel' WHERE uid= $uid AND id = $slotId");
    }

    public function getBookedSlotsByDate($date) {
        return $query = $this->db->query("SELECT b.*, c.category, u.full_name
                                          FROM bookedslots as b
                                          INNER JOIN categories as c on (c.category_id = b.category_id)
                                          INNER JOIN user as u on (u.uid = b.uid)
                                          WHERE book_date ='$date'");
    }
}