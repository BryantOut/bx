<?php 
    //获取提交的邮箱信息
    $email = $_GET["email"];

     //1、连接数据库
     $conn = mysqli_connect("127.0.0.1","root","root","bx");
     //2、判断是否成功连接数据库
     if (!$conn) {
         die("数据库连接失败……");
     }
     //设置字符集编码格式
     mysqli_set_charset($conn,"utf-8");

    //编写sql语句，查询数据库中是否已经存在相同的邮箱
    $sql = "SELECT * FROM users WHERE email = '$email'";

    //执行改sql语句
    $res = mysqli_query($conn,$sql);
    // var_dump(mysqli_num_rows($res));
    //判断是否查询成功
    if (mysqli_num_rows($res)==0) {
        $response = ["code"=>200,"message"=>"邮箱未被注册，可以使用"];
    }else {
        $response = ["code"=>-1,"message"=>"邮箱已被注册"];
    }
    echo json_encode($response);
?>