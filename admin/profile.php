<?php 
  //判断服务器的session中是否存了相关数据
  session_start();//一定要先开启
  // var_dump($_SESSION['user']);
  if (!isset($_SESSION["user"])) {
    header("location:./login.php");
    // print_r("请先登录");
    exit;
  }
  
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>

<body>

  <div class="main">
    <!-- <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="profile.php">
            <i class="fa fa-user"></i>个人中心</a>
        </li>
        <li>
        <a href="../api/logout.php" onclick="return confirm('确认退出吗？')">
            <i class="fa fa-sign-out"></i>退出</a>
        </li>
      </ul>
    </nav> -->
    <?php include_once "./commonNav.php"?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="alert alert-success" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <input type="hidden">
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="/static/assets/img/default.png" id="avatarImg">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="w@zce.me" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="汪磊" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <span type="submit" class="btn btn-primary" id="updateBtn">更新</span>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php 
    include_once "./commonAside.php";
  ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>

    //给上传头像文本域注册onChange()事件
    $("#avatar").on("change", function () {
      //获取到文件的上传信息
      var file = this.files[0];
      //html5的一个特征，利用fromData表单对象，可以用来传递二进制数据（文件流）
      var fromdata = new FormData();
      //append('键','值')
      fromdata.append('file', file);
      if (file) {
        //有文件上传，发送ajax请求，通过php帮助我们处理上传文件
        $.ajax({
          url: "../api/uploadImg.php",
          type: "post", //上传文件只能是post
          data: fromdata,
          contentType: false, //上传文件不可以指定数据类型
          processData: false, //对数据不进行数据的序列化
          dataType: "json",
          success: function (res) {
            if (res.code == 200) {
              $("#avatarImg").attr("src", res.url);
              $("input[type=hidden]").attr("value", res.url);
            }
          }
        });
      }
    });

    //为昵称输入框注册失去焦点事件
    $("#nickname").on("blur", function () {
      // console.log('触发鼠标弹起事件');
      var nickname = $(this).val();
      //1、判断名称输入框是否为空
      if ($.trim(nickname) == "") {
        $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>昵称不能为空</strong>');
        return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
      }
    });

    //为简介输入框注册失去焦点事件
    $("#bio").on("blur", function () {
      // console.log('触发鼠标弹起事件');
      var bio = $(this).val();
      //1、判断简介输入框是否为空
      if ($.trim(bio) == "") {
        $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>简介不能为空</strong>');
        return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
      }
    });

    //为更新按钮注册点击事件
    $("#updateBtn").on("click", function () {
      //获取当前昵称输入框内容
      var nickname = $("#nickname").val();
      //获取当前个人简介输入框内容
      var bio = $("#bio").val();
      //获取当前头像信息
      var avatarPath = $("input[type=hidden]").attr("value");

      // avatarPath=$.trim(avatarPath)==""?$oldImgPath:avatarPath;

      //发送ajax请求
      $.ajax({
        dataType: "json",
        type: "post",
        url: "../api/updateInfo.php",
        data: {
          nickname: nickname,
          bio: bio,
          avatarPath: avatarPath
        },
        beforeSend: function () {
          //1、判断名称输入框是否为空
          if ($.trim(nickname) == "") {
            $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>昵称不能为空</strong>');
            return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
          }
          //2、判断简介输入框是否为空
          if ($.trim(bio) == "") {
            $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>简介不能为空</strong>');
            return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
          }
        },
        success: function (res) {
          if (res.code==200){
            $(".alert-success").fadeIn(500).html("<strong>"+res.message+"</strong>").delay(1000).fadeOut(500);
          }
        }
      });
    });

    //从主页跳转过来的时候将session的数据渲染到页面
    $.ajax({
      dataType:"json",
      type:"get",
      url:"../api/getUser.php",
      success:function(res){
        // console.log(res);
        //设置当前昵称输入框内容
        $("#nickname").val(res.data.nickname);
        //设置当前个人简介输入框内容
        $("#bio").val(res.data.bio);
        //设置当前头像
        $("#avatarImg").attr("src",res.data.avatar);
        
      }
      ,error:function(){
        console.log("失败");
      }
    });
  </script>
</body>

</html>