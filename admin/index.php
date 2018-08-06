<?php 
  //判断服务器的session中是否存了相关数据
  session_start();//一定要先开启
  // var_dump($_SESSION['user']);
  if (!isset($_SESSION["user"])) {
    header("location:./login.php");
    // print_r("请先登录");
    exit;
  }
  
  //给当前页设置一个标志
  $visitor = 'index';
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
        <li><a href="./profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="../api/logout.php" onclick="return confirm('确认退出吗？')"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav> -->
    <?php include_once "./commonNav.php"?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group" id="count">
              <!-- 准备一个模板 -->
              <script type="text/x-art-template" id="tmpl">
                <li class="list-group-item"><strong>{{ res.postsCount }}</strong>篇文章（<strong>{{  res.draftedCount }}</strong>篇草稿）</li>
                <li class="list-group-item"><strong>{{  res.catsCount }}</strong>个分类</li>
                <li class="list-group-item"><strong>{{  res.commentsCount }}</strong>条评论（<strong>{{  res.heldCount }}</strong>条待审核）</li>
              </script>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php 
    include_once "./commonAside.php";
  ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <script>
    //发送ajax请求，将请求回来的数据渲染页面
    $.ajax({
      dataType:"json",
      type:"get",
      url:"../api/getStatisticsData.php",
      success:function(res){
        //调用模板引擎,渲染数据
        var html = template('tmpl',{"res":res});
        //调用模板引擎，进行渲染
        $("#count").html(html);
      }
    });
  </script>
</body>
</html>
