<?php



require('db_init.php');
$_POST = json_decode(file_get_contents('php://input'), true);
if (isset($_POST['commId'])) {
    $return = array();
    $link = mysqli_connect(SERVER_NAME, DB_USER, DB_PASS, DB_NAME);
    if (!$link) {
        $return['message'] = "Faild connection!";
        $return['status'] = -1;
    } else {
       
        $query = "UPDATE comments SET likes=likes-1 WHERE id='" . $_POST['commId'] . "'";
        if (mysqli_query($link, $query)) {
            $return['message'] = "Все ок";
            $return['status'] = 0;
        } else {
            $return['message'] = "Все плохо";
            $return['status'] = -2;
        }
    }
} else {
    $return['message'] = "Все плохо";
    $return['status'] = -1;
}

echo json_encode($return);
