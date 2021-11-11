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
          //if (isset($_REQUEST['q']) && !empty(str_replace(' ', '', $_REQUEST['q']))) {
          if (isset($_REQUEST['q'])) {
               $q = $_REQUEST['q'];
               $query = "SELECT id, name FROM users WHERE role_id != 0 AND status='1' AND (name LIKE '%".$q."%')";
               $query_result = $con->query($query);
               $resulted_data = array();
               while ($rows = $query_result->fetch_assoc()) {
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
               $result['error_code'] = 'Required Parameter Are Missing';
          }
     }
     $result = json_encode($result);
     if(array_key_exists('callback', $_GET)) {
          header('Content-Type: text/javascript; charset=utf8');
          header('Access-Control-Max-Age: 3628800');
          header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
          $callback = $_GET['callback'];
          echo $callback . '(' . $result . ');';
     } else {
          header('Content-Type: application/json; charset=utf8');
          echo $result;
     }
?>
