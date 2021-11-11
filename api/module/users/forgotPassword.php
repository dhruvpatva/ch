<?php
require_once '../../cmnvalidate.php';
$bydirect = true;
if (isset($_REQUEST['is_mobile_api'])) {
        if ($result['success'] == 1) {
                $bydirect = true;
        } else {
                $bydirect = false;
        }
        /*$params = array();
        if (isset($_REQUEST['userid'])) {
          $userid = $_REQUEST['userid'];
        }*/
}
if ($bydirect) {
        if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])){
             $validation_flag = 0;
             $validation_error_code = NULL;
             if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
                    $validation_flag = 1;
                    $validation_error_code = 'Email is Invalid';
             }
             if ($validation_flag == 0) {
                    $query = "SELECT * FROM `users` WHERE email = '". $_REQUEST['email'] ."' ";
                    $query_result = $con->query($query);
                    if ($query_result->num_rows != 0) {
                        $row = $query_result->fetch_assoc();
                        if($row['status'] == 1){
                            $result['success'] = 1;
                            $result['data'] = 'success';
                            $result['error'] = 0;
                            $result['error_code'] = NULL;
                            
                            // Send email
                            /*$name = $first_name." ".$last_name;
                            $content =  file_get_contents(SITE_ROOT.'/tpl/email/welcomespe.html'); 
                            $content = str_replace('{{name}}', $name, $content);
                            $content = str_replace('{{siteurl}}', SITE_ROOT, $content);
                            $content = str_replace('{{email}}', $email, $content);
                            $content = str_replace('{{password}}', $password, $content);
                            $sendemail = $obj->SendEmail($obj->from_admin_email,$obj->from_admin_name,$email,$name,'Welcome to FitFinder',$content,null);
                            if($sendemail == "success"){
                                 $result['email_sent'] = 1;
                            } else {
                                 $result['email_sent'] = 0;
                            }*/
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
                       $result['error_code'] = 'Email Not Exists';
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
            $result['error_code'] = 'Email is required';
        }
}
$result = json_encode($result);
echo $result; exit;
?>