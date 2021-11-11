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
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != "" && isset($_REQUEST['event_id']) && $_REQUEST['event_id'] != "") {
            $id = $_REQUEST['id'];
            $event_id = $_REQUEST['event_id'];
            if (isset($_GET["page"])) {
                $page  = $_GET["page"];
            } else {
                $page=1;
            }
            $start_from = ($page-1) * $num_rec_per_page;
            if (!empty($_GET["search"])) {
                $sqlTotal = "SELECT `id`, `start_date`, `end_date`, `interval`, `status` FROM `event_slot_timings` WHERE `event_id` = '".$event_id."' AND `slot_id` = '".$id."' AND (`start_date` LIKE '%" . $_GET["search"] . "%' OR `end_date` LIKE '%" . $_GET["search"] . "%' OR `interval` LIKE '%" . $_GET["search"] . "%')";
                $sql = "SELECT `id`, `start_date`, `end_date`, `interval`, `status` FROM `event_slot_timings` WHERE `event_id` = '".$event_id."' AND `slot_id` = '".$id."' AND (`start_date` LIKE '%" . $_GET["search"] . "%' OR `end_date` LIKE '%" . $_GET["search"] . "%' OR `interval` LIKE '%" . $_GET["search"] . "%') LIMIT $start_from, $num_rec_per_page";
            } else {
                $sqlTotal = "SELECT `id`, `start_date`, `end_date`, `interval`, `status` FROM `event_slot_timings` WHERE `event_id` = '".$event_id."' AND `slot_id` = '".$id."'";
                $sql = "SELECT `id`, `start_date`, `end_date`, `interval`, `status` FROM `event_slot_timings` WHERE `event_id` = '".$event_id."' AND `slot_id` = '".$id."' LIMIT $start_from, $num_rec_per_page";
            }
            $query_result = $con->query($sql);
            $resulted_data = array();
            while ($rows = $query_result->fetch_assoc()) {
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
