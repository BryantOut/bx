<?php
    include_once "../sqlHelper.php";
    $slug = $_GET['slug'];
    
    //编写sql语句，查看数据库的表中是否已经存在相同类型的一项
    $sql = "select * from category where classname = '$slug'";

    //通过数据库查询
    $res = read($sql,"bx");

    //判断是否已经存在，存在的话则提示用户所添加类名已经存在
    if ($res) {
        $response = ["code"=>-1,"message"=>'您所输入的类名已经存在，请重新输入'];
        echo json_encode($response);
        exit;
    }else {
        $response = ["code"=>200,"message"=>'您所输入的类名未被使用，可以使用'];
        echo json_encode($response);
        exit;
    }
?>