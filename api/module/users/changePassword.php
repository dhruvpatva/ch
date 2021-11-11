<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$userid = "";
if (isset($_SESSION['user'])){
    $userid = $_SESSION['user']['user_id'];
}
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
        if (isset($_REQUEST['oldpassword']) && isset($_REQUEST['newpassword']) && isset($_REQUEST['retypepassword'])){ 
                $validation_flag = 0;
                $validation_error_code = NULL;
                if (empty($_REQUEST['oldpassword'])){
                        $validation_flag = 1;
                        $validation_error_code = 'Current password is required';
                }
                if (empty($_REQUEST['newpassword'])) {
                        $validation_flag = 1;
                        $validation_error_code = 'New password is required';
                }
                if (empty($_REQUEST['retypepassword'])) {
                        $validation_flag = 1;
                        $validation_error_code = 'Re-type password is required';
                } 
                if ($validation_flag == 0){ 
                        $oldpassword = $obj->encryptIt($_REQUEST['oldpassword']); 
                        $query = "SELECT password FROM users where id = '$userid' AND password = '". $oldpassword ."' ";
                        $query_result = $con->query($query);
                        if($query_result->num_rows != 0){
                            if (@$_REQUEST['newpassword'] != @$_REQUEST['retypepassword']) {
                                    $result['success'] = 0;
                                    $result['data'] = NULL;
                                    $result['error'] = 1;
                                    $result['error_code'] = 'Password Not Match';
                            } else {
                                    $newpassword = $obj->encryptIt($_REQUEST['newpassword']);
                                    $query = "UPDATE `users` SET `password` = '$newpassword' WHERE id = '$userid' ";
                                    $query_result = $con->query($query);
                                    $result['success'] = 1;
                                    $result['data'] = 'success';
                                    $result['error'] = 0;
                                    $result['error_code'] = NULL;
                            }
                        } else {
                            $result['success'] = 0;
                            $result['data'] = NULL;
                            $result['error'] = 1;
                            $result['error_code'] = 'Please enter correct current password';
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
}
$result = json_encode($result);
if(isset($_SESSION['user'])){
        echo $result;
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>
