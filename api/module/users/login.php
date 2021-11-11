<?php
require_once '../../cmnvalidate.php';
$params = json_decode(file_get_contents('php://input'),true);
$params = $params['user'];
$bydirect = true;
if(isset($_REQUEST['is_mobile_api'])){
     if($result['success'] == 1){
          $bydirect = true;
     } else {
          $bydirect = false;
     }
$params = array();     
$params['username'] = $_REQUEST['username'];
$params['password'] = $_REQUEST['password'];
$params['devicetoken'] = $_REQUEST['devicetoken'];
}
if ($bydirect) {
    $user_name = $password = NULL;
    if (isset($params['username']) && isset($params['password'])) {
        $user_name = $params['username'];
        $password = $obj->encryptIt($params['password']);
        $apitype = @$params['api_type'];
        $validation_flag = 0;
        $devicetoken = "";
        if(isset($params['devicetoken']) && $params['devicetoken'] != ""){
          $devicetoken =  $params['devicetoken']; 
        }
        if (empty($params['username'])) {
            $validation_flag = 1;
            $validation_error_code = 'Email is required';
        }else if (empty($params['password'])) {
            $validation_flag = 1;
            $validation_error_code = 'Password is required';
        }
        
        if ($validation_flag == 0) {
            $query = "SELECT id AS user_id,role_id,email,name,password,profile_image,api_secret,api_created_date,status FROM `users` WHERE `name` = '$user_name' or  `email` = '$user_name'";
            $query_result = $con->query($query);
            if ($query_result->num_rows != 0) {
                $user_details = $query_result->fetch_assoc(); 
                if ($user_details['status'] == 1 && ($user_details['role_id'] == 0 || $user_details['role_id'] == 1)) {
                    if ($password == $user_details['password']) {
                        if($user_details['role_id'] == 0){
                             $userRole = 'admin';
                        }else if($user_details['role_id'] == 1){
                             $userRole = 'EndUser';
                        } else {
                             $userRole = '';
                        }
                        
                        $image = SITE_ROOT."/uploads/users/profile_user.png";
                        if($user_details['profile_image'] != "" && $user_details['profile_image'] != 'no-image.png'){
                              $image = SITE_ROOT."/uploads/users/".$user_details['profile_image'];
                        } 
                        
                        //$customer = Braintree_Customer::find($user_details['braintree_id']);
                        $is_payment = "0";
                        // Check payment method is added or not + hold 50$ for payment
                        /*if(!empty($customer->paymentMethods)){
                          $is_payment = "1";
                        }*/
                        $resulted_data = array(
                            'userRole' => $userRole,
                            'user_id' => $user_details['user_id'],
                            'role_id'=>$user_details['role_id'],
                            'name' => $user_details['name'],
                            'profilepic' => @$image,
                        );
                        $_SESSION['user'] = $resulted_data;
                        
                        $api_created_date =  $user_details['api_created_date'];
                        // Code for api secret userwise
                        //if (time() >= strtotime($api_created_date) + 86400) {
                             //$secretkey = substr(sha1(mt_rand()), 0, 22);
                             $secretkey =  $user_details['api_secret']; 
                             $api_date = date('Y-m-d H:i:s');
                             //$queryupdate = "Update `users` set api_secret='$secretkey',api_created_date='$api_date',devicetoken='$devicetoken' WHERE `id` = '".$user_details['user_id']."'";
                             $queryupdate = "Update `users` set devicetoken='$devicetoken',devicetype='$apitype' WHERE `id` = '".$user_details['user_id']."'";
                             //$query_result = $con->query($queryupdate);
                             $resulted_data['keyexpired'] = 1;
                             $resulted_data['newgeneratedapi'] = $secretkey;
                             $resulted_data['devicetoken'] = $devicetoken;
                             
                        //}
                        $result['success'] = 1;
                        $result['data'] = $resulted_data;
                        $result['error'] = 0;
                        $result['error_code'] = NULL;
                    } else {
                        $result['success'] = 0;
                        $result['data'] = NULL;
                        $result['error'] = 1;
                        $result['error_code'] = 'Password not match';
                    }
                } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = 'User is blocked or inactive';
                }
            } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = 'Invalid username or passwords';   
            }    
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = @$validation_error_code;
        }
    } else {
        $result['success'] = 0;
        $result['data'] = NULL;
        $result['error'] = 1;
        $result['error_code'] = 'Enter any username and password.';
    }
}
$result = json_encode($result);
echo $result; exit;
?>