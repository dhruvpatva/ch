<?php
     require_once '../../cmnvalidate.php';
     require_once '../../libs/image/ImageManipulator.php';
     if (isset($_SESSION['user'])) {
          if (isset($_POST['name'])) {
               $validation_flag = 0;
               $validation_error_code = NULL;
               $category_id = "";
               $name = "";
               $description = "";
               $timezone = "";
               $latitude = "";
               $longitude = "";
               $address = "";
               $city = "";
               $state = "";
               $country = "";
               $zipcode = "";
               $is_paid = "";
               $price = "";
               $discount_type = "";
               $discount_amount = "0";
               $offer_start_date = "NULL";
               $offer_end_date = "NULL";
               $status = 1;
               $logo = "";
               $banner = "";
               $created = date('Y-m-d H:i:s');
               $created_by = $_SESSION['user']['user_id'];
               
               if (isset($_POST['category_id'])) {
                    $category_id = $_POST['category_id'];
               }
               if (isset($_POST['name'])) {
                    $name = $obj->replaceUnwantedChars($_POST['name'], 1);
               }
               if (isset($_POST['description'])) {
                    $description = $obj->replaceUnwantedChars($_POST['description'], 1);
               }
               if (isset($_POST['timezone'])) {
                    $timezone = $_POST['timezone'];
               }
               if (isset($_POST['latitude'])) {
                    $latitude = $obj->replaceUnwantedChars($_POST['latitude'], 1);
               }
               if (isset($_POST['longitude'])) {
                    $longitude = $obj->replaceUnwantedChars($_POST['longitude'], 1);
               }
               if (isset($_POST['address'])) {
                    $address = $obj->replaceUnwantedChars($_POST['address'], 1);
               }
               if (isset($_POST['city'])) {
                    $city = $obj->replaceUnwantedChars($_POST['city'], 1);
               }
               if (isset($_POST['state'])) {
                    $state = $obj->replaceUnwantedChars($_POST['state'], 1);
               }
               if (isset($_POST['country'])) {
                    $country = $obj->replaceUnwantedChars($_POST['country'], 1);
               }
               if (isset($_POST['zipcode'])) {
                    $zipcode = $obj->replaceUnwantedChars($_POST['zipcode'], 1);
               }
               if (isset($_POST['is_paid'])) {
                    $is_paid = $_POST['is_paid'];
               }
               if (isset($_POST['price'])) {
                    $price = $obj->replaceUnwantedChars($_POST['price'], 1);
               }
               if (isset($_POST['discount_type'])) {
                    $discount_type = $_POST['discount_type'];
               }
               if (isset($_POST['discount_amount'])) {
                    $discount_amount = $obj->replaceUnwantedChars($_POST['discount_amount'], 1);
               }
               if (isset($_POST['offer_start_date'])) {
                    $date = explode('-', $_POST['offer_start_date']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $offer_start_date = "'".date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]))."'";
                    } else {
                         $offer_start_date = "NULL";
                    }
               }
               if (isset($_POST['offer_end_date'])) {
                    $date = explode('-', $_POST['offer_end_date']);
                    if(isset($date[0]) && isset($date[1]) && isset($date[2])){
                         $offer_end_date = "'".date('Y-m-d H:i:s', strtotime($date[1]."-".$date[0]."-".$date[2]))."'";
                    } else {
                         $offer_end_date = "NULL";
                    }
               }
               if (isset($_POST['status'])) {
                    $status = $_POST['status'];
               }
               
               //if ($validation_flag == 0 && (empty($name) || empty(str_replace(' ', '', $name)))) {
               if ($validation_flag == 0 && (empty($name))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Name Is Required.';
               }
               //if ($validation_flag == 0 && (empty($category_id) || empty(str_replace(' ', '', $category_id)) || $category_id == '0')) {
               if ($validation_flag == 0 && (empty($category_id) || $category_id == '0')) {
                    $validation_flag = 1;
                    $validation_error_code = 'Please Select Event Category.';
               }
               //if ($validation_flag == 0 && (empty($timezone) || empty(str_replace(' ', '', $timezone)))) {
               if ($validation_flag == 0 && (empty($timezone))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Please Select Event Timezone.';
               }
               //if ($validation_flag == 0 && (empty($latitude) || empty(str_replace(' ', '', $latitude)))) {
               if ($validation_flag == 0 && (empty($latitude))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Latitude Is Required.';
               } else {
                    if($validation_flag == 0 && !preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $latitude)){
                         $validation_flag = 1;
                         $validation_error_code = 'Enter Valid Latitude.';
                    }
               }
               //if ($validation_flag == 0 && (empty($longitude) || empty(str_replace(' ', '', $longitude)))) {
               if ($validation_flag == 0 && (empty($longitude))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Longitude Is Required.';
               } else {
                    if($validation_flag == 0 && !preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $longitude)){
                         $validation_flag = 1;
                         $validation_error_code = 'Enter Valid Longitude.';
                    }
               }
               //if ($validation_flag == 0 && (empty($address) || empty(str_replace(' ', '', $address)))) {
               if ($validation_flag == 0 && (empty($address))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Address Is Required.';
               }
               //if ($validation_flag == 0 && (empty($city) || empty(str_replace(' ', '', $city)))) {
               if ($validation_flag == 0 && (empty($city))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event City Is Required.';
               }
               //if ($validation_flag == 0 && (empty($state) || empty(str_replace(' ', '', $state)))) {
               if ($validation_flag == 0 && (empty($state))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event State Is Required.';
               }
               //if ($validation_flag == 0 && (empty($country) || empty(str_replace(' ', '', $country)))) {
               if ($validation_flag == 0 && (empty($country))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Country Is Required.';
               }
               //if ($validation_flag == 0 && (empty($zipcode) || empty(str_replace(' ', '', $zipcode)))) {
               if ($validation_flag == 0 && (empty($zipcode))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Zipcode Is Required.';
               }
               //if ($is_paid == '1' && $validation_flag == 0 && (empty($price) || empty(str_replace(' ', '', $price)))) {
               if ($is_paid == '1' && $validation_flag == 0 && (empty($price))) {
                    $validation_flag = 1;
                    $validation_error_code = 'Event Booking Price Is Required If Is Paid Event.';
               }
               
               if ($validation_flag == 0) {
                    if (isset($_FILES['logo'])) {
                         $file_name = time() . $_FILES['logo']['name'];
                         $file_size = $_FILES['logo']['size'];
                         $file_tmp = $_FILES['logo']['tmp_name'];
                         $file_type = $_FILES['logo']['type'];
                         $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                         if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                              $response = 'Invalid file extension.';
                              $validation_flag = 1;
                              $validation_error_code = 'Invalid file extension.';
                         } else {
                              $manipulator = new ImageManipulator($_FILES['logo']['tmp_name']);
                              $newImage = $manipulator->resample(600, 600);
                              $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/events/logo/" . $file_name);
                              $logo = $file_name;
                         }
                    }
                    if (isset($_FILES['banner'])) {
                         $file_name = time() . $_FILES['banner']['name'];
                         $file_size = $_FILES['banner']['size'];
                         $file_tmp = $_FILES['banner']['tmp_name'];
                         $file_type = $_FILES['banner']['type'];
                         $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                         if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                              $response = 'Invalid file extension.';
                              $validation_flag = 1;
                              $validation_error_code = 'Invalid file extension.';
                         } else {
                              $manipulator = new ImageManipulator($_FILES['banner']['tmp_name']);
                              $newImage = $manipulator->resample(1200, 700);
                              $res = $manipulator->save(PROJECT_ROOT_UPLOAD . "/events/banner/" . $file_name);
                              $banner = $file_name;
                         }
                    }
               }

               if ($validation_flag == 0) { 
                    $query = "INSERT INTO `events` (`category_id`,`name`,`description`,`timezone`,`latitude`,`longitude`,`address`,`city`,`state`,`country`,`zipcode`,`is_paid`,`price`,`image`,`banner_image`,`discount_type`,`discount_amount`,`offer_start_date`,`offer_end_date`,`status`,`created`,`created_by`) VALUES ('" . $category_id . "','" . $name . "','" . $description . "','" . $timezone . "','" . $latitude . "','" . $longitude . "','" . $address . "','" . $city . "','" . $state . "','" . $country . "','" . $zipcode . "','" . $is_paid . "','" . $price . "','" . $logo . "','" . $banner . "','" . $discount_type . "','" . $discount_amount . "'," . $offer_start_date . "," . $offer_end_date . ",'" . $status . "','" . $created . "','" . $created_by . "');";
                    $query_result = $con->query($query);
                    $result['success'] = 1;
                    $result['data'] = 'success';
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
               $result['error_code'] = 'Required Parameter Are Missing';
          }
     $result = json_encode($result);
     echo $result;
}
?>