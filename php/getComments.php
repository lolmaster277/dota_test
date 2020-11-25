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
        $query = "SELECT * FROM comments WHERE id_news=? ORDER BY date DESC";

        $stmt=mysqli_stmt_prepare($link,$query);
        mysqli_stmt_bind_param($stmt,"i",$_POST['postId']);
        mysqli_stmt_execute($stmt);
        $comments_before = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
        
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
