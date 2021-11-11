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
          if (isset($_REQUEST['userid'])) {
               $userid = $_REQUEST['userid'];
          }
          $filter = 'date';
          if (isset($_REQUEST['filter'])){
               $filter = $_REQUEST['filter'];
          }
          $lat = $lng = NULL;
          if (isset($_REQUEST['lat']) && isset($_REQUEST['lng'])){
               $lat = $_REQUEST['lat'];
               $lng = $_REQUEST['lng'];
          }
          $pagination = 0;
          if (isset($_REQUEST['pagination'])){
              $pagination = $_REQUEST['pagination'];
          }
          $start = $pagination * $num_rec_per_page;
          $current_date = date('Y-m-d H:i:s');
     } 
     if ($bydirect) {
          $condition = "";
          if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "") {
            $category_id = $_REQUEST['category_id'];
            $condition = "c.category_id='".$category_id."' AND ";
          }
           if (isset($_REQUEST['is_mobile_api'])){
                if ($filter != NULL && $filter == 'date') {
                    if (isset($_REQUEST['date_type'])) {
                        $date_type = $_REQUEST['date_type'];
                        if ($date_type == "today") {
                            $condition .= "DATE_FORMAT(cl_slot_time.start_date,'%m-%d-%Y') = '" . date('m-d-Y') . "' AND ";
                        }
                        if ($date_type == "tomorrow") {
                            $condition .= "DATE_FORMAT(cl_slot_time.start_date,'%m-%d-%Y') = '" . date('m-d-Y', strtotime(date() . "+1 days")) . "' AND ";
                        }
                        if ($date_type == "weekend") {
                            $nextSatday = date("Y-m-d H:i:s", strtotime('next Saturday'));
                            $nextSunday = date('Y-m-d H:i:s', strtotime($nextSatday . "+1 days"));
                            $condition .= "cl_slot_time.start_date BETWEEN '" . $nextSatday . "' AND '" . $nextSunday . "' AND ";
                        }
                        if ($date_type == "custom") {
                            $s_date = date("Y-m-d H:i:s");
                            $e_date = date("Y-m-d H:i:s", strtotime($s_date . "+1 days"));
                            if (isset($_REQUEST['from_date']) && $_REQUEST['from_date'] != "") {
                                $s_date = date("Y-m-d H:i:s", strtotime($_REQUEST['from_date'] . "-1 days"));
                            }
                            if (isset($_REQUEST['to_date']) && $_REQUEST['to_date'] != "") {
                                $e_date = date("Y-m-d H:i:s", strtotime($_REQUEST['to_date'] . "+1 days"));
                            }
                            $condition .= "cl_slot_time.start_date BETWEEN '" . $s_date . "' AND '" . $e_date . "' AND ";
                        }
                    }
                }
               $query = "SELECT (SELECT COUNT(*) FROM `class_user_bookings` AS cl_book WHERE cl_book.class_id = c.id GROUP BY c.id) as total_booking,c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy, cat.name AS event_category,cl_slot_time.start_date,cl_slot_time.end_date
                   FROM classes AS c 
                   LEFT JOIN `spe` ON spe.id=c.spe_id 
                   LEFT JOIN `categories` AS cat ON cat.id=c.category_id 
                   LEFT JOIN `class_slots` As cl_slot ON cl_slot.class_id = c.id 
                   LEFT JOIN `class_slot_timings` As cl_slot_time ON cl_slot_time.class_id = c.id
                   LEFT JOIN `class_user_bookings` As cl_book ON cl_book.class_id = c.id 
                   WHERE ".$condition."c.status='1' AND cl_slot_time.start_date >= '$current_date' GROUP BY c.id "
              . (($filter != NULL && $filter == 'distance' && $lat != NULL && $lng != NULL) ? 'ORDER BY ROUND(DISTANCE_BETWEEN(	c.latitude,c.longitude, '. $lat .', '. $lng .')) ASC' : '')
              . (($filter != NULL && $filter == 'price') ? 'ORDER BY c.price ASC' : '')
              . (($filter != NULL && $filter == 'date') ? 'ORDER BY cl_slot_time.start_date ASC' : '')
              . (($filter != NULL && $filter == 'popularity') ? 'ORDER BY total_booking DESC' : '') . " LIMIT $start, $num_rec_per_page";
              
           } else {
               $query = "SELECT c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy, cat.name AS event_category,CONCAT(cl_slot.start_date,' ',cl_slot.start_time) As start_date,CONCAT(cl_slot.end_date,' ',cl_slot.end_time) As end_date FROM classes AS c LEFT JOIN `spe` ON spe.id=c.spe_id LEFT JOIN `categories` AS cat ON cat.id=c.category_id LEFT JOIN `class_slots` As cl_slot ON cl_slot.class_id = c.id WHERE ".$condition."c.status='1'";
           } 
           //echo $query; exit;
           $query_result = $con->query($query);
           $resulted_data = array();
           while ($rows = $query_result->fetch_assoc()) {
                $query_in = "SELECT ci.`user_id` as `id`, u.`firstname`, u.`lastname` FROM class_instructors AS ci LEFT JOIN users AS u ON ci.`user_id`=u.`id` WHERE ci.`class_id`='".$rows['id']."' AND ci.`status`='1';";
                $query_result_in = $con->query($query_in);
                $instructor = array();
                while ($row = $query_result_in->fetch_assoc()) {
                     $row['name'] = $row['firstname'] . " " . $row['lastname'];
                     unset($row['firstname']);
                     unset($row['lastname']);
                     $instructor[] = $row;
                }
                $rows['instructor'] = $instructor;
                if($rows['image'] != ""){
                     $rows['image'] = SITE_ROOT."/uploads/classes/logo/" . $rows['image'];
                } else {
                     $rows['image'] = "";
                }
                if($rows['banner_image'] != ""){
                     $rows['banner_image'] = SITE_ROOT."/uploads/classes/banner/" . $rows['banner_image'];
                } else {
                     $rows['banner_image'] = "";
                }
                $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
                $rows['description'] = $obj->replaceUnwantedChars($rows['description'], 2);
                $rows['latitude'] = $obj->replaceUnwantedChars($rows['latitude'], 2);
                $rows['longitude'] = $obj->replaceUnwantedChars($rows['longitude'], 2);
                $rows['address'] = $obj->replaceUnwantedChars($rows['address'], 2);
                $rows['city'] = $obj->replaceUnwantedChars($rows['city'], 2);
                $rows['state'] = $obj->replaceUnwantedChars($rows['state'], 2);
                $rows['country'] = $obj->replaceUnwantedChars($rows['country'], 2);
                $rows['zipcode'] = $obj->replaceUnwantedChars($rows['zipcode'], 2);
                $rows['price'] = $obj->replaceUnwantedChars($rows['price'], 2);
                $rows['discount_amount'] = $obj->replaceUnwantedChars($rows['discount_amount'], 2);
                if($rows['offer_start_date'] != "0000-00-00 00:00:00" && $rows['offer_start_date'] != "1970-01-01 00:00:00"){
                     $rows['offer_start_date'] = date('Y-m-d H:i:s', strtotime($rows['offer_start_date']));
                } else {
                     $rows['offer_start_date'] = "";
                }
                if($rows['offer_end_date'] != "0000-00-00 00:00:00" && $rows['offer_end_date'] != "1970-01-01 00:00:00"){
                     $rows['offer_end_date'] = date('Y-m-d H:i:s', strtotime($rows['offer_end_date']));
                } else {
                     $rows['offer_end_date'] = "";
                }
                $query_att = "SELECT id,contact_number,registration_date FROM class_user_bookings WHERE class_id='" . $rows['id'] . "' AND user_id='" . $userid . "' AND status='1'";
                $query_att_result = $con->query($query_att);
                if ($query_att_result->num_rows == 0) {
                    $rows['is_join'] = 0;
                    $rows['booking_details'] = array("id" => "", "contact_number" => "", "registration_date" => "");
                } else {
                    $query_att_data = $query_att_result->fetch_assoc();
                    $rows['is_join'] = 1;
                    $rows['booking_details'] = $query_att_data;
                }
                $resulted_data[] = $rows;
           }
           $result['success'] = 1;
           $result['data'] = $resulted_data;
           $result['error'] = 0;
           $result['error_code'] = NULL;
     }
     $result = json_encode($result);
     if (isset($_REQUEST['is_mobile_api']) || isset($_SESSION['user'])) {
          echo $result;
     }
?>
