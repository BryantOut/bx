<?php
    include_once "../sqlHelper.php";
    // print_r($_GET);
    //1、获取当前页数
    //因为刚加载文章数据的时候没有带page参数，给设置一个默认为1
    $page = isset($_GET["page"])?$_GET["page"]:1;

    //1.1、自定义每页显示的条数
    $pagesize = 10;
    //1.2、定义limit查询的起始位置
    $offset = ($page-1)*$pagesize;

    //9.筛选模块
    //9.1获取提交过来的数据
    $cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:"all";
    $status = isset($_GET['status'])?$_GET['status']:"all";

    //组装where查询条件
    //where = '1=1' 条件永远为真，当后面没有and拼接条件的时候，sql就这样 ：select*from posts where
    //where后面没有条件就会报错，where后面拼接1=1,就避免这种错，这种语句一般用在搜索中，主要防止查询条件报错
    $where = '1=1';//用于拼接查询条件
    if ( $cat_id != 'all' ) {
        $where .= " and t1.cat_id = $cat_id ";
    }
    if ( $status != 'all' ) {
        $where .= " and t1.status = '$status'";
    }


     //2、编写sql语句查询所需数据
    $sql = "SELECT
     t1.*,t2.nickname,t3.cat_name
    FROM
     posts t1
    LEFT JOIN users t2
    on
     t1.user_id = t2.user_id
    LEFT JOIN category t3
    ON
     t1.cat_id = t3.cat_id
    WHERE $where
    ORDER BY t1.post_id DESC
    LIMIT $offset,$pagesize";

     //3、执行sql语句
     $data = read($sql,"bx");

     //4、定义sql语句获取文章总数,算出分页码数
     $sql2 = "SELECT count(*) postsCount FROM posts t1 WHERE $where";
    
     //执行sql语句
     $res2 = read($sql2,"bx");
     $postsCount = $res2[0]["postsCount"];
     $pageCount = ceil($postsCount/$pagesize);
    //  var_dump($postCount);
    //  array(1) {
    //     [0]=>
    //     array(1) {
    //       ["postsCount"]=>
    //       string(4) "1004"
    //     }
    //   }
    //  echo json_encode($data);

     //5、响应json数据给前端页面进行渲染
     if ($data) {
        $response = ['code'=>200,'message'=>'获取数据成功','data'=>$data,'pageCount'=>$pageCount];
     } else {
         $response = ['code'=>-1,'message'=>'获取数据失败'];
     }

     echo json_encode($response);
?>