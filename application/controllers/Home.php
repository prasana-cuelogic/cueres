<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {


        public function index(){
            $this->load->model('booking_slots');
            $this->load->library('booking_data');
            $result = $this->booking_data->getAvailableSlots('2016-03-04', '30', 'meeting_room');
            echo $result;
            //$this->booking_slots->getBookedSlotsByUid(82);
        }

        public function login(){
            //Set variables
            $uid = $this->input->post('cueid');
            $password = $this->input->post('password');

            $this->load->model('user');
            if($this->user->login($uid, $password)){
                echo true;
            } else {
                echo "Invalid login details.";die;
            }

        }

    }
