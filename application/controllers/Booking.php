<?php
/**
 * Created by PhpStorm.
 * User: cuelogic
 * Date: 5/3/16
 * Time: 11:51 PM
 */
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Booking extends CI_Controller {

        public function index(){
            $today = date("Y-m-d");
            $this->load->model('booking_slots');
            $objResult = $this->booking_slots->getBookedSlotsByDate($today);
            $arrRecord = array();
            $arrBookedSlots = array();
            if ($objResult->num_rows() > 0) {
                foreach ($objResult->result() as $obj) {
                    $arrRecord['name'] = $obj->full_name;
                    $arrRecord['purpose'] = $obj->purpose;
                    $arrRecord['time'] = date("h:i a", strtotime($obj->book_from)) ." - ". date("h:i a", strtotime($obj->book_to));
                    $arrBookedSlots[$obj->category_id][]= $arrRecord;
                }
            }
            //print_r($arrBookedSlots);die;
            $data = array("link"=>$this->config->base_url(), "arrBookedSlots"=>$arrBookedSlots);
            $this->load->view('booking', $data);
        }
    }