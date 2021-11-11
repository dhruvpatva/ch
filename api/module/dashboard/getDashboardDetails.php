<?php
    require_once '../../cmnvalidate.php';
    
    $result = array();
    $total_enduser = $obj->getOneValueOfChoice('COUNT(`id`) AS counter', "`users`", "`role_id`='1'", 'counter');
    if ($total_enduser == NULL) {
        $total_enduser = 0;
    }
    
    $result = array(
        'total_enduser' => $total_enduser
    );
    
    $result = json_encode($result);
    if (isset($_SESSION['user'])) {
         echo $result;
    }
?>
