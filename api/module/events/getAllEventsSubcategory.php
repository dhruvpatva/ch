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
        if (isset($_REQUEST['id'])){
            //$query = "SELECT id, name, created, created_by, status FROM categories WHERE type = '1' AND parent_id = '". $_REQUEST['id'] ."' ";  
            //$query = "SELECT (SELECT name FROM categories WHERE id = c.parent_id) As parent,c.id, name, c.created, c.created_by, c.status FROM categories As c WHERE c.type = '1' AND c.parent_id = '". $_REQUEST['id'] ."' ";
            $query = "SELECT c.id, c.name, c.created, c.created_by, c.status,ca.name As sub, casub.name As parent
                        FROM categories As c 
                        LEFT JOIN categories As ca ON ca.id = c.parent_id
                        LEFT JOIN categories As casub ON casub.id = ca.parent_id
                        WHERE c.type = '1' AND c.parent_id = '". $_REQUEST['id'] ."' ";
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
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = '';
        }
}
$result = json_encode($result);
if (isset($_SESSION['user'])) {
        echo $result;
}
?>
