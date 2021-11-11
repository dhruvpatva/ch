<?php
     require_once '../../cmnvalidate.php';
     $bydirect = true;
     if ($bydirect) {
          $id = $_REQUEST['id'];
          $query = "SELECT id, name, description, photo, created, status FROM categories WHERE id=$id";
          $query_result = $con->query($query);
          if ($query_result->num_rows > 0) {
               $resulted_data = $query_result->fetch_assoc();
               if ($resulted_data['photo'] != "") {
                    $resulted_data['photo'] = SITE_ROOT . "/uploads/category/" . $resulted_data['photo'];
               } else {
                    $resulted_data['photo'] = SITE_ROOT . "/uploads/category/no-image.png";
               }
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
