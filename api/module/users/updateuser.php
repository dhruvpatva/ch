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
        // Update Gender    
        if (isset($_REQUEST['updategender'])) {
                if (isset($_REQUEST['gender']) && $_REQUEST['userid']) {
                        $gender = $_REQUEST['gender'];
                        $queryupdate = "Update `users` set gender='$gender' WHERE `id` = '" . $userid . "'";
                        $query_result = $con->query($queryupdate);
                        $result['success'] = 1;
                        $result['data'] = 'success';
                        $result['error'] = 0;
                        $result['error_code'] = NULL;
                } else {
                        $result['success'] = 0;
                        $result['data'] = NULL;
                        $result['error'] = 1;
                        $result['error_code'] = 'Required Parameter Are Missing';
                }
        } else if (isset($_REQUEST['updatedob'])) { //Update Birthdate
                if (isset($_REQUEST['dob']) && $_REQUEST['userid']) {
                        $dob = date('Y-m-d', strtotime($_REQUEST['dob']));
                        $queryupdate = "Update `users` set dob='$dob' WHERE `id` = '" . $userid . "'";
                        $query_result = $con->query($queryupdate);
                        $result['success'] = 1;
                        $result['data'] = 'success';
                        $result['error'] = 0;
                        $result['error_code'] = NULL;
                } else {
                        $result['success'] = 0;
                        $result['data'] = NULL;
                        $result['error'] = 1;
                        $result['error_code'] = 'Required Parameter Are Missing';
                }
        } else if (isset($_REQUEST['updategoals'])) {  //Update Goals
                if (isset($_REQUEST['goals']) && $_REQUEST['userid']) {
                        foreach ($_REQUEST['goals'] as $key => $value) {
                                $query = "SELECT `id` FROM `users_goals` WHERE `user_id` = '$userid' And goal_id = '$value'";
                                $query_result = $con->query($query);
                                if ($query_result->num_rows == 0) {
                                        $query = "INSERT INTO `users_goals` (user_id, goal_id, status) VALUES ( $userid, $value, '1')";
                                        $query_result = $con->query($query);
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
                        $result['error_code'] = 'Required Parameter Are Missing';
                }
        } else if (isset($_REQUEST['updateactivelevel'])) { //Update Active Level
                if (isset($_REQUEST['activelevel']) && $_REQUEST['userid']) {
                        $activelevel = $_REQUEST['activelevel'];
                        $queryupdate = "Update `users` set active_level='$activelevel' WHERE `id` = '" . $userid . "'";
                        $query_result = $con->query($queryupdate);
                        $result['success'] = 1;
                        $result['data'] = 'success';
                        $result['error'] = 0;
                        $result['error_code'] = NULL;
                } else {
                        $result['success'] = 0;
                        $result['data'] = NULL;
                        $result['error'] = 1;
                        $result['error_code'] = 'Required Parameter Are Missing';
                }
        } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = 'Required Parameter Are Missing';
        }
}
$result = json_encode($result);
echo $result;
?>