<?php 
    include_once "../sqlHelper.php";

    // var_dump($_GET);

    //获取当前要删除的cat_id
    $cat_id = $_GET['cat_id'];
    //根据$cat_id删除数据库中该条数据
    //1、编写sql语句
    $sql = "delete from category where cat_id = $cat_id";
    //2、执行该条语句
    $res = cdu($sql,"bx");
    //3、判断是否删除成功
    if ($res) {
        $response = ["code"=>200,"message"=>"删除成功"];
        echo json_encode($response);
    }else {
        $response = ["code"=>-1,"message"=>"删除失败"];
        echo json_encode($response);
    }
?>  