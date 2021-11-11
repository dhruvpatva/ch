<?php
require_once '../../cmnvalidate.php';
if (isset($_SESSION['user'])) {
        // Get all user roles
        $query = "SELECT id,title  FROM `users_roles` WHERE status = '1'";
        $query_result = $con->query($query);
        $resulted_data = array();
        while ($rows = $query_result->fetch_assoc()) {
                 $resulted_data[] = $rows;
        }
        $result['success'] = 1;
        $result['data'] = $resulted_data;
        $result['error'] = 0;
        $result['error_code'] = NULL;
        $result = json_encode($result);
        echo $result;
}
?>