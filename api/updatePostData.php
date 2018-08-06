<?php 
// var_dump($_POST);
//1.接收参数
$title = $_POST['title'];
$content = $_POST['content'];
// $feature = $_POST['url'];
$created = $_POST['created'];
$cat_id = $_POST['cat_id'];
$status = $_POST['status'];
$post_id = $_POST['post_id'];
//1、连接数据库
$conn = mysqli_connect("127.0.0.1","root","root","bx");
//2、判断是否成功连接数据库
if (!$conn) {
    die("数据库连接失败……");
}
//3、设置字符集编码格式
mysqli_set_charset($conn,"utf-8");

//bug--获取旧的图片路径
$sql2 = "SELECT t1.feature from posts t1 where post_id = $post_id";
//执行sql语句
$res2 = mysqli_query($conn,$sql2);
$dataOfOldUrl = mysqli_fetch_assoc($res2);
//判断是否有新的图片路径传过来，没有的话继续引用旧的图片路径
$feature = trim($_POST['url'])==""?$dataOfOldUrl['feature']:$_POST['url'];

//3.编写sql语句，
$sql = "update posts set title='$title',content='$content',feature='$feature',cat_id='$cat_id',created='$created',status='$status' where post_id = $post_id";
//4.执行sql语句
$res = mysqli_query($conn,$sql);
//5.判断是否成功，返回json数据
if( mysqli_affected_rows($conn) ){
	$response = ['code'=>200,'message'=>'编辑成功'];
}else{
	$response = ['code'=>-1,'message'=>'编辑失败'];
}
echo json_encode($response);