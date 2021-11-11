<?php
require_once '../../cmnvalidate.php';
require_once '../../libs/image/ImageManipulator.php';
if (isset($_SESSION['user'])) { 
     if (isset($_REQUEST['name'])) {
          $name = $obj->replaceUnwantedChars($_REQUEST['name'], 1);
          $description = "";
          $photo = "";
          $validation_flag = 0;
          $validation_error_code = NULL;
          if(isset($_REQUEST['description']) && $_REQUEST['description'] != ""){
               $description = $obj->replaceUnwantedChars($_REQUEST['description'],1);
          }
          $status = 1;
          if (isset($_REQUEST['status'])){
               $status = $_REQUEST['status'];
          }
          if (isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != ""){
              $parent_id = $_REQUEST['parent_id'];
          }
          if (isset($_FILES['photo'])) {
                $file_name = time() . $_FILES['photo']['name'];
                $file_size = $_FILES['photo']['size'];
                $file_tmp = $_FILES['photo']['tmp_name'];
                $file_type = $_FILES['photo']['type'];
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                     $response = 'Invalid file extension.';
                     $validation_flag = 1;
                     $validation_error_code = 'Invalid file extension.';
                } else {
                     $manipulator = new ImageManipulator($_FILES['photo']['tmp_name']);
                     $newImage = $manipulator->resample(1000, 600, false);
                     $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/category/" . $file_name);
                     $photo = $file_name;
                }
          }
          if ($validation_flag == 0){
                $query = "SELECT id FROM categories WHERE type='1' AND name='$name'";
                $query_result = $con->query($query);
                if ($query_result->num_rows == 0) {
                     $query = "INSERT INTO categories (type, parent_id, name, description, photo, created, created_by, status) VALUES ('1', '".$parent_id."', '".$name."', '".$description."', '".$photo."', '".date("Y-m-d H:i:s")."', '". $_SESSION['user']['user_id'] ."', '".$status."')";
                     $query_result = $con->query($query);
                     $result['success'] = 1;
                     $result['data'] = 'success';
                     $result['error'] = 0;
                     $result['error_code'] = NULL;
                } else {
                     $result['success'] = 0;
                     $result['data'] = NULL;
                     $result['error'] = 1;
                     $result['error_code'] = 'Events Category Already Exists';
                }
          } else {
                $result['success'] = 0;
                $result['data'] = NULL;
                $result['error'] = 1;
                $result['error_code'] = $validation_error_code;
          }
     } else {
          $result['success'] = 0;
          $result['data'] = NULL;
          $result['error'] = 1;
          $result['error_code'] = 'Required Parameter Are Missing';
     }
$result = json_encode($result);
echo $result;
}
?>