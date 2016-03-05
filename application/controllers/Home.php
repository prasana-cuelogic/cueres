<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {

        private $arrReturn;

        public function __construct(){
            $this->arrReturn = array("success"=>0, "text"=>"", "data"=>"");
        }
        public function index(){
            $this->load->model('booking_slots');
            $this->load->library('booking_data');
            $result = $this->booking_data->getAvailableSlots('2016-03-04', '30', 'meeting_room');
            echo $result;
            //$this->booking_slots->getBookedSlotsByUid(82);
        }

        /**
         * Function check user login details and return his booked slots.
         * @return string
         */
        public function login() {
            //Set variables
            $uid = $this->input->post('cueid');
            $password = $this->input->post('password');

            $this->load->model('user');
            $intUid = $this->user->login($uid, $password);
            if((int)$intUid > 0){
                $this->arrReturn['success'] = 1;
                $this->load->model('booking_slots');
                $arrBookedSlots = $this->booking_slots->getBookedSlotsByUid($intUid);
                $this->arrReturn['data'] = $arrBookedSlots;
            } else {
                $this->arrReturn['success'] = 0;
                $this->arrReturn['text'] =  "Invalid login details.";
            }
            return json_encode($this->arrReturn);die;
        }

    }
