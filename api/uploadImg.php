<?php
    // var_dump($_FILES);
    /* array(1) {
        ["file"]=>
        array(5) {
          ["name"]=>
          string(16) "1454414215-0.png"
          ["type"]=>
          string(9) "image/png"
          ["tmp_name"]=>
          string(22) "C:\Windows\phpBF3C.tmp"
          ["error"]=>
          int(0)
          ["size"]=>
          int(74547)
        }
      } */
      //获取文件后缀名
      $name = $_FILES["file"]["name"];
      $fileSuffix = strrchr($name,".");

      //给文件随机生成一个名字
      $fileNewName = time().rand(0,999).$fileSuffix;

      //获取临时文件地址
      $temp_name = $_FILES["file"]["tmp_name"];

      //当前文件在指定文件夹中的路径
      $fileCurrPath = "../static/assets/img/".$fileNewName;

      //将临时文件存储到指定文件
      if(move_uploaded_file($temp_name,$fileCurrPath)){
          //上传成功，需要返回文件的完整路径
          $response = ['code'=>200,'message'=>'上传头像成功','url'=>$fileCurrPath];
      }else {
          //上传头像失败
          $response = ['code'=>-1,'message'=>'上传头像失败'];
      }
      echo json_encode($response);  
?>