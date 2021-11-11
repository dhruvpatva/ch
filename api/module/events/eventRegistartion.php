<?php

require_once '../../cmnvalidate.php';
if (isset($_REQUEST['is_mobile_api'])) {
    if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['slot_id']) && $_REQUEST['slot_id'] != "" && isset($_REQUEST['event_id']) && $_REQUEST['event_id'] != "") {
        $id = $_REQUEST['id'];
        $userid = $_REQUEST['userid'];
        $slot_id = $_REQUEST['slot_id'];
        $event_id = $_REQUEST['event_id'];
        $status = 1;
        if (isset($_REQUEST['status'])) {
            $status = $_REQUEST['status'];
        }
        $validation_flag = 0;
        $validation_error_code = NULL;
        if ($validation_flag == 0) {
            $query = "SELECT id FROM event_user_bookings WHERE user_id='" . $userid . "' AND event_id='" . $event_id . "' AND slot_id='" . $slot_id . "' AND slot_time_id='" . $id . "' AND status='1'";
            $query_result = $con->query($query);
            if ($query_result->num_rows == 0) {
                $query = "INSERT INTO event_user_bookings (event_id, slot_id, slot_time_id, user_id, attend_status, status, created, created_by) VALUES ('" . $event_id . "', '" . $slot_id . "', '" . $id . "', '" . $userid . "', '0', '" . $status . "', '" . date("Y-m-d H:i:s") . "', '" . $userid . "')";
                $con->query($query);
                
                $result['success'] = 1;
                $result['data'] = 'success';
                $result['error'] = 0;
                $result['error_code'] = NULL;
            } else {
                if($status == 0){
                    $eventData = $query_result->fetch_assoc();
                    $cBookId = $eventData['id'];
                    $query = "UPDATE event_user_bookings SET status=0 WHERE id='".$cBookId."'";
                    $con->query($query);
                    
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
                } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = 'Class Booking Already Exists For This User';
                }
            }
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
        $result['error_code'] = 'Required Parameter Are Missing';
    }
    $result = json_encode($result);
    echo $result;
}
?>
