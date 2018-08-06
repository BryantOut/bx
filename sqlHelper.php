<?php 
    //增删改
    function cdu($sql,$db) {
        //1、连接数据库
        $conn = mysqli_connect("127.0.0.1","root","root",$db);
        //2、判断是否成功连接数据库
        if (!$conn) {
            die("数据库连接失败……");
        }
        //设置字符集编码格式
        mysqli_set_charset($conn,"utf-8");
        
        //根据sql语句对数据库的表格进行增删改等业务操作
        $res = mysqli_query($conn,$sql);
       
        //关闭数据库
        mysqli_close($conn);

        //将结果返回
        return $res;
    }

    //测试
    // cdu("insert into fav values (null,'杨超越','火箭少女101',null,null,null)",'db1');

    //查询
    function read ($sql,$db) {
        //1、连接数据库
        $conn = mysqli_connect("127.0.0.1","root","root",$db);
        //2、判断是否成功连接数据库
        if (!$conn) {
            die("数据库连接失败……");
        }
        //设置字符集编码格式
        mysqli_set_charset($conn,"utf-8");

        //查询数据库指定的表
        // $res = mysqli_query($conn,"select*from fav");
        $res = mysqli_query($conn,$sql);

        //设置返回的字符串
        $arr = [];
        if (!$res) {
            //判断是否查询成功
            $str = "对不起，查询不到该表……";
        }else if (mysqli_num_rows($res)==0) {
            //判断是否为空表格，为空表格的话就没有必要继续往下读
            $str = "所查询表格为空……";
        }else {
            $arr = [];
            while ($data = mysqli_fetch_assoc($res)) {
                  $arr[] = $data;  
            }
            // var_dump($arr);
            mysqli_close($conn);
            return $arr;
        }

        mysqli_close($conn);
        return $arr;
    }

    //测试
    // var_dump(read("select*from fav",'db1'));
?>