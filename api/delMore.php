<?php 
    //1、连接数据库
    $conn = mysqli_connect("127.0.0.1","root","root","bx");
    //2、判断是否成功连接数据库
    if (!$conn) {
        die("数据库连接失败……");
    }
    //3、设置字符集编码格式
    mysqli_set_charset($conn,"utf-8");

    //4、获取提交过来的数据
    $cat_ids = $_GET["cat_ids"];

    //5、编写sql语句，批量删除
    $sql = "DELETE FROM category WHERE cat_id in ($cat_ids)";

    //6、执行sql语句，批量删除
    $res = mysqli_query($conn,$sql);
    
    // 7、判断是否删除成功
    if (mysqli_affected_rows($conn)) {
        $response = ["code"=>200,"message"=>"批量删除成功"];
    }else {
        $response = ["code"=>-1,"message"=>"服务器忙"];
    }
    echo json_encode($response);
?>