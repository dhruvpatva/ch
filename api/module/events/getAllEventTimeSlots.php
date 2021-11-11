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
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
            $id = $_REQUEST['id'];
            
            if (isset($_GET["page"])) {
                $page  = $_GET["page"];
            } else {
                $page=1;
            }
            $start_from = ($page-1) * $num_rec_per_page;
            if (!empty($_GET["search"])) {
                $sqlTotal = "SELECT `id`, `start_date`, `start_time`, `end_date`, `end_time`, `timezone`, `total_slots`, `attendee_limit`, `status`, (SELECT count(event_slot_timings.id) FROM event_slot_timings WHERE event_slot_timings.`slot_id`=event_slots.`id` AND event_slot_timings.`status`='1' GROUP BY event_slot_timings.`slot_id`) AS total_slots FROM `event_slots` WHERE `event_id` = '".$id."' AND `status`!='2' AND (`start_date` LIKE '%" . $_GET["search"] . "%' OR `start_time` LIKE '%" . $_GET["search"] . "%' OR `end_date` LIKE '%" . $_GET["search"] . "%' OR `end_time` LIKE '%" . $_GET["search"] . "%' OR `timezone` LIKE '%" . $_GET["search"] . "%' OR `total_slots` LIKE '%" . $_GET["search"] . "%' OR `attendee_limit` LIKE '%" . $_GET["search"] . "%')";
                $sql = "SELECT `id`, `start_date`, `start_time`, `end_date`, `end_time`, `timezone`, `total_slots`, `attendee_limit`, `status`, (SELECT count(event_slot_timings.id) FROM event_slot_timings WHERE event_slot_timings.`slot_id`=event_slots.`id` AND event_slot_timings.`status`='1' GROUP BY event_slot_timings.`slot_id`) AS total_slots FROM `event_slots` WHERE `event_id` = '".$id."' AND `status`!='2' AND (`start_date` LIKE '%" . $_GET["search"] . "%' OR `start_time` LIKE '%" . $_GET["search"] . "%' OR `end_date` LIKE '%" . $_GET["search"] . "%' OR `end_time` LIKE '%" . $_GET["search"] . "%' OR `timezone` LIKE '%" . $_GET["search"] . "%' OR `total_slots` LIKE '%" . $_GET["search"] . "%' OR `attendee_limit` LIKE '%" . $_GET["search"] . "%') LIMIT $start_from, $num_rec_per_page";
            } else {
                $sqlTotal = "SELECT `id`, `start_date`, `start_time`, `end_date`, `end_time`, `timezone`, `total_slots`, `attendee_limit`, `status`, (SELECT count(event_slot_timings.id) FROM event_slot_timings WHERE event_slot_timings.`slot_id`=event_slots.`id` AND event_slot_timings.`status`='1' GROUP BY event_slot_timings.`slot_id`) AS total_slots FROM `event_slots` WHERE `event_id` = '".$id."' AND `status`!='2'";
                $sql = "SELECT `id`, `start_date`, `start_time`, `end_date`, `end_time`, `timezone`, `total_slots`, `attendee_limit`, `status`, (SELECT count(event_slot_timings.id) FROM event_slot_timings WHERE event_slot_timings.`slot_id`=event_slots.`id` AND event_slot_timings.`status`='1' GROUP BY event_slot_timings.`slot_id`) AS total_slots FROM `event_slots` WHERE `event_id` = '".$id."' AND `status`!='2' LIMIT $start_from, $num_rec_per_page";
            }
            $query_result = $con->query($sql);
            $resulted_data = array();
            while ($rows = $query_result->fetch_assoc()) {
                $rows['start_date_time'] = $rows['start_date'] . " " . $rows['start_time'];
                $rows['end_date_time'] = $rows['end_date'] . " " . $rows['end_time'];
                $resulted_data[] = $rows;
            }
            
            $sqlTotal_result = $con->query($sqlTotal);
            $result['total'] = $sqlTotal_result->num_rows;
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
    if (isset($_SESSION['user'])) {
        echo $result;
    }
?>
