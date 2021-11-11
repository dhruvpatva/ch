<?php
include("config/config.php");
include("../../libs/PHPMailer/PHPMailerAutoload.php");

define('PROJECT_ROOT_UPLOAD', dirname( dirname(dirname(__FILE__)))."/uploads");
define('PROJECT_ROOT', dirname( dirname(dirname(__FILE__)))."/uploads");
define('SITE_ROOT', "http://localhost/ClubhouseNow");

class commonFunction extends configFunction {
        
    public $cryptKey = "qJB0rGtIn5UB1xG03efyCp";
    public $no_image = "image_not_available.png";
    public $site_url = "";
    public $from_admin_email = "team@fitfinder.com";
    public $from_admin_name = "FitFinder";
    
    function getCurrentPage() {
        $result = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
        return $result;
    }

    function getCurrentUrl() {
        return $this->getCurrentPage() . (($_SERVER["QUERY_STRING"] != '') ? '?' . $_SERVER["QUERY_STRING"] : '');
    }

    function encryptIt($data) {
        $result = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->cryptKey), $data, MCRYPT_MODE_CBC, md5(md5($this->cryptKey))));
        return( $result );
    }

    function decryptIt($data) {
        $result = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->cryptKey), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($this->cryptKey))), "\0");
        return( $result );
    }

    function getTimeZone($format) {
        return date('Y-m-d H:i:s');
    }
    function getTimeZoneList($format) {
       $timezones = array(
                'Pacific/Midway'       => "(GMT-11:00) Midway Island",
                'US/Samoa'             => "(GMT-11:00) Samoa",
                'US/Hawaii'            => "(GMT-10:00) Hawaii",
                'US/Alaska'            => "(GMT-09:00) Alaska",
                'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
                'America/Tijuana'      => "(GMT-08:00) Tijuana",
                'US/Arizona'           => "(GMT-07:00) Arizona",
                'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
                'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
                'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
                'America/Mexico_City'  => "(GMT-06:00) Mexico City",
                'America/Monterrey'    => "(GMT-06:00) Monterrey",
                'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
                'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
                'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
                'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
                'America/Bogota'       => "(GMT-05:00) Bogota",
                'America/Lima'         => "(GMT-05:00) Lima",
                'America/Caracas'      => "(GMT-04:30) Caracas",
                'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
                'America/La_Paz'       => "(GMT-04:00) La Paz",
                'America/Santiago'     => "(GMT-04:00) Santiago",
                'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
                'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
                'Greenland'            => "(GMT-03:00) Greenland",
                'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
                'Atlantic/Azores'      => "(GMT-01:00) Azores",
                'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
                'Africa/Casablanca'    => "(GMT) Casablanca",
                'Europe/Dublin'        => "(GMT) Dublin",
                'Europe/Lisbon'        => "(GMT) Lisbon",
                'Europe/London'        => "(GMT) London",
                'Africa/Monrovia'      => "(GMT) Monrovia",
                'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
                'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
                'Europe/Berlin'        => "(GMT+01:00) Berlin",
                'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
                'Europe/Brussels'      => "(GMT+01:00) Brussels",
                'Europe/Budapest'      => "(GMT+01:00) Budapest",
                'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
                'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
                'Europe/Madrid'        => "(GMT+01:00) Madrid",
                'Europe/Paris'         => "(GMT+01:00) Paris",
                'Europe/Prague'        => "(GMT+01:00) Prague",
                'Europe/Rome'          => "(GMT+01:00) Rome",
                'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
                'Europe/Skopje'        => "(GMT+01:00) Skopje",
                'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
                'Europe/Vienna'        => "(GMT+01:00) Vienna",
                'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
                'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
                'Europe/Athens'        => "(GMT+02:00) Athens",
                'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
                'Africa/Cairo'         => "(GMT+02:00) Cairo",
                'Africa/Harare'        => "(GMT+02:00) Harare",
                'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
                'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
                'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
                'Europe/Kiev'          => "(GMT+02:00) Kyiv",
                'Europe/Minsk'         => "(GMT+02:00) Minsk",
                'Europe/Riga'          => "(GMT+02:00) Riga",
                'Europe/Sofia'         => "(GMT+02:00) Sofia",
                'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
                'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
                'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
                'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
                'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
                'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
                'Europe/Moscow'        => "(GMT+03:00) Moscow",
                'Asia/Tehran'          => "(GMT+03:30) Tehran",
                'Asia/Baku'            => "(GMT+04:00) Baku",
                'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
                'Asia/Muscat'          => "(GMT+04:00) Muscat",
                'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
                'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
                'Asia/Kabul'           => "(GMT+04:30) Kabul",
                'Asia/Karachi'         => "(GMT+05:00) Karachi",
                'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
                'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
                'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
                'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
                'Asia/Almaty'          => "(GMT+06:00) Almaty",
                'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
                'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
                'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
                'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
                'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
                'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
                'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
                'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
                'Australia/Perth'      => "(GMT+08:00) Perth",
                'Asia/Singapore'       => "(GMT+08:00) Singapore",
                'Asia/Taipei'          => "(GMT+08:00) Taipei",
                'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
                'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
                'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
                'Asia/Seoul'           => "(GMT+09:00) Seoul",
                'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
                'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
                'Australia/Darwin'     => "(GMT+09:30) Darwin",
                'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
                'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
                'Australia/Canberra'   => "(GMT+10:00) Canberra",
                'Pacific/Guam'         => "(GMT+10:00) Guam",
                'Australia/Hobart'     => "(GMT+10:00) Hobart",
                'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
                'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
                'Australia/Sydney'     => "(GMT+10:00) Sydney",
                'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
                'Asia/Magadan'         => "(GMT+12:00) Magadan",
                'Pacific/Auckland'     => "(GMT+12:00) Auckland",
                'Pacific/Fiji'         => "(GMT+12:00) Fiji",
            );
       return $timezones;
    }
    
    

    function getRandomNo() {
        list($usec, $sec) = explode(" ", microtime());
        $list = explode('.', ((float) $usec + (float) $sec));
        return $list[0];
    }
    
    function validateApiCall($data) {
        $result = array();
        $con = $this->connect();

        if (isset($data['api_type']) && isset($data['api_access_key']) && isset($data['api_secret'])) {
            $api_type = $data['api_type'];
            $api_access_key = md5($data['api_access_key']);
            $api_secret = md5($data['api_secret']);
            if(isset($data['user_id']) && trim($data['user_id']) != ""){
               $userid = $data['user_id'];
               $query = "SELECT `api_type` FROM `api_call` WHERE `api_type` = '$api_type' AND `api_access_key` = '$api_access_key'";
               $query_result = $con->query($query);
               $user_api_secret = $data['api_secret'];
               $queryuser = "SELECT `api_secret` FROM `users` WHERE `id` = '$userid' ANd `api_secret` = '".$user_api_secret."'"; 
               $query_result_users = $con->query($queryuser);
               if ($query_result->num_rows == 0 || $query_result_users->num_rows == 0) {
                     $result['success'] = 0;
                     $result['data'] = 'success';
                     $result['error'] = 1;
                     $result['error_msg'] = 'User secret key is invalid';
               } else {
                     $result['success'] = 1;
                     $result['data'] = 'success';
                     $result['error'] = 0;
                     $result['error_code'] = NULL;
               }  
            } else {
               $query = "SELECT `api_type` FROM `api_call` WHERE `api_type` = '$api_type' AND `api_access_key` = '$api_access_key' AND `api_secret` = '$api_secret'";
               $query_result = $con->query($query);
               if ($query_result->num_rows == 0) {
                     $result['success'] = 0;
                     $result['data'] = 'success';
                     $result['error'] = 1;
                     $result['error_code'] = 'Api secret is not valid';
               } else {
                     $result['success'] = 1;
                     $result['data'] = 'success';
                     $result['error'] = 0;
                     $result['error_code'] = NULL;
               }
            }
            
        } else {
            $result['success'] = 0;
            $result['data'] = NULL;
            $result['error'] = 1;
            $result['error_code'] = 'ER0001';
        }
        $con->close();
        return $result;
    }
    function SendEmail($from_email,$from_name,$to_email,$to_name,$subject,$content,$extra){
          $mail = new PHPMailer;
          $mail->setFrom($from_email, $from_name);
          $mail->addAddress($to_email, $to_name);
          $mail->Subject = $subject;
          $mail->isHTML(true); 
          $mail->Body = $content;
          if (!$mail->send()) {
              return "Mailer Error: " . $mail->ErrorInfo;
          } else {
              return "success";
          }
    }
    function getOneValueOfChoice($field, $table, $where, $return) {
        $result = NULL;
        $con = $this->connect();
        $query = "SELECT $field FROM $table " . (($where != NULL) ? ' WHERE ' . $where : '');
        $query_result = $con->query($query);
        if ($query_result->num_rows == 1 || $query_result->num_rows == 0) {
            $row = $query_result->fetch_assoc();
            $result = $row[$return];
        } else {
            while ($row = $query_result->fetch_assoc()) {
                $result[] = $row[$return];
            }
            $result = implode(',', $result);
        }
        $this->close();
        return $result;
    }

    function getMultipleValueOfChoice($fields, $table_name, $conditions) {
        $result = array();
        $con = $this->connect();
        $query = "SELECT $fields FROM $table_name " . (($conditions != NULL) ? ' WHERE ' . $conditions : '');
        $query_result = $con->query($query);
        while ($row = $query_result->fetch_assoc()) {
            $result[] = $row;
        }
        $con->close();
        return $result;
    }

    function replaceUnwantedChars($data, $status) {
        $result = '';
        if ($status == 1) {
            $result = trim(htmlspecialchars(htmlentities($data, ENT_QUOTES))); //ENCODE
        }
        if ($status == 2) {
            $result = trim(htmlspecialchars_decode(html_entity_decode($data, ENT_QUOTES))); //DECODE
        }
        return $result;
    }

    function createDropDown($table, $where, $value, $option, $title, $order_by, $order, $match) {
        $result = '';
        if ($title != NULL) {
            $result = '<option value="">' . $title . '</option>';
        }
        $con = $this->connect();
        $query = "SELECT * FROM `$table`" . (($where != '') ? 'WHERE ' . $where : '') . (($order_by != NULL) ? ' ORDER BY ' . $order_by . ' ' . $order : '');
        $query_result = $con->query($query);
        while ($row = $query_result->fetch_assoc()) {
            $row[$option] = $this->replaceUnwantedChars($row[$option], 2);
            $result .= '<option ' . (($match == $row[$value]) ? 'selected' : '') . ' value="' . $row[$value] . '">' . $row[$option] . '</option>';
        }
        $this->close();
        return $result;
    }

    function createDropDownForParentChild($table, $where, $value, $option, $title, $order_by, $order, $prefix, $match, $extra_clause = NULL) {
        $result = '';
        if ($title != NULL) {
            $result = '<option value="">' . $title . '</option>';
        }
        $con = $this->connect();
        if ($extra_clause != NULL) {
            if ($where == NULL) {
                $where = $extra_clause;
            } else {
                $where = $where . ' AND ' . $extra_clause;
            }
        }
        $query = "SELECT * FROM `$table`" . (($where != '') ? 'WHERE ' . $where : '') . (($order_by != NULL) ? ' ORDER BY ' . $order_by . ' ' . $order : '');
        $query_result = $con->query($query);
        while ($row = $query_result->fetch_assoc()) {
            $result .= '<option ' . (($match == $row[$value]) ? 'selected' : '') . ' value="' . $row[$value] . '">' . $prefix . $row[$option] . '</option>';
            $result .= $this->createDropDownForParentChild($table, 'pid=' . $row['id'], $value, $option, NULL, $order_by, $order, '--- ' . $prefix, $match, $extra_clause);
        }
        $this->close();
        return $result;
    }

    function myPagination($data) {
        $url = $data['url'];
        $current_page = $data['active_page'];
        $rc_per_page = $data['records_per_page'];
        $total_records = $data['total_records'];
        $start_from = $data['start_from'];
        $total_pages = ceil($total_records / $rc_per_page);
        $tmp_total_pages = $total_pages;
        if ($tmp_total_pages > 3) {
            $tmp_total_pages = 3;
        }
        $pagination = '<li class="paginate_button previous ' . (($current_page == 1) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><a href="' . $url . '&page=1' . '"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></a></li>
                       <li class="paginate_button previous ' . (($current_page == 1) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><a href="' . $url . '&page=' . ((($current_page - 1) == 0) ? '1' : $current_page - 1) . '"><i class="fa fa-chevron-left"></i></a></li>';
        $tmp_index = $current_page;
        if ($tmp_index > 1) {
            $tmp_index--;
        }
        for ($a = 1; $a <= $tmp_total_pages; $a++) {
            if ($tmp_index > $total_pages) {
                break;
            }
            $pagination .= '<li class="paginate_button ' . (($current_page == $tmp_index) ? 'active' : '') . '" aria-controls="dynamic-table" tabindex="0"><a href="' . $url . '&page=' . $tmp_index . '">' . $tmp_index . '</a></li>';
            $tmp_index++;
        }
        /*$pagination .= '<li class="paginate_button next ' . (($current_page == $total_pages || $total_pages == 0) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next">' . (($total_pages != 0) ? '<a href="' . $url . '&page=' . ((($tmp_index + 1) > $total_pages) ? $total_pages : $tmp_index + 1) . '"><i class="fa fa-chevron-right"></i></a>' : '<a href="#"><i class="fa fa-chevron-right"></i></a>') . '</li>
                        <li class="paginate_button next ' . (($current_page == $total_pages || $total_pages == 0) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next">' . (($total_pages != 0) ? '<a href="' . $url . '&page=' . $total_pages . '"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a>' : '<a href="#"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a>') . '</li>';*/
        $pagination .= '<li class="paginate_button next ' . (($current_page == $total_pages || $total_pages == 0) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next">' . (($total_pages != 0) ? '<a href="' . $url . '&page=' . ((($tmp_index + 1) > $total_pages) ? $total_pages : $current_page + 1) . '"><i class="fa fa-chevron-right"></i></a>' : '<a href="#"><i class="fa fa-chevron-right"></i></a>') . '</li>
                        <li class="paginate_button next ' . (($current_page == $total_pages || $total_pages == 0) ? 'disabled' : '') . '" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next">' . (($total_pages != 0) ? '<a href="' . $url . '&page=' . $total_pages . '"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a>' : '<a href="#"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a>') . '</li>';
        $result['showing-entries'] = ' Showing ' . (($start_from == 0) ? '1' : $start_from) . ' to ' . ((($start_from + $rc_per_page) > $total_records) ? $total_records : ($start_from + $rc_per_page)) . ' of ' . $total_records . ' entries';
        $result['pagination'] = $pagination;
        return $result;
    }

    function makeSpecialArray($task, $myarray, $mykey, $flag = 0, $sutra) {
        if ($task == 'makeoned') {
            $result = array();
            foreach ($myarray as $key => $value) {
                $result[] = $value;
                $last_key = (count($result) - 1);
                unset($result[$last_key][$mykey]);
                if (is_array($value[$mykey])) {
                    $result[] = $this->makeSpecialArray('makeoned', $value[$mykey], $mykey, 1, $sutra);
                }
            }
            if ($flag == 0) {
                $result = $this->makeNonNested($result, $sutra);
            }
            return $result;
        }
    }

    function makeNonNestedRecursive(array &$out, array $in, $sutra, $prefix = '') {
        foreach ($in as $k => $v) {
            if (is_array($v) && !isset($v[$sutra])) {
                $this->makeNonNestedRecursive($out, $v, $sutra, '--- ' . $prefix);
            } else {
                $v['value'] = $prefix . $v['value'];
                $out[$v['id']] = $v;
            }
        }
    }

    function makeNonNested(array $in, $sutra) {
        $out = array();
        $this->makeNonNestedRecursive($out, $in, $sutra);
        return $out;
    }

    function getTreeView($data) {
        // start assign variable ***
        $fields = $data['fields'];
        $table = $data['table'];
        $where_clause = $data['where_clause'];
        $key_to_order = $data['key_to_order'];
        $order = $data['order'];
        $key = $data['key'];
        $p_field = $data['p_field'];
        $p_value = $data['p_value'];
        $consider_p = $data['consider_p'];
        // End assign variable ***
        $result = NULL;
        $con = $this->connect();
        $query = "SELECT $fields FROM $table WHERE $p_field = $p_value " . (($where_clause != NULL) ? ' AND ' . $where_clause : '') . " ORDER BY $key_to_order $order";
        $query_result = $con->query($query);
        if ($query_result->num_rows != 0) {
            while ($row = $query_result->fetch_assoc()) {
                $result[$row[$key]] = $row;
                $result[$row[$key]]['childrens'] = $this->getTreeView(array('consider_p' => $consider_p, 'p_field' => $p_field, 'p_value' => $row[$consider_p], 'fields' => $fields, 'table' => $table, 'where_clause' => $where_clause, 'key_to_order' => $key_to_order, 'order' => $order, 'key' => $key));
            }
        }
        $con->close();
        return $result;
    }

    function arrayMagica($task, $data) {
        if ($task == 'makeOneDimensionalWithTree') {
            $content = $data['content'];
            $child_container_name = $data['child_container_name'];
            $add_to = $data['add_to'];
            $prefix = $data['prefix'];
            $key = $data['key'];
            $blank_array = array();
            $this->makeOneDimensional($blank_array, $content, $child_container_name, $add_to, $prefix, $key);
            return $blank_array;
        }
    }

    function makeOneDimensional(array &$blank_array, array $in, $child_container_name, $add_to, $prefix, $key) {
        foreach ($in as $k => $v) {
            if (is_array($v[$child_container_name])) {
                $v[$add_to] = $prefix . $v[$add_to];
                $tmp_ar = $v;
                unset($tmp_ar[$child_container_name]);
                $blank_array[$v[$key]] = $tmp_ar;
                $this->makeOneDimensional($blank_array, $v[$child_container_name], $child_container_name, $add_to, '--- ' . $prefix, $key);
            } else {
                $v[$add_to] = $prefix . $v[$add_to];
                $blank_array[$v[$key]] = $v;
            }
        }
    }

    function create_image_for_captcha() {
        global $image;
        $image = imagecreatetruecolor(200, 50) or die("Cannot Initialize new GD image stream");
        //$background_color = imagecolorallocate($image, 255, 255, 255);
        $background_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        $text_color = imagecolorallocate($image, 0x00, 0x00, 0x00);
        $line_color = imagecolorallocatealpha($image, 255, 0, 0, 91);
        $pixel_color = imagecolorallocate($image, 0xFF, 0xFF, 255);
        $grey = imagecolorallocate($image, 128, 128, 128);
        imagefilledrectangle($image, 1, 1, 198, 48, $background_color);
        for ($i = 0; $i < 3; $i++) {
            imageline($image, 0, rand() % 50, 200, rand() % 50, $line_color);
        }
        for ($i = 0; $i < 1000; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixel_color);
        }
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($letters);
        $letter = $letters[rand(0, $len - 1)];
        $font = 'arial.ttf';
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $word = "";
        for ($i = 0; $i < 6; $i++) {
            $letter = $letters[rand(0, $len - 1)];
            imagestring($image, 10, 5 + ($i * 30), 20, $letter, $text_color);
            imagestring($image, 10, 6 + ($i * 30), 20, $letter, $text_color);
            $word .= $letter;
        }
        $_SESSION['captcha_string'] = $word;
        $images = glob("*.png");
        foreach ($images as $image_to_delete) {
            @unlink($image_to_delete);
        }
        imagepng($image, "image" . $_SESSION['count'] . ".png");
        $this->display_captcha();
    }

    function display_captcha() {
        ?>
        <div>
            <div>
                <img src="image<?php echo $_SESSION['count'] ?>.png">
            </div>
        </div>
        <?php
    }

    function generateRandomString($length = 9) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    function checkdateisvalid($month, $day, $year) {
        try {
            return checkdate($month, $day, $year);
        } catch (Exception $e) {
            return false;
        }
    }
    
    function weekOfMonth($qDate) {
        $dt = strtotime($qDate);
        $day = date('j', $dt);
        $month = date('m', $dt);
        $year = date('Y', $dt);
        $totalDays = date('t', $dt);
        $weekCnt = 1;
        $retWeek = 0;
        for ($i = 1; $i <= $totalDays; $i++) {
            $curDay = date("N", mktime(0, 0, 0, $month, $i, $year));
            if ($curDay == 7) {
                if ($i == $day) {
                    $retWeek = $weekCnt + 1;
                }
                $weekCnt++;
            } else {
                if ($i == $day) {
                    $retWeek = $weekCnt;
                }
            }
        }
        return $retWeek;
    }
    
    function query_bulk($query, $queries) {
        $query = $query.implode(",", $queries);
        $con = $this->connect();
        $con->query($query);
        $con->close();
    }
}
$obj = new commonFunction();;
?>