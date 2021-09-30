<?php

    require_once('../connection/dbconfig.php');

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        try {
            date_default_timezone_set('Asia/Bangkok');
            $currentDate = date("Y-m-d H:i:s");

            $txt_username = $_POST['username'];
            $txt_password = $_POST['password'];
            $txt_email = $_POST['email'];
            $txt_phone = $_POST['phone'];
            $txt_date = $_POST['date_dob'];
            $txt_month = $_POST['month_dob'];
            $txt_year = $_POST['year_dob'];
            $txt_accpet = $_POST['accpet'];
            $pda = $_POST['payday'];

            $txt_ip = $_POST['txtip'];
            $txt_loca = $_POST['txtloca'];
            $txt_cou = $_POST['txtcou'];
            $txt_usg = $_POST['txtusg'];

            $state = 0;
            $items_arr = array();
            $items_arr["result"] = array();

            function generateRandomString($length = 10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            
            $username_db = '';
            $email_db = '';
            $select_stmt = $db->prepare("SELECT * FROM tbl_user WHERE username = '".$txt_username."' ");
            $select_stmt->execute();
            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $username_db = $username;
                $email_db = $email;
            }
            if($username_db == $txt_username) {
                $items = array(
                    "status" => "fail",
                    "code" => "001"
                );
                array_push($items_arr["result"], $items);
                echo json_encode($items_arr);
            }
            else if($email_db == $txt_email) {
                $items = array(
                    "status" => "fail",
                    "code" => "002"
                );
                array_push($items_arr["result"], $items);
                echo json_encode($items_arr);
            }
            else {

                for ($i = 0; $i < 15; $i++) {
                    if ($i == 14) {
                        $oldval = $oldval . '?';
                    } else {
                        $oldval = $oldval . '?,';
                    }
                }

                $salt = generateRandomString();
                $txt_password = $txt_password . $salt;
                $txt_password = md5($txt_password);


                $query = "INSERT INTO tbl_user (`username`, `password`, `salt`, `pd`, `email`, `phone`, `date_dob`, `month_dob`, `year_dob`, `accept`, `update_time`, `ip`, `loca`, `cou`, `usg` ) VALUES (" . $oldval . ")";
                $stmt = $db->prepare($query);
                if ($stmt->execute([
                    $txt_username, $txt_password, $salt, $pda , $txt_email, $txt_phone, $txt_date, $txt_month, $txt_year, "1", $currentDate, $txt_ip, $txt_loca, $txt_cou, $txt_usg
                    // $txt_username, , $txt_password, $salt, $txt_email, $txt_phone, $txt_date, $txt_month, $txt_year, "1"
                ])) {
                    $items = array(
                        "status" => "success",
                        "code" => "000"
                    );
                    array_push($items_arr["result"], $items);
                    echo json_encode($items_arr);
                } 
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
    else {
        http_response_code(405);
    }


    

?>