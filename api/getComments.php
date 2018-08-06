<?php
    include_once "../sqlHelper.php";

    //获取提交过来的当前页码
    $page = $_GET['page'];
    $pageSize = 10;
    $offset = ($page-1)*$pageSize;
    //编写sql语句--目的计算所有评论的总数
    $sql1 = "SELECT COUNT(*) as commentsCount FROM comments ";
    $res2 = read($sql1,"bx");
    $commentsCount = $res2[0]['commentsCount'];
    //总共有多少页
    $pageCount = ceil($commentsCount/$pageSize);
    //编写sql语句
    $sql = "SELECT t1.*,t2.title 
    FROM comments t1
    JOIN posts t2
    ON t1.post_id = t2.post_id
    LIMIT $offset,$pageSize";

    //执行sql语句
    $data = read($sql,"bx");

    //判断是否查询成功
    if ($data) {
        $response = ['code'=>200,'message'=>'提取成功','data'=>$data];
    } else {
        $response = ['code'=>-1,'message'=>'提取失败'];
    }

    echo json_encode($response);
?>