<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <style>
    .alert-danger{
      position: absolute;
      top: 32px;
      left: -39px;
    }
    .alert-success {
      position: absolute;
      top: 32px;
      left: -39px;
    }
  </style>
</head>
<body>
  <div class="login">
    <form class="login-wrap">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong> 用户名或密码错误！
      </div>
      <div class="alert alert-success" style="display:none">
        <strong>错误！</strong> 用户名或密码错误！
      </div>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <a class="btn btn-primary btn-block" id="loginBtn" href="javascript:;">登 录</a>
    </form>
  </div>
  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script>
    //为邮箱输入框注册失去焦点事件
    $("#email").on("blur", function () {
    // console.log('触发鼠标弹起事件');
    var email = $(this).val();
    //1、判断名称输入框是否为空
    if ($.trim(email) == "") {
              $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>邮箱不能为空</strong>');
              return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
            }
            //2、判断邮箱是否合法
            //获取提交的邮箱信息

            //邮箱校验的正则表达式
            var reg = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;

            // console.log(reg.test(email));

            if (!reg.test(email)) {
              $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>邮箱格式不合法</strong>');
              return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
    }
  });

  //为密码输入框注册失去焦点事件
  $("#password").on("blur", function () {
    // console.log('触发鼠标弹起事件');
    var password = $(this).val();
    //1、判断名称输入框是否为空
    if ($.trim(password) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>密码不能为空</strong>');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        } 
  });

  //为登录按钮注册点击事件
  $("#loginBtn").on("click",function(){
    var email = $("#email").val();
    var password = $("#password").val();
    // console.log("登录按钮被触发");
    $.ajax({
      dataType:"json",
      type:"post",
      url:"../api/login.php",
      data:{
        email:email,
        password:password
      },
      beforeSend:function(){
        //1、判断名称输入框是否为空
        if ($.trim(email) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>邮箱不能为空</strong>');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
         //2、判断名称输入框是否为空
         if ($.trim(password) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>密码不能为空</strong>');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
        
      },
      success:function(res){
        if (res.code == 200) {
          console.log("登录成功");
          // header('location:./index.php');
          // window.location.href = "location:./index.php";
          location.href = "./index.php";
        }else {
          console.log("登录失败");
        }
      },
      error:function(){}
    });
  });
  </script>
</body>
</html>
