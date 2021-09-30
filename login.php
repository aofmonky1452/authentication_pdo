<?php

    require_once('../connection/dbconfig.php');
    session_start();

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        try {
            date_default_timezone_set('Asia/Bangkok');
            $currentDate = date("Y-m-d H:i:s");

            $txt_username = $_POST['username'];
            $txt_password = $_POST['password'];

            $state = 0;
            $items_arr = array();
            $items_arr["result"] = array();

            $select_stmt = $db->prepare("   SELECT * FROM tbl_user 
                                            WHERE username = '".$txt_username."' ");
            $select_stmt->execute();
            
            try {
                while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $txt_password = $txt_password . $salt;
                    $txt_password = md5($txt_password);
                    if($txt_username == $username && $txt_password == $password) {
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['haslogin'] = "none";
                        $_SESSION['email'] = $email;
                        $items = array(
                            "status" => "success",
                            "code" => "000",
                            "id" => $id,
                            "accept" => $accept
                        );
                        array_push($items_arr["result"], $items);
                        echo json_encode($items_arr);
                    }
                    else {
                        $items = array(
                            "status" => "fail",
                            "code" => "002"
                        );
                        array_push($items_arr["result"], $items);
                        echo json_encode($items_arr);
                    }
                }
            }
            catch(PDOException $e) {
                $items = array(
                    "status" => "fail",
                    "code" => "003"
                );
                array_push($items_arr["result"], $items);
                echo json_encode($items_arr);
                echo $e->getMessage();
            }
        } catch(PDOException $e) {
            $items = array(
                "status" => "fail",
                "code" => "001"
            );
            array_push($items_arr["result"], $items);
            echo json_encode($items_arr);
            echo $e->getMessage();
        }
    }
    else {
        http_response_code(405);
    }


    

?>