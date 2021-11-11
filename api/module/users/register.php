<?php
require_once '../../cmnvalidate.php';
require_once '../../libs/image/ImageManipulator.php';
$bydirect = true;
$created = date('Y-m-d H:i:s');
if(isset($_REQUEST['is_mobile_api'])){
        if($result['success'] == 1){
             $bydirect = true;
        } else {
             $bydirect = false;
        }
        $params = array();     
        $params['name'] = @$_REQUEST['name'];
        $params['member_id'] = @$_REQUEST['member_id'];
        $params['email'] = @$_REQUEST['email'];
        $params['password'] = @$_REQUEST['password'];
        $params['devicetoken'] = @$_REQUEST['devicetoken'];
        $secretkey = substr(sha1(mt_rand()), 0, 22);  
        $role_id = 1;
}
if ($bydirect){ 
    if(isset($_REQUEST['sso']) && $_REQUEST['sso'] == 1){
        /*  if (isset($params['firstname']) &&  isset($params['lastname']) &&  isset($params['email']) &&  isset($params['devicetoken'])) {
               $validation_flag = 0;
               $validation_error_code = NULL;
               $first_name = $params['firstname'];
               $last_name = $params['lastname'];
               $email = $params['email'];
               $devicetoken = $_REQUEST['devicetoken']; 
               $gender = $_REQUEST['gender'];
               $providertype = $_REQUEST['providertype'];
               $provideruserid = $_REQUEST['provideruserid'];
               $profileimageurl = $_REQUEST['profileimageurl'];
               $api_type = $_REQUEST['api_type']; 
               $status = 1;  
               $profilepic = "image_not_available.png";
               if($profileimageurl != ""){
                      $profilepic = time().".png";   
                      file_put_contents(PROJECT_ROOT_UPLOAD."/users/".$profilepic, file_get_contents($profileimageurl)); 
                      $profileimage = SITE_ROOT."/uploads/users/".$profilepic;
               }
               if (empty($params['firstname']) || empty(str_replace(' ', '', $params['firstname']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'FirstName is required';
               }  else if (!preg_match("/^[a-zA-Z]*$/", $first_name)) {
                   $validation_flag = 1;
                   $validation_error_code = 'FirstName is Invalid';
               } else if (empty($params['lastname']) || empty(str_replace(' ', '', $params['lastname']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'LastName is required';
               } else if (!preg_match("/^[a-zA-Z]*$/", $params['lastname'])) {
                   $validation_flag = 1;
                   $validation_error_code = 'LastName is Invalid';
               } else if (empty($params['email']) || empty(str_replace(' ', '', $params['email']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is required';
               } else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is Invalid';
               } 

               if ($validation_flag == 0) {
                   $query = "SELECT id,role_id, email, password, firstname, lastname, profile_image, status, api_secret,devicetoken,devicetype,ssofbuserid,ssogoogleuserid,ssoprovidertype FROM `users` WHERE `email` = '$email'";
                   $query_result = $con->query($query);
                   if ($query_result->num_rows == 1) {
                        $userdata = $query_result->fetch_assoc();
                        $user_id = $userdata['id'];
                       
                         if($providertype == "Facebook" && $provideruserid == $userdata['ssofbuserid']){
                              $resulted_data = array(
                                  'user_id' => "$user_id",
                                  'firstname' => $first_name,
                                  'lastname' => $last_name,
                                  'email' => $email,
                                  'newgeneratedapi' => $secretkey,
                                  'role_id' => $role_id,
                                  'profileimage' => $profileimage,
                                  'providertype' => $providertype,
                                  'provideruserid' => $provideruserid
                              );
                         } else if($providertype == "Google" && $provideruserid == $userdata['ssogoogleuserid']){
                              $resulted_data = array(
                                  'user_id' => "$user_id",
                                  'firstname' => $first_name,
                                  'lastname' => $last_name,
                                  'email' => $email,
                                  'newgeneratedapi' => $secretkey,
                                  'role_id' => $role_id,
                                  'profileimage' => $profileimage,
                                  'providertype' => $providertype,
                                  'provideruserid' => $provideruserid
                              );
                         } else {
                              if($providertype == "Facebook"){
                                   $setparam = "ssofbuserid='".$provideruserid."'";
                              } else if($providertype == "Google"){
                                   $setparam = "ssogoogleuserid='".$provideruserid."'"; 
                              }   
                             
                              $queryupdate = "Update users set $setparam where id='$user_id'";
                              $query_result = $con->query($queryupdate);
                              $resulted_data = array(
                                   'user_id' => "$user_id",
                                   'firstname' => $first_name,
                                   'lastname' => $last_name,
                                   'email' => $email,
                                   'newgeneratedapi' => $secretkey,
                                   'role_id' => $role_id,
                                   'profileimage' => $profileimage,
                                   'providertype' => $providertype,
                                   'provideruserid' => $provideruserid
                               );
                         }
                         $result['success'] = 1;
                         $result['data'] = $resulted_data;
                         $result['error'] = 0;
                         $result['error_code'] = NULL;
                   } else {
                        if($providertype == "Facebook"){
                              $field = "ssofbuserid";
                         } else if($providertype == "Google"){
                              $field = "ssogoogleuserid";
                         } 
                         $query = "INSERT INTO `users` (role_id, email, firstname, lastname, profile_image, ssoprovidertype,$field, status, api_secret,devicetoken,devicetype) VALUES ('$role_id','$email', '$first_name', '$last_name', '$profilepic', '$providertype', '$provideruserid', '$status', '$secretkey', '$devicetoken', '$api_type')";
                         $query_result = $con->query($query);
                         if($query_result){
                              $user_id = $con->insert_id;
                              $profileimage = SITE_ROOT."/uploads/users/".$profilepic;
                              $resulted_data = array(
                                  'user_id' => "$user_id",
                                  'firstname' => $first_name,
                                  'lastname' => $last_name,
                                  'email' => $email,
                                  'newgeneratedapi' => $secretkey,
                                  'role_id' => $role_id,
                                  'profileimage' => $profileimage,
                                  'providertype' => $providertype,
                                  'provideruserid' => $provideruserid
                              );
                              $result['success'] = 1;
                              $result['data'] = $resulted_data;
                              $result['error'] = 0;
                              $result['error_code'] = NULL;
                         } else {
                              $result['success'] = 0;
                              $result['data'] = NULL;
                              $result['error'] = 1;
                              $result['error_code'] = "Error in query";
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
           } */
    } else {
         if (isset($params['name']) && isset($params['member_id']) && isset($params['email']) && isset($params['password']) && isset($params['devicetoken'])) {
               $validation_flag = 0;
               $validation_error_code = NULL;
               $name = $params['name'];
               $member_id = $params['member_id'];
               $email = $params['email'];
               $password = $obj->encryptIt($params['password']);
               $devicetoken = $_REQUEST['devicetoken']; 
               $api_type = $_REQUEST['api_type']; 
               $status = 1;  
               $profilepic = "no-image.png";
               
               if(isset($_FILES['profilepic'])){ 
                      $file_name = time().$_FILES['profilepic']['name'];
                      $file_size =$_FILES['profilepic']['size'];
                      $file_tmp =$_FILES['profilepic']['tmp_name'];
                      $file_type=$_FILES['profilepic']['type'];                    
                      $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));   
                      if ( !in_array($ext, array('jpg','jpeg','png','gif','bmp')) ) {
                          $response = 'Invalid file extension.';
                          $validation_flag = 1;
                          $validation_error_code = 'Invalid file extension';
                      } else {
                           $manipulator = new ImageManipulator($_FILES['profilepic']['tmp_name']);
                           // resizing to 200x200
                             $newImage = $manipulator->resample(400, 400);
                           $res = $manipulator->save(PROJECT_ROOT_UPLOAD."/users/".$file_name);
                           $profilepic = $file_name;
                      }
               }
               /*
               if (empty($params['name']) || empty(str_replace(' ', '', $params['name']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'Name is required';
               }  else if (!preg_match("/^[a-zA-Z]*$/", $name)) {
                   $validation_flag = 1;
                   $validation_error_code = 'Name is Invalid';
               } else if (empty($params['member_id']) || empty(str_replace(' ', '', $params['member_id']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'Member ID is required';
               } else if (empty($params['email']) || empty(str_replace(' ', '', $params['email']))) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is required';
               } else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is Invalid';
               } else if (empty($_REQUEST['password'])  && empty($_REQUEST['cpassword'])) {
                   $validation_flag = 1;
                   $validation_error_code = 'Password Field Missing';
               } else if (@$_REQUEST['password'] != @$_REQUEST['cpassword']) {
                   $validation_flag = 1;
                   $validation_error_code = 'Password Not Match';
               } */
               
               if (empty($params['name'])) {
                   $validation_flag = 1;
                   $validation_error_code = 'Name is required';
               }  else if (!preg_match("/^[a-zA-Z]*$/", $name)) {
                   $validation_flag = 1;
                   $validation_error_code = 'Name is Invalid';
               } else if (empty($params['member_id'])) {
                   $validation_flag = 1;
                   $validation_error_code = 'Member ID is required';
               } else if (empty($params['email']) ) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is required';
               } else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
                   $validation_flag = 1;
                   $validation_error_code = 'Email is Invalid';
               } else if (empty($_REQUEST['password'])  && empty($_REQUEST['cpassword'])) {
                   $validation_flag = 1;
                   $validation_error_code = 'Password Field Missing';
               } else if (@$_REQUEST['password'] != @$_REQUEST['cpassword']) {
                   $validation_flag = 1;
                   $validation_error_code = 'Password Not Match';
               } 

               if ($validation_flag == 0) {
                   $query = "SELECT `id` FROM `users` WHERE `email` = '$email'";
                   $query_result = $con->query($query);
                   if ($query_result->num_rows == 0) {
                               $query = "INSERT INTO `users` (role_id, email, member_id, name, password, profile_image, status, api_secret, devicetoken, devicetype, created) VALUES ('$role_id','$email', '$member_id', '$name', '$password', '$profilepic', '$status', '$secretkey', '$devicetoken', '$api_type', '$created')";
                               $query_result = $con->query($query);
                               $user_id = $con->insert_id;
                               $profileimage = SITE_ROOT."/uploads/users/".$profilepic;
                               $resulted_data = array(
                                   'user_id' => "$user_id",
                                   'name' => $name,
                                   'email' => $email,
                                   'newgeneratedapi' => $secretkey,
                                   'role_id' => $role_id,   
                                   'profileimage' => $profileimage

                               );
                               $result['success'] = 1;
                               $result['data'] = $resulted_data;
                               $result['error'] = 0;
                               $result['error_code'] = NULL;
                   } else {
                       $result['success'] = 0;
                       $result['data'] = NULL;
                       $result['error'] = 1;
                       $result['error_code'] = 'Email Already Registered';
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
}
$result = json_encode($result);
echo $result;
?>