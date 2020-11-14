<?php



require('db_init.php');
$_POST = json_decode(file_get_contents('php://input'), true);
if (isset($_POST['postId'])) {
    $return = array();
    $link = mysqli_connect(SERVER_NAME, DB_USER, DB_PASS, DB_NAME);
    if (!$link) {
        $return['message'] = "Faild connection!";
        $return['status'] = -1;
    } else {

        $return['message'] = "Все ок";
        $return['status'] = 0;
        //Один запрос в базу
        $query = "SELECT * FROM comments WHERE id_news='" . $_POST['postId'] . "' ORDER BY date DESC";
        $result = mysqli_query($link, $query);
        $comments_before = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $return['comments'] = array();
        //Группировка по родителям
        foreach ($comments_before as $row) {
            $return['comments'][$row['parent_id']][] = $row;
        }
    }
} else {
    $return['message'] = "Все плохо";
    $return['status'] = -1;
}

echo json_encode($return);
