<?php
require_once '../../cmnvalidate.php';
require_once '../../libs/image/ImageManipulator.php';
$bydirect = true; 
if (isset($_SESSION['user'])) {  
     if (isset($_REQUEST['id']) && isset($_REQUEST['name'])) {
          $id = $_REQUEST['id'];
          $parent_id = NULL;
          $name = $obj->replaceUnwantedChars($_REQUEST['name'], 1);
          if (isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != ""){
              $parent_id = $_REQUEST['parent_id'];
          }
          $query = "SELECT id FROM categories WHERE id != '" . $id . "' AND type='1' AND name='" . $name . "'";
          $query_result = $con->query($query);
          if ($query_result->num_rows == 0) {
               $validation_flag = 0;
               $validation_error_code = NULL;
               $updatevalues = "";
               if (isset($_REQUEST['name']) && $_REQUEST['name'] != "") {
                    $updatevalues .= "name='" . $name . "' ,";
               }
               /*if (isset($_REQUEST['parent']) && $_REQUEST['parent'] != ""){
                    $updatevalues .= "parent_id='" . $_REQUEST['parent'] . "' ,";
               }*/
               if (isset($_REQUEST['description']) && $_REQUEST['description'] != "") {
                    $description = $obj->replaceUnwantedChars($_REQUEST['description'], 1);
                    $updatevalues .= "description='" . $description . "' ,";
               }
               if (isset($_REQUEST['status']) && $_REQUEST['status'] != "") {
                    $updatevalues .= "status='" . $_REQUEST['status'] . "' ,";
               } 
               if (isset($_FILES['image'])) { 
                    $file_name = time() . $_FILES['image']['name'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_type = $_FILES['image']['type'];
                    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                         $response = 'Invalid file extension.';
                         $validation_flag = 1;
                         $validation_error_code = 'Invalid file extension.';
                    } else {
                         $manipulator = new ImageManipulator($_FILES['image']['tmp_name']);
                         $newImage = $manipulator->resample(1000, 600, false);
                         $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/category/" . $file_name);
                         $updatevalues .= "photo='". $file_name ."' ,";
                    }
               } 
               $updatevalues .= "updated='" . date('Y-m-d H:i:s') . "'";
               $updatevalues = rtrim($updatevalues, ",");
               if ($validation_flag == 0) {
                    $query = "UPDATE `categories` SET $updatevalues WHERE id = '$id' ";
                    $query_result = $con->query($query);
                    $result['success'] = 1;
                    $result['data'] = 'success';
                    $result['parent_id'] = $parent_id;
                    $result['error'] = 0;
                    $result['error_code'] = NULL;
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
               $result['error_code'] = 'Event Category Already Exists';
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