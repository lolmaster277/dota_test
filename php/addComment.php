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
        $query = "INSERT INTO comments (parent_id,id_news,date,user_name,text) VALUES (?,?,?,?,?) ";
     
        $date=date('Y-m-d H:i:s');
        $stmt=mysqli_prepare($link,$query);
        mysqli_stmt_bind_param($stmt,"iisss",$_POST['commentId'],$_POST['postId'],$date,$_POST['user_name'],$_POST['comm_text']);
        if(mysqli_stmt_execute($stmt)){
            if($_POST['sort']=='new'){
                $query = "SELECT * FROM comments WHERE id_news=? AND parent_id=? ORDER BY date DESC";

            }else if($_POST['sort']=='old'){
                $query = "SELECT * FROM comments WHERE id_news=? AND parent_id=? ORDER BY date ASC";

            }else if($_POST['sort']=='popular'){
                $query = "SELECT * FROM comments WHERE id_news=? AND parent_id=? ORDER BY likes";

            }else{
                exit(-1);
            }
            mysqli_stmt_close($stmt);
        
            $stmt=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($stmt,"ii",$_POST['postId'],$_POST['commentId']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $comments_before = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $return['comments'] = array();
            //Группировка по родителям
            foreach ($comments_before as $row) {
                $return['comments'][$row['parent_id']][] = $row;
            }

            mysqli_stmt_close($stmt);
            mysqli_close($link);
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
