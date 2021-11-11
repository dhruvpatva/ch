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
          $userid = $_REQUEST['userid'];
     }
     if ($bydirect) {
          $query = "SELECT id, name FROM categories WHERE type='1' AND status='1'";
          $query_result = $con->query($query);
          $resulted_data = array();
          while ($rows = $query_result->fetch_assoc()) {
               $resulted_data[] = $rows;
          }
          $result['success'] = 1;
          $result['data'] = $resulted_data;
          $result['error'] = 0;
          $result['error_code'] = NULL;
     }
     $result = json_encode($result);
     echo $result;
?>
