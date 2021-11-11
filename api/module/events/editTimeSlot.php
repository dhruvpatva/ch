<?php
    require_once '../../cmnvalidate.php';
    require_once '../../libs/image/ImageManipulator.php';
if (isset($_SESSION['user'])) {
        $validation_flag = 0;
        $validation_error_code = NULL;
        
        $slot_id = "";
        $event_id = "";
        $start_date = "";
        $end_date = "";
        $start_time = "";
        $end_time = "";
        $timezone = "";
        $slot_type = 0;
        $slot_interval = 0;
        $total_slots = 0;
        $recurring_type = 0;
        $recurring_weeks = "";
        $recurring_months_base = 1;
        $recurring_month_date = "";
        $recurring_month_every = "";
        $recurring_month_day = "";
        $attendee_type = 0;
        $attendee_limit = 0;
        $registration_cut_off = 0;
        $status = 1;
        $created = date('Y-m-d H:i:s');
        $created_by = $_SESSION['user']['user_id'];
        
        //if ($validation_flag == 0 && (!isset($_POST['event_id']) || empty($_POST['event_id']) || empty(str_replace(' ', '', $_POST['event_id'])))) {
        if ($validation_flag == 0 && (!isset($_POST['event_id']) || empty($_POST['event_id']))) {
            $validation_flag = 1;
            $validation_error_code = 'Unauthorised Access.';
        }
        //if ($validation_flag == 0 && (!isset($_POST['start_date']) || empty($_POST['start_date']) || empty(str_replace(' ', '', $_POST['start_date'])))) {
        if ($validation_flag == 0 && (!isset($_POST['start_date']) || empty($_POST['start_date']))) {
            $validation_flag = 1;
            $validation_error_code = 'Start Date Is Required.';
        } else if($validation_flag == 0) {
            $date = explode('-', $_POST['start_date']);
            if (isset($date[0]) && isset($date[1]) && isset($date[2])) {
                if(!$obj->checkdateisvalid($date[0], $date[1], $date[2])){
                    $validation_flag = 1;
                    $validation_error_code = 'Start Date Is Not Valid.';
                }
            } else {
                $validation_flag = 1;
                $validation_error_code = 'Start Date Is Required.';
            }
        }
        //if ($validation_flag == 0 && (!isset($_POST['end_date']) || empty($_POST['end_date']) || empty(str_replace(' ', '', $_POST['end_date'])))) {
        if ($validation_flag == 0 && (!isset($_POST['end_date']) || empty($_POST['end_date']))) {
            $validation_flag = 1;
            $validation_error_code = 'End Date Is Required.';
        } else if($validation_flag == 0) {
            $date = explode('-', $_POST['end_date']);
            if (isset($date[0]) && isset($date[1]) && isset($date[2])) {
                if(!$obj->checkdateisvalid($date[0], $date[1], $date[2])){
                    $validation_flag = 1;
                    $validation_error_code = 'End Date Is Not Valid.';
                }
            } else {
                $validation_flag = 1;
                $validation_error_code = 'End Date Is Required.';
            }
        }
        //if ($validation_flag == 0 && (!isset($_POST['start_time']) || empty($_POST['start_time']) || empty(str_replace(' ', '', $_POST['start_time'])))) {
        if ($validation_flag == 0 && (!isset($_POST['start_time']) || empty($_POST['start_time']))) {
            $validation_flag = 1;
            $validation_error_code = 'Start Time Is Required.';
        }
        //if ($validation_flag == 0 && (!isset($_POST['end_time']) || empty($_POST['end_time']) || empty(str_replace(' ', '', $_POST['end_time'])))) {
        if ($validation_flag == 0 && (!isset($_POST['end_time']) || empty($_POST['end_time']))) {
            $validation_flag = 1;
            $validation_error_code = 'End Time Is Required.';
        }
        //if ($validation_flag == 0 && (!isset($_POST['timezone']) || empty($_POST['timezone']) || empty(str_replace(' ', '', $_POST['timezone'])))) {
        if ($validation_flag == 0 && (!isset($_POST['timezone']) || empty($_POST['timezone']))) {
            $validation_flag = 1;
            $validation_error_code = 'Please Select Slot Timezone.';
        }
        if(isset($_POST['slot_type']) && $_POST['slot_type'] == 1){
            //if ($validation_flag == 0 && (!isset($_POST['slot_interval']) || empty($_POST['slot_interval']) || empty(str_replace(' ', '', $_POST['slot_interval'])))) {
            if ($validation_flag == 0 && (!isset($_POST['slot_interval']) || empty($_POST['slot_interval']))) {
                $validation_flag = 1;
                $validation_error_code = 'Slot Interval Is Required.';
            }
        }
        if(isset($_POST['slot_type']) && $_POST['slot_type'] == 2){
            //if ($validation_flag == 0 && (!isset($_POST['total_slots']) || empty($_POST['total_slots']) || empty(str_replace(' ', '', $_POST['total_slots'])))) {
            if ($validation_flag == 0 && (!isset($_POST['total_slots']) || empty($_POST['total_slots']))) {
                $validation_flag = 1;
                $validation_error_code = 'Total Slots Is Required.';
            }
        }
        if(isset($_POST['slot_type']) && $_POST['slot_type'] != 0){
            if(isset($_POST['recurring_type']) && $_POST['recurring_type'] == 1){
                if ($validation_flag == 0 && isset($_POST['recurring_weeks'])) {
                    $_POST['recurring_weeks'] = array();
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_date'])) {
                    $_POST['recurring_month_date'] = array();
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_every'])) {
                    $_POST['recurring_month_every'] = array();
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_day'])) {
                    $_POST['recurring_month_day'] = array();
                }
            }
            if(isset($_POST['recurring_type']) && $_POST['recurring_type'] == 2){
                if ($validation_flag == 0 && (!isset($_POST['recurring_weeks']) || empty($_POST['recurring_weeks']))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Select atleast one Recurring Week Day.';
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_date'])) {
                    $_POST['recurring_month_date'] = array();
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_every'])) {
                    $_POST['recurring_month_every'] = array();
                }
                if ($validation_flag == 0 && isset($_POST['recurring_month_day'])) {
                    $_POST['recurring_month_day'] = array();
                }
            }
            if(isset($_POST['recurring_type']) && $_POST['recurring_type'] == 3){
                if(isset($_POST['recurring_months_base']) && $_POST['recurring_months_base'] == 1){
                    if ($validation_flag == 0 && (!isset($_POST['recurring_month_date']) || empty($_POST['recurring_month_date']))) {
                        $validation_flag = 1;
                        $validation_error_code = 'Select atleast one Recurring Month Date.';
                    }
                    if ($validation_flag == 0 && isset($_POST['recurring_weeks'])) {
                        $_POST['recurring_weeks'] = array();
                    }
                    if ($validation_flag == 0 && isset($_POST['recurring_month_every'])) {
                        $_POST['recurring_month_every'] = array();
                    }
                    if ($validation_flag == 0 && isset($_POST['recurring_month_day'])) {
                        $_POST['recurring_month_day'] = array();
                    }
                }
                
                if(isset($_POST['recurring_months_base']) && $_POST['recurring_months_base'] == 2){
                    if ($validation_flag == 0 && (!isset($_POST['recurring_month_every']) || empty($_POST['recurring_month_every']))) {
                        $validation_flag = 1;
                        $validation_error_code = 'Select atleast one Recurring Month Every.';
                    }
                    if ($validation_flag == 0 && (!isset($_POST['recurring_month_day']) || empty($_POST['recurring_month_day']))) {
                        $validation_flag = 1;
                        $validation_error_code = 'Select atleast one Recurring Month Day.';
                    }
                    if ($validation_flag == 0 && isset($_POST['recurring_weeks'])) {
                        $_POST['recurring_weeks'] = array();
                    }
                    if ($validation_flag == 0 && isset($_POST['recurring_month_date'])) {
                        $_POST['recurring_month_date'] = array();
                    }
                }
            }
        }
        if(isset($_POST['attendee_type']) && $_POST['attendee_type'] == 1){
            //if ($validation_flag == 0 && (!isset($_POST['attendee_limit']) || empty($_POST['attendee_limit']) || empty(str_replace(' ', '', $_POST['attendee_limit'])))) {
            if ($validation_flag == 0 && (!isset($_POST['attendee_limit']) || empty($_POST['attendee_limit']))) {
                $validation_flag = 1;
                $validation_error_code = 'Total Attendee Is Required.';
            }
        }
        
        if ($validation_flag == 0) {
            if (isset($_POST['event_id'])) {
                $event_id = $_POST['event_id'];
            }
            if (isset($_POST['id'])) {
                $slot_id = $_POST['id'];
            }
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $timezone = $_POST['timezone'];
            
            $date = explode('-', $start_date);
            $start_date = date('Y-m-d H:i:s', strtotime($date[2]."-".$date[0]."-".$date[1]." ".$start_time));
            $start_date = explode(' ', $start_date);
            $start_time = $start_date[1];
            $start_date = $start_date[0];
            
            $date = explode('-', $end_date);
            $end_date = date('Y-m-d H:i:s', strtotime($date[2]."-".$date[0]."-".$date[1]." ".$end_time));
            $end_date = explode(' ', $end_date);
            $end_time = $end_date[1];
            $end_date = $end_date[0];
            
            if($start_time > $end_time){
                $validation_flag = 1;
                $validation_error_code = 'Please select proper time.';
            }
            
            if ($validation_flag == 0) {
                if (isset($_POST['slot_type'])) {
                    $slot_type = $_POST['slot_type'];
                }
                if (isset($_POST['slot_interval'])) {
                    $slot_interval = $obj->replaceUnwantedChars($_POST['slot_interval'], 1);
                }
                if (isset($_POST['total_slots'])) {
                    $total_slots = $obj->replaceUnwantedChars($_POST['total_slots'], 1);
                }
                if (isset($_POST['recurring_type'])) {
                    $recurring_type = $_POST['recurring_type'];
                }
                $recurring_weeks_in = "";
                if (isset($_POST['recurring_weeks'])) {
                    $recurring_weeks = $_POST['recurring_weeks'];
                    if(is_array($recurring_weeks) && !empty($recurring_weeks)){
                        $recurring_weeks_in = implode(",", $recurring_weeks);
                    }
                }
                if (isset($_POST['recurring_months_base'])) {
                    $recurring_months_base = $_POST['recurring_months_base'];
                }
                $recurring_month_date_in = "";
                if (isset($_POST['recurring_month_date'])) {
                    $recurring_month_date = $_POST['recurring_month_date'];
                    if(is_array($recurring_month_date) && !empty($recurring_month_date)){
                        $recurring_month_date_in = implode(",", $recurring_month_date);
                    }
                }
                $recurring_month_every_in = "";
                if (isset($_POST['recurring_month_every'])) {
                    $recurring_month_every = $_POST['recurring_month_every'];
                    if(is_array($recurring_month_every) && !empty($recurring_month_every)){
                        $recurring_month_every_in = implode(",", $recurring_month_every);
                    }
                }
                $recurring_month_day_in = "";
                if (isset($_POST['recurring_month_day'])) {
                    $recurring_month_day = $_POST['recurring_month_day'];
                    if(is_array($recurring_month_day) && !empty($recurring_month_day)){
                        $recurring_month_day_in = implode(",", $recurring_month_day);
                    }
                }
                if (isset($_POST['attendee_type'])) {
                    $attendee_type = $_POST['attendee_type'];
                }
                if (isset($_POST['attendee_limit'])) {
                    $attendee_limit = $_POST['attendee_limit'];
                }
                if (isset($_POST['registration_cut_off'])) {
                    $registration_cut_off = $_POST['registration_cut_off'];
                }
                if (isset($_POST['status'])) {
                    $status = $_POST['status'];
                }
                $query_sel = "SELECT `start_date`,`end_date`,`start_time`,`end_time`,`slot_type`,`slot_interval`,`total_slots`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day` FROM `event_slots` WHERE `id`='".$slot_id."'";
                $query_sel_result = $con->query($query_sel);
                $slot_previous_data = $query_sel_result->fetch_assoc();
                if($slot_previous_data['start_date'] == $start_date && $slot_previous_data['end_date'] == $end_date && $slot_previous_data['start_time'] == $start_time && $slot_previous_data['end_time'] == $end_time && $slot_previous_data['slot_type'] == $slot_type && $slot_previous_data['slot_interval'] == $slot_interval && $slot_previous_data['total_slots'] == $total_slots && $slot_previous_data['recurring_type'] == $recurring_type && $slot_previous_data['recurring_weeks'] == $recurring_weeks_in && $slot_previous_data['recurring_months_base'] == $recurring_months_base && $slot_previous_data['recurring_month_date'] == $recurring_month_date_in && $slot_previous_data['recurring_month_every'] == $recurring_month_every_in && $slot_previous_data['recurring_month_day'] == $recurring_month_day_in){
                    $query_up = "UPDATE `event_slots` SET `timezone`='" . $timezone . "',`attendee_type`='" . $attendee_type . "',`attendee_limit`='" . $attendee_limit . "',`status`='" . $status . "',`updated`='" . $created . "',`updated_by`='" . $created_by . "' WHERE `id`='".$slot_id."';";
                    $con->query($query_up);
                } else {
                    $query_up = "UPDATE `event_slots` SET `start_date`='" . $start_date . "',`end_date`='" . $end_date . "',`start_time`='" . $start_time . "',`end_time`='" . $end_time . "',`timezone`='" . $timezone . "',`slot_type`='" . $slot_type . "',`slot_interval`='" . $slot_interval . "',`total_slots`='" . $total_slots . "',`attendee_type`='" . $attendee_type . "',`attendee_limit`='" . $attendee_limit . "',`recurring_type`='" . $recurring_type . "',`recurring_weeks`='" . $recurring_weeks_in . "',`recurring_months_base`='" . $recurring_months_base . "',`recurring_month_date`='" . $recurring_month_date_in . "',`recurring_month_every`='" . $recurring_month_every_in . "',`recurring_month_day`='" . $recurring_month_day_in . "',`registration_cut_off`='" . $registration_cut_off . "',`status`='" . $status . "',`updated`='" . $created . "',`updated_by`='" . $created_by . "' WHERE `id`='".$slot_id."';";
                    $con->query($query_up);
                    $query_slot_up = "UPDATE `event_slot_timings` SET `status`='2',`updated`='" . $created . "',`updated_by`='" . $created_by . "' WHERE `event_id`='" . $event_id . "' AND `slot_id`='" . $slot_id . "'";
                    $con->query($query_slot_up);
                    if($slot_type == 0){
                        $query_single_slot = "INSERT INTO `event_slot_timings` (`event_id`,`slot_id`,`start_date`,`end_date`,`interval`,`status`,`created`,`created_by`) VALUES ( '" . $event_id . "', '" . $slot_id . "','" . $start_date . " " . $start_time . "','" . $end_date . " " . $end_time . "','" . $slot_interval . "','" . $status . "','" . date('Y-m-d H:i:s') . "','" . $created_by . "');";
                        $con->query($query_single_slot);
                    } else {
                        if($slot_type == 1) {
                            $slotInterval = $slot_interval;
                            $totalSlots = round(round(abs(strtotime($end_time) - strtotime($start_time)) / 60, 2) / $slot_interval, 2);
                        }
                        if($slot_type == 2) {
                            $slotInterval = round(round(abs(strtotime($end_time) - strtotime($start_time)) / 60, 2) / $total_slots, 2);
                            $totalSlots = $total_slots;
                        }
                        if($slot_type == 1 || $slot_type == 2) {
                            $slot_start = new DateTime(date("Y-m-d", strtotime($start_date)));
                            $slot_end = new DateTime(date("Y-m-d", strtotime("+1 day " . $end_date)));
                            $interval = DateInterval::createFromDateString('1 day');
                            $total_days = new DatePeriod($slot_start, $interval, $slot_end);
                            $daysToInsert = array();
                            if($recurring_months_base == 2){
                                $lastW = false;
                                if(($key = array_search(6, $recurring_month_every)) !== false) {
                                    unset($recurring_month_every[$key]);
                                    $lastW = true;
                                }
                            }
                            foreach ($total_days as $single_day) {
                                if($recurring_type == 1){
                                    $daysToInsert[] = $single_day->format("Y-m-d");
                                } else if($recurring_type == 2) {
                                    if (in_array($single_day->format("N"), $recurring_weeks)) {
                                        $daysToInsert[] = $single_day->format("Y-m-d");
                                    }
                                } else if($recurring_type == 3) {
                                    if($recurring_months_base == 1){
                                        if (in_array($single_day->format("j"), $recurring_month_date)) {
                                            $daysToInsert[] = $single_day->format("Y-m-d");
                                        }
                                    } else if($recurring_months_base == 2){
                                        $currentWeek = $obj->weekOfMonth($single_day->format("Y-m-d"));
                                        $lastWeek = $obj->weekOfMonth($single_day->format("Y-m-t"));
                                        if((in_array($currentWeek, $recurring_month_every) && in_array($single_day->format("N"), $recurring_month_day)) || ($lastW && $currentWeek == $lastWeek && in_array($single_day->format("N"), $recurring_month_day))){
                                            $daysToInsert[] = $single_day->format("Y-m-d");
                                        }
                                    }
                                }
                            }
                            if (!empty($daysToInsert)) {
                                $j = 0;
                                $bulk_initial = "INSERT INTO `event_slot_timings` (`event_id`,`slot_id`,`start_date`,`end_date`,`interval`,`status`,`created`,`created_by`) VALUES ";
                                $bulk_quries = array();
                                foreach ($daysToInsert as $key => $daySingle) {
                                    $sStart = $start_time;
                                    $sEnd = $end_time;
                                    for ($i = 1; $i <= $totalSlots; $i++) {
                                        if ($i != 1) {
                                            $sStart = date("H:i:s", strtotime("+" . $slotInterval . " minutes", strtotime($sStart)));
                                            $sEnd = date("H:i:s", strtotime("+" . $slotInterval . " minutes", strtotime($sStart)));
                                        } else {
                                            $sStart = date("H:i:s", strtotime($sStart));
                                            $sEnd = date("H:i:s", strtotime("+" . $slotInterval . " minutes", strtotime($sStart)));
                                        }
                                        $bulk_quries[] = "( '" . $event_id . "', '" . $slot_id . "','" . $daySingle . " " . $sStart . "','" . $daySingle . " " . $sEnd . "','" . $slotInterval . "','" . $status . "','" . date('Y-m-d H:i:s') . "','" . $created_by . "')";
                                        $j++;
                                        if($j >= 500){
                                            $obj->query_bulk($bulk_initial, $bulk_quries);
                                            $j = 0;
                                            $bulk_quries = array();
                                        }
                                    }
                                }
                                if(!empty($bulk_quries)){
                                    $obj->query_bulk($bulk_initial, $bulk_quries);
                                    $j = 0;
                                    $bulk_quries = array();
                                }
                            }
                        }
                    }
                }
                $result['success'] = 1;
                $result['data'] = 'success';
                $result['error'] = 0;
                $result['error_code'] = NULL;
            } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = $validation_error_code;
            }
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = $validation_error_code;
        }
        $result = json_encode($result);
        echo $result;
}
?>