<?php
require_once 'classes/commonfunction.php';
$con = $obj->connect();
if(isset($_REQUEST['is_mobile_api'])){
      $result = $obj->validateApiCall($_REQUEST);
}
?>