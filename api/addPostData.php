<?php
    // var_dump($_POST);
    //获取提交过来的数据
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created = $_POST['created'];
    $status = $_POST['status'];
    $url = $_POST['url'];
    $cat_id = $_POST['cat_id'];

    session_start();
    $user_id = $_SESSION['user']['user_id'];
    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //3、设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //4、编写sql语句,执行sql
    $sql = "insert into posts(title,content,created,feature,status,cat_id,user_id) 
    values('$title','$content','$created','$url','$status','$cat_id','$user_id')";

    mysqli_query($conn,$sql);

    //5、判断数据是否成功，返回json数据
    if ( mysqli_affected_rows($conn) ) {
        $response = ['code'=>200,'message'=>'添加文章成功'];
        
    } else {
        $response = ['code'=>-1,'message'=>'添加文章失败'];
    }

    echo json_encode($response);
?>