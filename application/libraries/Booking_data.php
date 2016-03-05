<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Booking_data {

        private $startTime, $endTime;
        public function __construct(){
            $this->startTime = "10:00";
            $this->endTime = "21:00";
        }
        /**
         * Function will calculate available slots for the requested date.
         * @param $date - Want to book appointment on that date
         * @param $slotTime - Duration of appointment
         * @param $type - appointment type. Conf room, meeting room etc....
         *
         * @return string
         */
        public function getAvailableSlots($duration, $arrBookedTime){

            $arrTime = array();
            $arrTime["from"][] = $this->startTime;
            if(count($arrBookedTime) > 0){
                foreach ($arrBookedTime as $rowTime) {
                    $arrTime['end'][] = date("H:i", strtotime($rowTime->book_from));
                    $arrTime['from'][] = date("H:i", strtotime($rowTime->book_to));
                }
            }
            $arrTime["end"][] = $this->endTime;
            return $arrAvailableSlots = $this->createTimeSlotForBooking($duration, $arrTime);
        }

        /**
         * @param $intDuration - It will be in minutes
         * @param $arrAvailableChunks = array of available slots.
         *
         * @return array
         */
        private function createTimeSlotForBooking($intDuration, $arrAvailableChunks){
            $arrTimeSlots = array();
            $intCount = count($arrAvailableChunks['from']);
            $nextTime = "";
            for($i = 0; $intCount > $i; $i++) {
                $boolFlag = false;
                $strStartTime = strtotime($arrAvailableChunks['from'][$i]);
                $strEndTime = strtotime($arrAvailableChunks['end'][$i]);
                do {
                    $nextTime = $strStartTime + ($intDuration*60);
                    if($nextTime <= $strEndTime){
                        $arrTimeSlots[] = array("time"=>date("h:i a", $strStartTime), "status"=>1);
                        $strStartTime = $nextTime;
                        $boolFlag = true;
                    } else {
                        $boolFlag = false;
                    }
                } while($boolFlag);
            }
            return $arrTimeSlots;
        }


    }
