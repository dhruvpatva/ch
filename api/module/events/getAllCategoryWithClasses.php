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
        if (isset($_REQUEST['filter'])) {
            $filter = $_REQUEST['filter'];
        }
        $lat = $lng = NULL;
        if (isset($_REQUEST['lat']) && isset($_REQUEST['lng'])) {
            $lat = $_REQUEST['lat'];
            $lng = $_REQUEST['lng'];
        }
        $pagination = 0;
        if (isset($_REQUEST['pagination'])) {
            $pagination = $_REQUEST['pagination'];
        }
        $start = $pagination * $num_rec_per_page;
        $current_date = date('Y-m-d H:i:s');
    }
    if ($bydirect) {
        if (isset($_REQUEST['is_mobile_api'])) {
            $query = "SELECT id, name, description FROM categories WHERE type='Classes' AND status='1' LIMIT $start, $num_rec_per_page";
        } else {
            $query = "SELECT id, name, description FROM categories WHERE type='Classes' AND status='1'";
        }
        $query_result = $con->query($query);
        $resulted_data = array();
        while ($rows = $query_result->fetch_assoc()) {
            $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
            $rows['description'] = $obj->replaceUnwantedChars($rows['description'], 2);
            $category_id = $rows['id'];
            $query_class = "SELECT COUNT(cl_book.class_id) As total_booking,c.`id`,c.`spe_id`,c.`category_id`,c.`name`,c.`description`,c.`timezone`,c.`latitude`,c.`longitude`,c.`address`,c.`city`,c.`state`,c.`country`,c.`zipcode`,c.`is_paid`,c.`price`,c.`image`,c.`banner_image`,c.`discount_type`,c.`discount_amount`,c.`offer_start_date`,c.`offer_end_date`,c.`status`,spe.`name` as spe_name, spe.`phone` as spe_phone, spe.`website` as spe_website, spe.`email` as spe_email, spe.`refund_policy` as spe_refund_policy, spe.`privacy_policy` as spe_privacy_policy, cat.name AS event_category,cl_slot_time.start_date,cl_slot_time.end_date
                       FROM classes AS c 
                       LEFT JOIN `spe` ON spe.id=c.spe_id 
                       LEFT JOIN `categories` AS cat ON cat.id=c.category_id 
                       LEFT JOIN `class_slots` As cl_slot ON cl_slot.class_id = c.id 
                       LEFT JOIN `class_slot_timings` As cl_slot_time ON cl_slot_time.class_id = c.id
                       LEFT JOIN `class_user_bookings` As cl_book ON cl_book.class_id = c.id 
                       WHERE c.category_id='" . $category_id . "' AND c.status='1' AND cl_slot_time.start_date >= '$current_date' GROUP BY c.id "
                    . (($filter != NULL && $filter == 'distance' && $lat != NULL && $lng != NULL) ? 'ORDER BY ROUND(DISTANCE_BETWEEN(c.latitude,c.longitude, ' . $lat . ', ' . $lng . ')) ASC' : '')
                    . (($filter != NULL && $filter == 'price') ? 'ORDER BY c.price ASC' : '')
                    . (($filter != NULL && $filter == 'date') ? 'ORDER BY cl_slot_time.start_date ASC' : '')
                    . (($filter != NULL && $filter == 'popularity') ? 'ORDER BY cl_book.class_id DESC' : '') . ' LIMIT 3';
            ;
            $query_result_class = $con->query($query_class);
            $cl_data = array();
            while ($row_class = $query_result_class->fetch_assoc()) {
                $query_in = "SELECT ci.`user_id` as `id`, u.`firstname`, u.`lastname` FROM class_instructors AS ci LEFT JOIN users AS u ON ci.`user_id`=u.`id` WHERE ci.`class_id`='" . $row_class['id'] . "' AND ci.`status`='1';";
                $query_result_in = $con->query($query_in);
                $instructor = array();
                while ($row = $query_result_in->fetch_assoc()) {
                    $row['name'] = $row['firstname'] . " " . $row['lastname'];
                    unset($row['firstname']);
                    unset($row['lastname']);
                    $instructor[] = $row;
                }
                $row_class['instructor'] = $instructor;
                if ($row_class['image'] != "") {
                    $row_class['image'] = SITE_ROOT . "/uploads/classes/logo/" . $row_class['image'];
                } else {
                    $row_class['image'] = "";
                }
                if ($row_class['banner_image'] != "") {
                    $row_class['banner_image'] = SITE_ROOT . "/uploads/classes/banner/" . $row_class['banner_image'];
                } else {
                    $row_class['banner_image'] = "";
                }
                $row_class['name'] = $obj->replaceUnwantedChars($row_class['name'], 2);
                $row_class['description'] = $obj->replaceUnwantedChars($row_class['description'], 2);
                $row_class['latitude'] = $obj->replaceUnwantedChars($row_class['latitude'], 2);
                $row_class['longitude'] = $obj->replaceUnwantedChars($row_class['longitude'], 2);
                $row_class['address'] = $obj->replaceUnwantedChars($row_class['address'], 2);
                $row_class['city'] = $obj->replaceUnwantedChars($row_class['city'], 2);
                $row_class['state'] = $obj->replaceUnwantedChars($row_class['state'], 2);
                $row_class['country'] = $obj->replaceUnwantedChars($row_class['country'], 2);
                $row_class['zipcode'] = $obj->replaceUnwantedChars($row_class['zipcode'], 2);
                $row_class['price'] = $obj->replaceUnwantedChars($row_class['price'], 2);
                $row_class['discount_amount'] = $obj->replaceUnwantedChars($row_class['discount_amount'], 2);
                if ($row_class['offer_start_date'] != "0000-00-00 00:00:00" && $row_class['offer_start_date'] != "1970-01-01 00:00:00") {
                    $row_class['offer_start_date'] = date('Y-m-d H:i:s', strtotime($row_class['offer_start_date']));
                } else {
                    $row_class['offer_start_date'] = "";
                }
                if ($row_class['offer_end_date'] != "0000-00-00 00:00:00" && $row_class['offer_end_date'] != "1970-01-01 00:00:00") {
                    $row_class['offer_end_date'] = date('Y-m-d H:i:s', strtotime($row_class['offer_end_date']));
                } else {
                    $row_class['offer_end_date'] = "";
                }
                $query_att = "SELECT id,contact_number,registration_date FROM class_user_bookings WHERE class_id='" . $row_class['id'] . "' AND user_id='" . $userid . "' AND status='1'";
                $query_att_result = $con->query($query_att);
                if ($query_att_result->num_rows == 0) {
                    $row_class['is_join'] = 0;
                    $row_class['booking_details'] = array("id" => "", "contact_number" => "", "registration_date" => "");
                } else {
                    $query_att_data = $query_att_result->fetch_assoc();
                    $row_class['is_join'] = 1;
                    $row_class['booking_details'] = $query_att_data;
                }
                $cl_data[] = $row_class;
            }
            if (!empty($cl_data)) {
                $rows['classes'] = $cl_data;
                $resulted_data[] = $rows;
            }
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
