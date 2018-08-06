<?php 
    //获取提交过来的邮箱和密码
    $email = $_POST['email'];
    $password = $_POST['password'];


     //1、连接数据库
     $conn = mysqli_connect("127.0.0.1","root","root","bx");
     //2、判断是否成功连接数据库
     if (!$conn) {
         die("数据库连接失败……");
     }
     //设置字符集编码格式
     mysqli_set_charset($conn,"utf-8");

     //编写sql语句查询邮箱账户密码是否正确
     $sql = "SELECT * FROM users WHERE email = '$email' and password = '$password'";

     //执行sql代码
     $res = mysqli_query($conn,$sql);


    //判断是否查询成功
    if (mysqli_num_rows($res)) {
        //补充
        //获取数据
	    $userInfo = mysqli_fetch_assoc($res);


        $response = ["code"=>200,"message"=>"登录成功"];
        //session是存在与服务器，是在服务器中创建一个文件来存储数据
        //这个文件同时会生成一个唯一的sessionID，会将这个session返回给浏览器
        session_start();//一定要先开启session

        $_SESSION['user'] = array(
            'nickname'=>$userInfo['nickname'],
            'user_id'=>$userInfo['user_id'],
            'avatar'=>$userInfo['avatar'],
            'email'=>$userInfo["email"],
            'password'=>$userInfo["password"],
            'islogin'=>true
        );
    }else {
        $response = ["code"=>-1,"message"=>"账号或密码错误"];
    }
    echo json_encode($response);
?>