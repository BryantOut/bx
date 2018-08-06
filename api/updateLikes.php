<?php include_once "../sqlHelper.php"?>
<?php 
    // 获取当前页面的post_id
    $post_id = $_GET["post_id"];
    // echo $post_id;
    //编写sql语句
    $sql = "select * from posts where post_id = $post_id";
    //查询数据库
    $res = read($sql,"bx");

    $oldLikes = $res[0]['likes'];

    //重新编写更新的sql语句
    $sql = "update posts set likes =likes+1 where post_id = $post_id";

    $res = cdu($sql,"bx");

    if ($res) {
        $response = ["code"=>200,"mesgess"=>'更新成功',"data"=>$oldLikes+1];
    }else {
        $response = ["code"=>-1,"mesgess"=>'更新失败'];
    }

    echo json_encode($response);
?>