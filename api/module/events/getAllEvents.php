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
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        }
        $start_from = ($page - 1) * $num_rec_per_page;
        if (!empty($_GET["search"])) {
            $sqlTotal = "SELECT ev.id, ev.name, c.name AS category_name, ev.image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS c ON c.id=ev.category_id WHERE ev.status != '2' AND (ev.`name` LIKE '%" . $_GET["search"] . "%' OR c.name LIKE '%" . $_GET["search"] . "%')" . (($_SESSION['user']['userRole'] == 'SPEadmin' || $_SESSION['user']['userRole'] == 'SPIuser') ? ' AND ev.created_by = '.$_SESSION['user']['user_id'].' ' : '');
            $sql = "SELECT ev.id, ev.name, c.name AS category_name, ev.image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS c ON c.id=ev.category_id WHERE ev.status != '2' AND (ev.`name` LIKE '%" . $_GET["search"] . "%' OR c.name LIKE '%" . $_GET["search"] . "%') " . (($_SESSION['user']['userRole'] == 'SPEadmin' || $_SESSION['user']['userRole'] == 'SPIuser') ? ' AND ev.created_by = '.$_SESSION['user']['user_id'].' ' : '') . " LIMIT $start_from, $num_rec_per_page";
        } else { 
            $sqlTotal = "SELECT ev.id, ev.name, c.name AS category_name, ev.image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS c ON c.id=ev.category_id WHERE ev.status != '2' " . (($_SESSION['user']['userRole'] == 'SPEadmin' || $_SESSION['user']['userRole'] == 'SPIuser') ? ' AND ev.created_by = '. $_SESSION['user']['user_id'] .' ' : '');
            $sql = "SELECT ev.id, ev.name, c.name AS category_name, ev.image, ev.status FROM `events` AS ev LEFT JOIN `categories` AS c ON c.id=ev.category_id WHERE ev.status != '2' " . (($_SESSION['user']['userRole'] == 'SPEadmin' || $_SESSION['user']['userRole'] == 'SPIuser') ? ' AND ev.created_by = '.$_SESSION['user']['user_id'].' ' : '') . " LIMIT $start_from, $num_rec_per_page";
        }
        $query_result = $con->query($sql);
        $resulted_data = array();
        while ($rows = $query_result->fetch_assoc()) {
            if ($rows['image'] != "") {
                $rows['image'] = SITE_ROOT . "/uploads/events/logo/" . $rows['image'];
            } else {
                $rows['image'] = SITE_ROOT . "/uploads/events/logo/no-image.png";
            }
            $rows['name'] = $obj->replaceUnwantedChars($rows['name'], 2);
            $rows['category_name'] = $obj->replaceUnwantedChars($rows['category_name'], 2);
            $resulted_data[] = $rows;
        }

        $sqlTotal_result = $con->query($sqlTotal);
        $result['total'] = $sqlTotal_result->num_rows;

        $result['success'] = 1;
        $result['data'] = $resulted_data;
        $result['error'] = 0;
        $result['error_code'] = NULL;
    }
    $result = json_encode($result);
    if (isset($_SESSION['user'])) {
        echo $result;
    }
?>
