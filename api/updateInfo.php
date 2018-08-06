<?php
    session_start();
    //获取当前用户的user_id
    // var_dump($_SESSION);
    $user_id = $_SESSION["user"]["user_id"];

    //获取当前昵称输入框内容
    $nickname = $_POST['nickname'];
    //获取当前个人简介输入框内容
    $bio = $_POST['bio'];
    //获取当前头像信息
    $avatarPath = $_POST['avatarPath'];    

    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //3、设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //获取旧头像信息，假如没有更换新头像，则引用原来的头像信息
    $sql2 = "SELECT avatar FROM users WHERE user_id = $user_id";
    $res2 = mysqli_query($conn,$sql2);
    $data = mysqli_fetch_assoc($res2);
    $oldAvatarPath = $data['avatar'];

    $avatarPath = trim($avatarPath)==""?$oldAvatarPath:$avatarPath;

    //4、编写sql语句用于更新
    $sql = "UPDATE users SET nickname= '$nickname', bio='$bio',avatar='$avatarPath' WHERE user_id = $user_id";

    //5、执行sql语句
    $res = mysqli_query($conn,$sql);

    

    //6、判断是否更新成功
    if (mysqli_affected_rows($conn)>0) {
        $response = ['code'=>200,'message'=>'更新成功'];
    }else {
        $response = ['code'=>-1,'message'=>'服务器忙'];
    }

    echo json_encode($response);
?>