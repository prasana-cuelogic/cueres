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
        public function getAvailableSlots($date, $slotTime, $type){
            //return $date.$slotTime.$type;
            //$arrBookedSlots = $this->getBookedSlots($date);

            $arrTimeChunks = array(
                'from' => array(
                    "10:00", "14:30", "17:15"
                ),
                'end'  => array(
                    "14:00", "16:45", "21:00"
                )
            );
            $time = strtotime($arrTimeChunks['from'][0]);
            /*echo date("Y-m-d H:i:s", $time);
            $time += (30*60);
            echo "==>";
            echo date("Y-m-d H:i:s", $time);
            die;*/
            $arrTime = $this->createTimeSlotForBooking(30, $arrTimeChunks);
            echo "<pre>";print_r($arrTime);die;
        }

        /**
         * function will create chunks in which user can book new slot.
         * @param $arrBookedTime
         *
         * @return array
         */
        private function getAvailableTimeChunks($arrBookedTime){
            $arrTime = array();
            $arrTime["from"][] = $this->startTime;
            foreach($arrBookedTime as $objTime){
                $arrTime['end'][] = date("H:i", $objTime->book_from);
                $arrTime['from'][] = date("H:i", $objTime->book_end);
            }
            $arrTime["end"][] = $this->endTime;
            return $arrTime;
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
                        $arrTimeSlots[] = date("h:i a",$strStartTime);
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
