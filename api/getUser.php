<?php 
    session_start();

    $user_id = $_SESSION['user']["user_id"];

    include_once("../sqlHelper.php");

     //1、连接数据库
    //  $conn = mysqli_connect("127.0.0.1","root","root","bx");
     //2、判断是否成功连接数据库
    //  if (!$conn) {
    //      die("数据库连接失败……");
    //  }
     //3、设置字符集编码格式
    //  mysqli_set_charset($conn,"utf-8");

     //4、编写sql语句查询,并执行
     $sql = "SELECT *FROM users WHERE user_id = $user_id";
    //  $res = mysqli_query($conn,$sql);

    //5、因为query方法返回的结束是一个二维数组，通过下标0可以取出对应的数据
    // $data = $res[0];   
    $data = read($sql,"bx");
    //6、返回json数据
    $response = ["code"=>200,"data"=>$data[0]];

    echo json_encode($response);
?>