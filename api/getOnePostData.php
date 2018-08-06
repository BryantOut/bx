<?php
include_once "../sqlHelper.php";
//获取提交过来的post_id
$post_id = $_GET['post_id'];
//编写另一条sql语句，读取该post_id的数据渲染页面
$sql2 = "SELECT * FROM posts WHERE post_id = $post_id"; 
  
$postInfo = read($sql2,"bx");

if ($postInfo) {
    $response = ['code'=>200,'message'=>'success','data'=>$postInfo[0]];
} else {
    $response = ['code'=>-1,'message'=>'fail','data'=>[] ];
}

echo json_encode($response);
?>