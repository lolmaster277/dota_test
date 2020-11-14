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
        $query = "INSERT INTO comments (parent_id,id_news,date,user_name,text) VALUES ('".$_POST['commentId']."','".$_POST['postId']."','".date('Y-m-d H:i:s')."','".$_POST['user_name']."','".$_POST['comm_text']."') ";
        if(mysqli_query($link, $query)){
            if($_POST['sort']=='new'){
                $query = "SELECT * FROM comments WHERE id_news='" . $_POST['postId'] . "' AND parent_id='".$_POST['commentId']."' ORDER BY date DESC";

            }else if($_POST['sort']=='old'){
                $query = "SELECT * FROM comments WHERE id_news='" . $_POST['postId'] . "' AND parent_id='".$_POST['commentId']."' ORDER BY date ASC";

            }else if($_POST['sort']=='popular'){
                $query = "SELECT * FROM comments WHERE id_news='" . $_POST['postId'] . "' AND parent_id='".$_POST['commentId']."' ORDER BY likes";

            }else{
                exit(-1);
            }
            $result = mysqli_query($link, $query);
            $comments_before = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $return['comments'] = array();
            //Группировка по родителям
            foreach ($comments_before as $row) {
                $return['comments'][$row['parent_id']][] = $row;
            }
        }else{
            $return['message'] = "Все плохо".$query;
            $return['status'] = -2;
        }
        
    }
} else {
    $return['message'] = "Все плохо";
    $return['status'] = -1;
}

echo json_encode($return);
