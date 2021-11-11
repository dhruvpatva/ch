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
        if (isset($_REQUEST['userid'])) {
          $userid = $_REQUEST['userid'];
        }
}
if ($bydirect) {
        $query = "SELECT id, name, created, created_by, status FROM categories WHERE type='1' AND parent_id = '0' ";
        $query_result = $con->query($query);
        $resulted_data = array();
        while ($rows = $query_result->fetch_assoc()) {
                 if ($rows['created_by'] == $_SESSION['user']['user_id'] || $_SESSION['user']['role_id'] == '0'){
                    $rows['is_delete'] = 1;
                 } else {
                    $rows['is_delete'] = "";
                 }
                 $resulted_data[] = $rows;
        }
        $result['success'] = 1;
        $result['data'] = $resulted_data;
        $result['error'] = 0;
        $result['error_code'] = NULL;
}
$result = json_encode($result);
if (isset($_SESSION['user'])) {
        echo $result;
}
?>
