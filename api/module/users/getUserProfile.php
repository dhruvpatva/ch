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
        // Get user detail
        $query = "SELECT * FROM users where id=$userid ";
        $query_result = $con->query($query);
        if($query_result->num_rows > 0){
                $resulted_data = $query_result->fetch_assoc();
                //$profileimage = SITE_ROOT."/uploads/users/plus_sign.png";
                $profileimage = "";
                if($resulted_data['profile_image'] != "" && $resulted_data['profile_image'] != 'no-image.png'){
                     $profileimage = SITE_ROOT."/uploads/users/".$resulted_data['profile_image'];
                }
                $resulted_data['profile_image'] = $profileimage;
                $result['success'] = 1;
                $result['data'] = $resulted_data;
                $result['error'] = 0;
                $result['error_code'] = NULL;
        } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = "User Not Found";
        }
}
$result = json_encode($result);
if(isset($_SESSION['user'])){
        echo $result;
}else if(isset($_REQUEST['is_mobile_api'])){
        echo $result;
}
?>
