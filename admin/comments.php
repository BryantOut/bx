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
  $visitor = 'comments';
?>
<!DOCTYPE php>
<php lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <style>
    .pagination.pagination-sm {
      position: absolute;
      top: 0px;
      right: 220px;
    }
        
  </style>
</head>
<body>
  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" >
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul id="pagination-demo" class="pagination-sm pagination"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once "./commonAside.php" ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <script src="/static/plugins/jquery.twbsPagination.min.js"></script>
  <script type="text/x-art-template" id="tmpl">
    {{each comments}}
    <tr>
      <td class="text-center"><input type="checkbox"></td>
      <td>{{$value.author}}</td>
      <td>{{$value.content}}</td>
      <td>{{$value.title}}</td>
      <td>{{$value.created}}</td>
      <td>{{$value.status}}</td>
      <td class="text-center">
        <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
        <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
      </td>
    </tr>
    {{/each}}
  </script>
  <script>
    function getComments(page){
      //发送ajax请求，请求数据渲染页面
      $.ajax({
        dataType:"json",
        type:"get",
        data:{page:page},
        url:"../api/getComments.php",
        success:function(res){
          console.log(res);
          //模板所需数据
          var context = {comments:res.data}
          //借助模板引擎的api
          var html = template('tmpl',context);
          console.log(html);
          //将渲染结果的HTML设置到默认元素的 innerHTML 中
          $("tbody").html(html);

          //绘制分页导航
          $('#pagination-demo').twbsPagination({
            totalPages: 10,//分页页码的总页数
            visiblePages: 7,//展示的页码数
            initiateStartPageClick:false, // 取消默认初始点击
            onPageClick: function (event, page) {
              getComments(page);
            }
});
        },
        error:function(){}
      });
    }
    
    getComments(1);
  </script>
</body>
</php>
