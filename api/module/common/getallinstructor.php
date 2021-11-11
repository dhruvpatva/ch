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
          $query = "SELECT id, firstname, lastname FROM users WHERE status='1' AND role_id='2'";
          $query_result = $con->query($query);
          $resulted_data = array();
          while ($rows = $query_result->fetch_assoc()) {
               $rows['name'] = $rows['firstname']." ".$rows['lastname'];
               $resulted_data[] = $rows;
          }
          $result['success'] = 1;
          $result['data'] = $resulted_data;
          $result['error'] = 0;
          $result['error_code'] = NULL;
     }
     $result = json_encode($result);
     header('Content-Type: application/json; charset=utf8');
     echo $result;
?>
