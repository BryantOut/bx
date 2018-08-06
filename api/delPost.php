<?php
    //获取提交过来的post_id
    $post_id = $_GET['post_id'];

    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //3、设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //4、编写sql语句删除该条id的文章
    $sql = "DELETE FROM posts WHERE post_id = '$post_id'";

    //5、执行sql语句
    $res = mysqli_query($conn,$sql);

    //6、判断是否删除成功
    if (mysqli_affected_rows($conn)) {
        $response = ['code'=>200,'message'=>'删除成功'];
    } else {
        $response = ['code'=>-1,'message'=>'服务器忙，删除失败'];
    }
    
    //7、服务器返回给前台数据
    echo json_encode($response);
?>