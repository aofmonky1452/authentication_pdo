<?php
    session_start();
    require_once('../connection/dbconfig.php');

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $newpassword = $_POST['password'];
        $txt_username =  $_POST['username'];

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $salt = generateRandomString();
        $newpassword = $newpassword . $salt;
        $newpassword = md5($newpassword);

        $query = "UPDATE `tbl_user` SET `password` = '".$newpassword."', `salt` = '".$salt."' WHERE username = '".$txt_username."' ";
        $stmt = $db->prepare($query);
        $stmt->execute();

        http_response_code(200);

    }
    else {
        http_response_code(405);
    }



?>