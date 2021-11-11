<?php
    require_once '../../cmnvalidate.php';
    $bydirect = true;
    if ($bydirect) { 
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['event_id']) && $_REQUEST['event_id'] != "") { 
            $id = $_REQUEST['id'];
            $event_id = $_REQUEST['event_id'];
            $query = "SELECT `id`,`event_id`,`start_date`,`end_date`,`start_time`,`end_time`,`timezone`,`slot_type`,`slot_interval`,`total_slots`,`attendee_type`,`attendee_limit`,`recurring_type`,`recurring_weeks`,`recurring_months_base`,`recurring_month_date`,`recurring_month_every`,`recurring_month_day`,`registration_cut_off`,`status` FROM `event_slots` WHERE `id`='" . $id . "' AND `event_id`='" . $event_id . "'";
            $query_result = $con->query($query);
            if ($query_result->num_rows > 0) {
                $resulted_data = $query_result->fetch_assoc();
                $resulted_data['start_date'] = date('m-d-Y', strtotime($resulted_data['start_date']));
                $resulted_data['end_date'] = date('m-d-Y', strtotime($resulted_data['end_date']));
                $resulted_data['start_time'] = date('h:i A', strtotime($resulted_data['start_time']));
                $resulted_data['end_time'] = date('h:i A', strtotime($resulted_data['end_time']));
                if ($resulted_data['recurring_weeks'] != "") {
                    $recurring_weeks = explode(',', $resulted_data['recurring_weeks']);
                    $resulted_data['recurring_weeks'] = array();
                    foreach ($recurring_weeks as $week) {
                        $resulted_data['recurring_weeks'][$week] = true;
                    }
                }
                if ($resulted_data['recurring_month_date'] != "") {
                    $recurring_month_date = explode(',', $resulted_data['recurring_month_date']);
                    $resulted_data['recurring_month_date'] = array();
                    foreach ($recurring_month_date as $rmdt) {
                        $resulted_data['recurring_month_date'][$rmdt] = true;
                    }
                }
                if ($resulted_data['recurring_month_every'] != "") {
                    $recurring_month_every = explode(',', $resulted_data['recurring_month_every']);
                    $resulted_data['recurring_month_every'] = array();
                    foreach ($recurring_month_every as $rme) {
                        $resulted_data['recurring_month_every'][$rme] = true;
                    }
                }
                if ($resulted_data['recurring_month_day'] != "") {
                    $recurring_month_day = explode(',', $resulted_data['recurring_month_day']);
                    $resulted_data['recurring_month_day'] = array();
                    foreach ($recurring_month_day as $rmd) {
                        $resulted_data['recurring_month_day'][$rmd] = true;
                    }
                }
                $result['success'] = 1;
                $result['data'] = $resulted_data;
                $result['error'] = 0;
                $result['error_code'] = NULL;
            } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = 'No Data Found';
            }
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = 'Required Parameter Are Missing';
        }
    }
    $result = json_encode($result);
    if (isset($_REQUEST['is_mobile_api']) || isset($_SESSION['user'])) {
        echo $result;
    }
?>
