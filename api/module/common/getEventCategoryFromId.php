<?php
     require_once '../../cmnvalidate.php';
     $bydirect = true;
     if ($bydirect) {
          $id = $_REQUEST['id'];
          //$query = "SELECT name FROM categories WHERE id=$id";
          $query = "SELECT (SELECT name FROM categories WHERE id = c.parent_id) As parent,c.name FROM categories  as c WHERE c.id = $id";
          $query_result = $con->query($query);
          if ($query_result->num_rows > 0) {
               $resulted_data = $query_result->fetch_assoc();
               $result['success'] = 1;
               $result['data'] = $resulted_data;
               $result['error'] = 0;
               $result['error_code'] = NULL;
          }
     }
     $result = json_encode($result);
     if (isset($_SESSION['user'])) {
          echo $result;
     }
?>
