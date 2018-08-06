<?php include_once "../sqlHelper.php"?>
<?php
    //当前的cat_id
    $cat_id = $_GET["cat_id"];
    //当前页面最后一篇文章的post_id
    $lastPostId = $_GET["lastPostId"];

    print_r($cat_id,$lastPostId);
    
    //编写sql语句
    $sql = "SELECT t1.*,t3.nickname,t2.cat_name,
    (SELECT count(*) FROM comments t4 where post_id = t1.post_id) as commentCount
    FROM posts t1
    LEFT JOIN category t2 on t1.cat_id=t2.cat_id
    LEFT JOIN users t3 on t1.user_id = t3.user_id
    WHERE t1.cat_id = $cat_id and t1.post_id<$lastPostId
    ORDER BY post_id desc
    limit 5";
    $morePostData = read($sql,"bx");

    if ($morePostData) {
        $res = [
            'code'=>200,
            'data'=>$morePostData,
            'message'=>'加载成功'
        ];
    }else {
        $res = [
            'code'=>-1,
            'data'=>[],
            'message'=>'加载超时'
        ];
    }

    echo json_encode($res);
?>