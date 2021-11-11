<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
$userid = "";
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['user_id'];
}
$checkaccess = true;
if (isset($_REQUEST['is_mobile_api'])) {
        if ($result['success'] == 1) {
                $bydirect = true;
        } else {
                $bydirect = false;
        }
        $params = array();
        $userid = $_REQUEST['userid'];
        $checkaccess = false;
}
if ($bydirect) { 
     if (isset($userid) && isset($_REQUEST['name']) && isset($_REQUEST['email'])) {
        $email = $_REQUEST['email'];
        // Get user detail
        $query = "SELECT id FROM users where id!=$userid and email='$email'";
        $query_result = $con->query($query);
        if($query_result->num_rows == 0){
                $validation_flag = 0;
                $validation_error_code = NULL;
                $updatevalues = "";
                if(isset($_REQUEST['company_name']) && $_REQUEST['company_name'] != ""){
                     $updatevalues .= "company_name='".$_REQUEST['company_name']."' ,";
                }
                if(isset($_REQUEST['name']) && $_REQUEST['name'] != ""){
                     $updatevalues .= "name='".$_REQUEST['name']."' ,";
                }
                if(isset($_REQUEST['email']) && $_REQUEST['email'] != ""){
                     $updatevalues .= "email='".$_REQUEST['email']."' ,";
                }
                if(isset($_REQUEST['address']) && $_REQUEST['address'] != ""){
                     $updatevalues .= "address='".$_REQUEST['address']."' ,";
                }
                if(isset($_REQUEST['city']) && $_REQUEST['city'] != ""){
                     $updatevalues .= "city='".$_REQUEST['city']."' ,";
                }
                if(isset($_REQUEST['state']) && $_REQUEST['state'] != ""){
                     $updatevalues .= "state='".$_REQUEST['state']."' ,";
                }
                if(isset($_REQUEST['zipcode']) && $_REQUEST['zipcode'] != ""){
                     $updatevalues .= "zipcode='".$_REQUEST['zipcode']."' ,";
                }
                
                $updatevalues = rtrim($updatevalues,",");
                if (empty($email)) {
                //if (empty($email) || empty(str_replace(' ', '', $email))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Email is required';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validation_flag = 1;
                    $validation_error_code = 'Invalid email address';
                }
                if ($validation_flag == 0) {
                    $query = "UPDATE `users` SET $updatevalues WHERE id = '$userid' ";
                    $query_result = $con->query($query);
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
               $result['error_code'] = 'Email Already Exists';     
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