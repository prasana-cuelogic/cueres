<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {

        private $arrReturn;

        public function __construct(){
            parent::__construct();
            $this->arrReturn = array("success"=>0, "text"=>"", "data"=>"", "token"=>"");
        }
        public function index(){
            $this->mobileAPI();
            /*$this->load->model('booking_slots');
            $this->load->library('booking_data');
            $result = $this->booking_data->getAvailableSlots('2016-03-04', '30', 'meeting_room');
            echo $result;*/
            //$this->booking_slots->getBookedSlotsByUid(82);
        }

        private function availableSlots($date, $duration, $category, $chkSlot = ""){
            $this->load->model('booking_slots');
            $this->load->library('booking_data');
            $objResult = $this->booking_slots->getBookedSlotsByDate($date, $category);

            $arrTimeChunk = array();
            $arrBookedSlots = array();
            if ($objResult->num_rows() > 0) {
                foreach ($objResult->result() as $objTime) {
                    $arrTimeChunk[] = $objTime;
                    $arrBookedSlots[]= date("h:i a", strtotime($objTime->book_from)) ." - ". date("h:i a", strtotime($objTime->book_to));
                }
            }
            $arrAvailableSlots = $this->booking_data->getAvailableSlots($duration, $arrTimeChunk);
            $this->arrReturn['data'] =array("available" => $arrAvailableSlots, "booked" => $arrBookedSlots);
        }

        private function getMyBookedSlots($intUid){
            $this->load->model('booking_slots');
            $objResult = $this->booking_slots->getBookedSlotsByUid($intUid);
            if ($objResult->num_rows() > 0) {
                foreach ($objResult->result() as $row) {
                    $arrBookedSlots[] = $row;
                }
            }
            $this->arrReturn['data'] = $arrBookedSlots;
        }

        private function bookAppointment(){
            $uid = $this->input->post('uid');
            $date = $this->input->post('bkDate');
            $category = $this->input->post('type');
            $bookStart = $this->input->post('startTime');
            $purpose = $this->input->post('purpose');
            $duration = $this->input->post('duration');
            if($date != "" && $category != "" && $bookStart != "" && $duration != "") {
                $bookStart = date("H:i:s", strtotime($bookStart));
                $bookEnd = date("H:i:s", strtotime($bookStart) + (60*$duration));

                $this->load->model('booking_slots');
                $intBookingId = $this->booking_slots->bookAppointment($uid, $purpose, $date, $category,$bookStart,$bookEnd);
                if($intBookingId != false){
                    $this->arrReturn['success'] = 1;
                    $this->arrReturn['text'] = "Appointment booked successfully";
                    $this->arrReturn['data'] = $intBookingId;
                } else {
                    $this->arrReturn['success'] = 0;
                    $this->arrReturn['text'] = "Please try again";
                }
            } else {
                $this->invalidParameter();
            }
        }

        private function cancelMySlot($uid, $slotId){
            $this->load->model('booking_slots');
            $result = $this->booking_slots->cancelAppointment($slotId, $uid);
            if($result != false){
                $this->arrReturn['success'] = 1;
                $this->arrReturn['text'] = "Appointment cancelled successfully";
            } else {
                $this->arrReturn['success'] = 0;
                $this->arrReturn['text'] = "Please try again";
            }
        }

        /**
         * Function check user login details and return his booked slots.
         * @return string
         */
        private function login() {
            //Set variables
            $uid = $this->input->post('cueid');
            $password = $this->input->post('password');

            $this->load->model('login');
            $intUid = $this->login->userLogin($uid, $password);
            if((int)$intUid > 0){
                $this->arrReturn['success'] = 1;
                $this->getMyBookedSlots($intUid);
                //$this->arrReturn['token'] = $intUid;
            } else {
                $this->arrReturn['success'] = 0;
                $this->arrReturn['text'] =  "Invalid login details.";
            }
        }

        public function mobileAPI(){
            $uid = $this->input->post('uid');
            $caseAction = $this->input->post('action');
            switch($caseAction){
                case "login":
                    $this->login();
                break;
                case "cancelSlot":
                    if((int)$uid > 0) {
                        $slotId = $this->input->post("aptId");
                        $this->cancelMySlot($slotId,$uid);
                    } else {
                        $this->invalidParameter();
                    }
                break;
                case "myBookedApt":
                    if((int)$uid > 0) {
                        $this->getMyBookedSlots($uid);
                    } else {
                        $this->invalidParameter();
                    }
                break;
                case "bookApt":
                    if((int)$uid > 0) {
                        $this->bookAppointment();
                    } else {
                        $this->invalidParameter();
                    }
                break;
                case "availableSlots":
                    $date = $this->input->post('date');
                    $duration = $this->input->post('duration');
                    $category = $this->input->post('type');
                    $this->availableSlots($date, $duration, $category);
                break;
                case "myBookedSlots":
                default:
                    if((int)$uid > 0) {
                        $this->myBookedSlots($uid);
                    } else {
                        $this->invalidParameter();
                    }
                break;
            }
            print json_encode($this->arrReturn);die;
        }

        private function invalidParameter(){
            $this->arrReturn['success'] = 0;
            $this->arrReturn['text'] =  "Invalid parameters.";
        }

        private function invalidAPICall(){
            $this->arrReturn['success'] = 0;
            $this->arrReturn['text'] =  "Wrong API call";
        }
    }
