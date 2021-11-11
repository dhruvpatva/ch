<?php
require_once '../../cmnvalidate.php';
$result = array();
if (isset($_SESSION['user'])) {
        $resulted_data = $_SESSION['user'];
        $result['success'] = 1;
        $result['data'] = $resulted_data;
        $result['error'] = 0;
        $result['error_code'] = NULL;
} 
$result = json_encode($result);
echo $result; exit;
?>