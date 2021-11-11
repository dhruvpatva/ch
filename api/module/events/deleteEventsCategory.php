<?php
     require_once '../../cmnvalidate.php';
     if (isset($_SESSION['user'])) {  
          if ($_SESSION['user']['role_id'] == 0 || $_SESSION['user']['role_id'] == 1) {
               $id = $_REQUEST['id'];
               $queryupdate = "DELETE FROM `categories` WHERE `id` = '" . $id . "'";
               $query_result = $con->query($queryupdate);
               $result['success'] = 1;
               $result['data'] = 'success';
               $result['error'] = 0;
               $result['error_code'] = NULL;
          }
     }
     $result = json_encode($result);
     echo $result;
?>