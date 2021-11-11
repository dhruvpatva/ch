<?php
require_once '../../cmnvalidate.php';
$created = date('Y-m-d H:i:s');
if (isset($_REQUEST['firstname']) && isset($_REQUEST['lastname']) && isset($_REQUEST['email']) && isset($_REQUEST['mobile']) && isset($_REQUEST['company']) && isset($_REQUEST['no_of_emps'])) {
        $validation_flag = 0;
        $validation_error_code = NULL;
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'];
        $mobile = $_REQUEST['mobile'];
        $email = $_REQUEST['email'];
        $company = $_REQUEST['company'];
        $no_of_emp = $_REQUEST['no_of_emps'];
        $secretkey = substr(sha1(mt_rand()), 0, 22);
        $password = $obj->encryptIt($secretkey);
        $status = 1;
        $role_id = 1;
        
        if (empty($_REQUEST['firstname'])) {
            $validation_flag = 1;
            $validation_error_code = 'Firstname is required';
        } else if (!preg_match("/^[a-zA-Z]*$/", $firstname)) {
            $validation_flag = 1;
            $validation_error_code = 'Firstname is Invalid';
        } else if (empty($_REQUEST['lastname'])) {
            $validation_flag = 1;
            $validation_error_code = 'Lastname is required';
        } else if (!preg_match("/^[a-zA-Z]*$/", $lastname)) {
            $validation_flag = 1;
            $validation_error_code = 'Lastname is Invalid';
        } else if (empty($_REQUEST['email'])) {
            $validation_flag = 1;
            $validation_error_code = 'Email is required';
        } else if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
            $validation_flag = 1;
            $validation_error_code = 'Email is Invalid';
        } else if (empty($_REQUEST['mobile'])) {
            $validation_flag = 1;
            $validation_error_code = 'Mobile number is required';
        } else if (!preg_match("/^[0-9]{10}+$/", $mobile)) {
            $validation_flag = 1;
            $validation_error_code = 'Mobile number is Invalid';
        } else if (empty($_REQUEST['company'])) {
            $validation_flag = 1;
            $validation_error_code = 'Company name is required';
        }

        if ($validation_flag == 0) {
            $query = "SELECT `id` FROM `users` WHERE `email` = '$email'";
            $query_result = $con->query($query);
            if ($query_result->num_rows == 0) {
                $query = "INSERT INTO `users` (role_id, firstname, lastname, email, mobile, password, company_name, no_of_employees, created, status) 
                                            VALUES ('$role_id','$firstname','$lastname','$email', '$mobile', '$password', '$company', '$no_of_emp', '$created', '$status')";
                $query_result = $con->query($query);
                if ($query_result) {
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['error'] = 0;
                    $result['error_code'] = NULL;

                    // Send email
                    /* $name = $first_name." ".$last_name;
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
                      } */
                } else {
                    $result['success'] = 0;
                    $result['data'] = NULL;
                    $result['error'] = 1;
                    $result['error_code'] = "Error in query";
                }
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
$result = json_encode($result);
echo $result;
?>
