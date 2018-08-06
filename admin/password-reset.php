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
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <style>
    .alert{
      position: fixed;
      top: 32px;
      left: 382px;
      z-index: 200;
    }
  </style>
</head>
<body>
  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.html"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.html"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
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
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirmPwd" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirmPwd" class="form-control" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <span class="btn btn-primary" id="modifyPwd">修改密码</span>
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
</body>
<script>
 
  var confirmPwd;
  var old;
  var password;
  //为修改密码文本框注册失去焦点事件
  $("#old").on("blur",function () {
    //获取修密码文本框中的内容
    old = $(this).val();
    //1、判断名称输入框是否为空
    if ($.trim(old) == "") {
      $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>旧密码必填');
      return;
    }
  });

  //为修改密码文本框注册失去焦点事件
  $("#password").on("blur",function () {
    //获取新密码文本框中的内容
    password = $(this).val();
    //1、判断名称输入框是否为空
    if ($.trim(password) == "") {
      $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>新密码必填');
      return;
    }

    //密码必须由6-16个英文字母和数字的字符串组成！
    var reg = /^[\w]{6,12}$/;

    if (reg.test(password)) {
      $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>安全</strong>密码合法');
    }else {
      $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>密码的格式为6-12位，只能是字母、数字和下划线');
    }
  });

  //为确认密码文本框注册失去焦点事件
  $("#confirmPwd").on("blur",function () {
    //获取确认密码文本框中的内容
    confirmPwd = $(this).val();
    //1、判断确认密码输入框是否为空
    if ($.trim(this) == "") {
      $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>旧密码必填');
      return false;
    }
    
    if (confirmPwd!=password) {
      $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>两次输入的密码不一致');
      return false;
    }
  });

  //为修改密码按钮注册点击事件
  $("#modifyPwd").on("click",function(){
    $.ajax({
      dataType:"json",
      type:"post",
      url:"../api/modifyPwd.php",
      data:{
        old:old,
        password:password,
        confirmPwd:confirmPwd
      },
      beforeSend:function(){
        //1、判断名称输入框是否为空
        if ($.trim(old) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>旧密码必填');
          return false;
        }
        //2、判断新密码输入框是否为空
        if ($.trim(password) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>新密码必填');
          return false;
        }
        //密码必须由6-16个英文字母和数字的字符串组成！
        var reg = /^[\w]{6,12}$/;

        if (!reg.test(password)) {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>密码的格式为6-12位，只能是字母、数字和下划线');
          return false;
        }

        //3、判断确认密码输入框是否为空
        if ($.trim(confirmPwd) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>旧密码必填');
          return false;
        }
        
        if (confirmPwd!=password) {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>两次输入的密码不一致');
          return false;
        }
      },
      success:function(res){
        if (res.code==200) {
          $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>成功！</strong>修改完成');
        }else {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>'+res.message+'');
        }
      },
      error:function(){
        console.log("失败");
      },
      complete:function(){}
    });
  });
</script>
</html>
