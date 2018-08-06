<?php 
    include_once "../sqlHelper.php";
    $name = $_GET['name'];
    
    //编写sql语句，查看数据库的表中是否已经存在相同类型的一项
    $sql = "select * from category where cat_name = '$name'";

    //通过数据库查询
    $res = read($sql,"bx");

    //判断是否已经存在，存在的话则提示用户所添加名称已经存在
    if ($res) {
        $response = ["code"=>-1,"message"=>'您所输入的名称已经存在，请重新输入'];
        echo json_encode($response);
        exit;
    }

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
    }

    //全都合法之后，执行下面代码将数据存进数据库
    //1、编写sql代码
    $sql = "INSERT INTO category VALUE (null,'$name','$slug')";
    //2、通过数据库的方法插入数据
    $res = cdu($sql,"bx");
    // var_dump($res);
    //3、判断是否插入成功
    if ($res==1){
        $name = $_GET['name'];
        $sql = "select * from category where cat_name = '$name'";
        $getIdLink=read($sql,"bx");
        // print_r($getIdLink);
        $insert_id = $getIdLink[0]['cat_id'];
        $response = ["code"=>200,"message"=>'添加成功','insert_id'=>$insert_id];
        echo json_encode($response);
        exit;    
    }else {
        //插入失败
        $response = ["code"=>-1,"message"=>'服务器忙'];
        echo json_encode($response);
        exit;
    }
?>