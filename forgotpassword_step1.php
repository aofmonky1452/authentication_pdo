<?php
    require_once('../connection/dbconfig.php');

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $txt_username = $_POST['username'];
        $txt_email = $_POST['email'];

        $select_stmt = $db->prepare("SELECT * FROM tbl_user WHERE username = '".$txt_username."' AND email = '".$txt_email."' ");
        $select_stmt->execute();

        $data_arr = array();
        $data_arr["result"] = array();

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
                $data_items = array(
                    "status" => "success"
                );
                array_push($data_arr["result"], $data_items);
                echo json_encode($data_arr);
        }
        http_response_code(200);
        
    }
    else if($_SERVER['REQUEST_METHOD'] == "GET") {
        echo 'Reject';
    }
    else {
        http_response_code(405);
    }
?>