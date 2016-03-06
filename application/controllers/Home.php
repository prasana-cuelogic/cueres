<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller {

        private $arrReturn;
        private $postData;
        public function __construct(){
            parent::__construct();
            $this->arrReturn = array("success"=>0, "text"=>"", "data"=>"", "token"=>"");
        }
        public function index(){
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }

            // Access-Control headers are received during OPTIONS requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                    header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

                exit(0);
            }

            $this->mobileAPI();
        }

        private function availableSlots($date, $duration, $category, $chkSlot = ""){
            $this->load->model('booking_slots');
            $this->load->library('booking_data');
            $objResult = $this->booking_slots->getBookedSlotsByDateCategory($date, $category);

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
            $uid = $this->postData->uid;
            $date = $this->postData->bkDate;
            $category = $this->postData->type;
            $bookStart = $this->postData->startTime;
            $purpose = $this->postData->purpose;
            $duration = $this->postData->duration;
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
            $uid = $this->postData->cueid;
            $password = $this->postData->password;

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
            $jsonData = file_get_contents("php://input");
            if (isset($jsonData)) {
                $this->postData = json_decode($jsonData);
                print_r($this->postData);die;
                $uid = (isset($this->postData->uid)) ? $this->postData->uid : "";
                $caseAction = $this->postData->action;
                switch ($caseAction) {
                    case "login":
                        $this->login();
                        break;
                    case "cancelSlot":
                        if ((int) $uid > 0) {
                            $slotId = $this->input->post("aptId");
                            $this->cancelMySlot($slotId, $uid);
                        }
                        else {
                            $this->invalidParameter();
                        }
                        break;
                    case "myBookedApt":
                        if ((int) $uid > 0) {
                            $this->getMyBookedSlots($uid);
                        }
                        else {
                            $this->invalidParameter();
                        }
                        break;
                    case "bookApt":
                        if ((int) $uid > 0) {
                            $this->bookAppointment();
                        }
                        else {
                            $this->invalidParameter();
                        }
                        break;
                    case "availableSlots":
                        $date = $this->postData->date;
                        $duration = $this->postData->duration;
                        $category = $this->postData->type;
                        $this->availableSlots($date, $duration, $category);
                        break;
                    case "myBookedSlots":
                    default:
                        if ((int) $uid > 0) {
                            $this->myBookedSlots($uid);
                        }
                        else {
                            $this->invalidParameter();
                        }
                        break;
                }
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
