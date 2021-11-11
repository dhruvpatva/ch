<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if (isset($_REQUEST['is_mobile_api'])) {
             if ($result['success'] == 1) {
                     $bydirect = true;
             } else {
                     $bydirect = false;
             }
             $params = array();
             $userid = $_REQUEST['userid'];
    }
    if ($bydirect) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['date']) && $_REQUEST['date'] != "" ) {
            $id = $_REQUEST['id'];
            $date = $_REQUEST['date'];
            $type = 1;
            if(isset($_REQUEST['type'])){
                $type = $_REQUEST['type'];
            }
            $resulted_data = array();
            $slot_type = 0;
            $recurring_type = 0;
            $recurring_weeks = array();
            $recurring_months_base = 1;
            $recurring_month_date = array();
            $recurring_month_every = array();
            $recurring_month_day = array();
            $slot_time_sql = "SELECT `id`, `slot_id`, `start_date`, `end_date`, `interval`, COUNT(`id`) as total_time_slots FROM `event_slot_timings` WHERE `event_id`='".$id."' AND `start_date` >= '".date('Y-m-d H:i:s')."' AND DATE_FORMAT(`start_date`,'%m-%d-%Y') = '".$date."' AND `status`='1' GROUP BY `slot_id`,DATE(`start_date`) ORDER BY `start_date` ASC";
            $slot_time_sql_result = $con->query($slot_time_sql);
            $dates = array();
            $total_spots = 0;
            $interval = 0;
            while ($slot_time = $slot_time_sql_result->fetch_assoc()) {
                if(empty($dates)){
                    $slot_sql = "SELECT `id`,`start_date`,`end_date`,`start_time`,`end_time`,`timezone`,`slot_type`,`attendee_type`,`attendee_limit`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day`,`registration_cut_off` FROM `event_slots` WHERE `id`='".$slot_time['slot_id']."'";
                    $slot_sql_result = $con->query($slot_sql);
                    $slot = $slot_sql_result->fetch_assoc();
                    $dates[] = date('m-d-Y', strtotime($slot_time['start_date']));
                    $interval = $slot_time['interval'];
                    if($slot['attendee_type'] == '0'){
                        $total_spots = -1;
                    } else{
                        if($type == 1){
                            $total_spots = $slot['attendee_limit'];
                        } else {
                            $total_spots = $slot_time['total_time_slots'] * $slot['attendee_limit'];
                        }
                    }
                    $slot_type = $slot['slot_type'];
                    if($slot_type == 1 || $slot_type == 2) {
                        $recurring_type = $slot['recurring_type'];
                        if($recurring_type == 2 || $recurring_type == 3) {
                            if($slot['recurring_type'] == 2){
                                $recurring_weeks = explode(",", $slot['recurring_weeks']);
                            } else {
                                $recurring_months_base = $slot['recurring_months_base'];
                                if($recurring_months_base == 1){
                                    $recurring_month_date = explode(",", $slot['recurring_month_date']);
                                } else {
                                    $recurring_month_every = explode(",", $slot['recurring_month_every']);
                                    $recurring_month_day = explode(",", $slot['recurring_month_day']);
                                }
                            }
                        } else {
                            $recurring_weeks = array("1","2","3","4","5","6","7");
                        }
                    }
                } else if(in_array(date('m-d-Y', strtotime($slot_time['start_date'])), $dates)){
                    if(array_search(date('m-d-Y', strtotime($slot_time['start_date'])), $dates) == 0) {
                        if($type == 2){
                            $slot_sql = "SELECT `id`,`start_date`,`end_date`,`start_time`,`end_time`,`timezone`,`slot_type`,`slot_interval`,`attendee_type`,`attendee_limit`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day`,`registration_cut_off` FROM `event_slots` WHERE `id`='".$slot_time['slot_id']."'";
                            $slot_sql_result = $con->query($slot_sql);
                            $slot = $slot_sql_result->fetch_assoc();
                            if($slot['attendee_type'] == '0'){
                                $total_spots = -1;
                            } else{
                                $total_spots = $total_spots + ($slot_time['total_time_slots'] * $slot['attendee_limit']);
                            }
                        }
                    }
                }
            }
            $resulted_data['total_spots'] = $total_spots;
            $filled_spots = 0;
            $time_available = array();
            if(!empty($dates)){
                $get_slots = "SELECT `id`, `start_date`, `end_date`, (SELECT COUNT(event_user_bookings.`id`) FROM event_user_bookings WHERE event_user_bookings.`slot_time_id`=`event_slot_timings`.`id` GROUP BY event_user_bookings.`slot_time_id`) as `user_booking` FROM `event_slot_timings` WHERE `event_id`='".$id."' AND DATE_FORMAT(`start_date`,'%m-%d-%Y') = '".$dates[0]."' AND `status`='1'";
                $get_slots_result = $con->query($get_slots);
                $i = 1;
                $slot_time_id = array();
                while ($slot_book = $get_slots_result->fetch_assoc()) {
                    if($type == 1 && $i == 1){
                        $filled_spots = $slot_book['user_booking'];
                    } else {
                        $filled_spots = $filled_spots + $slot_book['user_booking'];
                    }
                    $time_available[] = date('H:i', strtotime($slot_book['start_date']))." - ".date('H:i', strtotime($slot_book['end_date']));
                    $i++;
                    $slot_time_id[] = $slot_book['id'];
                }
                $query_att = "SELECT event_id, slot_id, slot_time_id FROM event_user_bookings WHERE event_id='" . $id . "' AND slot_time_id IN ('" . implode("','", $slot_time_id) . "') AND user_id='" . $userid . "' AND status='1'";
                $query_att_result = $con->query($query_att);
                if ($query_att_result->num_rows == 0) {
                    $resulted_data['is_join'] = 0;
                    $resulted_data['booking_details'] = array("event_id" => "", "slot_id" => "", "slot_time_id" => "");
                } else {
                    $query_att_data = $query_att_result->fetch_assoc();
                    $resulted_data['is_join'] = 1;
                    $resulted_data['booking_details'] = $query_att_data;
                }
            } else {
                $resulted_data['is_join'] = 0;
                $resulted_data['booking_details'] = array("event_id" => "", "slot_id" => "", "slot_time_id" => "");
            }
            $resulted_data['filled_spots'] = $filled_spots;
            $resulted_data['time_available'] = $time_available;
            $resulted_data['interval'] = $interval;
            $resulted_data['slot_type'] = $slot_type;
            $resulted_data['recurring_type'] = $recurring_type;
            $resulted_data['recurring_weeks'] = $recurring_weeks;
            $resulted_data['recurring_months_base'] = $recurring_months_base;
            $resulted_data['recurring_month_date'] = $recurring_month_date;
            $resulted_data['recurring_month_every'] = $recurring_month_every;
            $resulted_data['recurring_month_day'] = $recurring_month_day;
            
            $result['success'] = 1;
            $result['data'] = $resulted_data;
            $result['error'] = 0;
            $result['error_code'] = NULL;
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = 'Required Parameter Are Missing';
        }
    }
    $result = json_encode($result);
    if (isset($_REQUEST['is_mobile_api'])) {
        echo $result;
    }
?>
