<?php 

    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //接收传过来的数据
    $cat_id = $_GET['cat_id'];
    $name = $_GET['name'];
    $slug = $_GET['slug'];

    //编写检查是否有除自身id意外名字或是类名冲突的sql语句
    $sql = "SELECT *FROM category WHERE cat_name = '$name' and cat_id != '$cat_id'";   
    
    //执行sql语句
    $res1 = mysqli_query($conn,$sql);
    if (mysqli_num_rows($res1)) {
        $response = ["code"=>-1,"message"=>"名字以被使用……"];
        exit;
    }

    //编写更新sql语句
    $sql = "UPDATE category SET cat_name = '$name',classname='$slug' WHERE cat_id = '$cat_id'";

    //根据sql语句对数据库的表格进行增删改等业务操作
    mysqli_query($conn,$sql);

    //判断是否更新成功
    if (mysqli_affected_rows($conn)>=0) {
        $response = ["code"=>200,"message"=>"更新成功"];
        
    } else {
        $response = ["code"=>-1,"message"=>"服务器忙"];
       
    }

    echo json_encode($response);
    //判断是否更新成功
?>