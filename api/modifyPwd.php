<?php
    session_start();
    // print_r($_SESSION);
    $user_id = $_SESSION["user"]["user_id"];
    //获取提交过来的数据
    $old = $_POST['old'];
    $password = $_POST['password'];
    $confirmPwd = $_POST['confirmPwd'];

    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //3、设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //4、编写sql语句判断旧密码是否一致
    $sql = "SELECT * FROM users WHERE user_id = '$user_id' and password = '$old'";

    $res = mysqli_query($conn,$sql);
    //5、执行sql语句
    if (mysqli_num_rows($res)==0) {
        $response = ["code"=>-1,"message"=>"旧密码输入错误请重新输入"];
        echo json_encode($response);
        exit;
    }

    //执行到这一步说明可以修改数据库了
    $sql = "UPDATE users SET password='$password' WHERE user_id = $user_id";

    $res = mysqli_query($conn,$sql);

    if (mysqli_affected_rows($conn)>0) {
        $response = ["code"=>200,"message"=>"修改成功"];
    }else{
        $response = ["code"=>-1,"message"=>"服务器忙，请稍后再试"];
    }

    echo json_encode($response);
?>