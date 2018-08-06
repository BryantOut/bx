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
  $visitor = "posts";

  //获取分类
  include_once "../sqlHelper.php";
  $sql = "SELECT t1.cat_id,t1.cat_name FROM category t1";
  $cat_data = read($sql,"bx");
  // print_r($cat_data[0]['cat_name']);
  // exit;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/plugins/layer/theme/default/layer.css">
</head>
<body>
  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="../api/logout.php" onclick="return confirm('确认退出吗？')"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
    <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
    <ul id="pagination-demo" class="pagination-sm pull-right pagination"></ul>
    <div class="page-action">
    <a class="btn btn-danger btn-sm" href="javascript:;" style="visibility: hidden;">批量删除</a>
    <form class="form-inline">
          <select name="cat_id" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($cat_data as $key=>$value): ?>
              <option value="<?php echo $value['cat_id']?>"><?php echo $value['cat_name'] ?></option>
            <?php endforeach;?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted">草稿</option>
            <option value="published">已发布</option>
            <option value="trashed">已作废</option>
          </select>
          <span class="btn btn-default btn-sm" id="filtrate">筛选</span>
        </form>
        </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->

        <!-- show when multiple checked -->
        
      
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <?php 
    include_once "./commonAside.php";
  ?>
  
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/plugins/jquery.twbsPagination.min.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <!-- 引入好layer.js后，直接用即可 -->
  <script src="/static/plugins/layer/layer.js"></script>
  <!-- 准备一个模板引擎 -->
  <script type="text/x-art-template" id="tmpl">
    {{each comments}}
    <tr>
      <td class="text-center"><input type="checkbox" value="{{$value["post_id"]}}"></td>
      <td>{{$value["title"]}}</td>
      <td>{{$value["nickname"]}}</td>
      <td>{{$value["cat_name"]}}</td>
      <td class="text-center">{{$value["created"]}}</td>
      <td class="text-center">
        {{ if $value["status"] == 'drafted'}}
          草稿
          {{ else if $value["status"] == 'piblished' }}
            已发布
          {{ else }}
            已作废
        {{ /if }}
      </td>
      <td class="text-center">
        <a href="./post-edit.php?post_id={{$value["post_id"]}}" class="btn btn-default btn-xs" id="editBtn">编辑</a>
        <a href="javascript:;" class="btn btn-danger btn-xs" id="delBtn">删除</a>
      </td>
    </tr>
    {{/each}}
  </script>
</body>
<script>
  $(function(){
    $(document)
    .ajaxStart(function(){
    //   // console.log("ajax开始");
    //   layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
    // })
    //loading层
      layer.load(1, {
        shade: [0.3,'#ccc'] ,//0.1透明度的白色背景
        shadeClose :false
      });
    })
    .ajaxStop(function(){
      layer.closeAll(); //关闭特定层
    });
  });
  

  //在posts.php页面的下面发送ajax请求，获取文章的数据,使用模板引擎进行数据的渲染
  $.ajax({
    dataType:"json",
    type:"get",
    url:"../api/getPosts.php",
    success:function(res){
      // console.log(res);
      if (res.code == 200) {
        var data = res.data;
        var pageCount = res.pageCount;
        var context = {comments:data}
        //借助与模板引擎的api
        var html = template('tmpl',context);
        $("tbody").html(html);

        //1、初始化成功之后，为分页导航初始化并注册点击事件
        //1.1、绘制分页导航
        pageList(pageCount);
      }
    },
    error:function(){
      console.log("失败");
    }
  });

  //绘制分页导航
  function pageList(pageCount) {
    //把class = pagination渲染出分页页码的html结构
    //重置分页页码，要重新渲染筛选条件后的分页页码,对page进行解绑事件
    $("#pagination-demo").empty();
    //删除此插件自带的一个值
    $("#pagination-demo").removeData('twbs-pagination');
    //解绑page事件
    $("#pagination-demo").unbind('page');
    //模板需要的数据
    $('#pagination-demo').twbsPagination({
      totalPages: pageCount,//分页页码的总页数
      visiblePages: 7,//展示的页码数
      initiateStartPageClick:false, // 取消默认初始点击
      onPageClick: function (event, page) {
          // $('#page-content').text('Page ' + page);
          //重新获取筛选条件
          var cat_id = $("select[name='cat_id']").val();
          var status = $("select[name='status']").val();
          $.ajax({
            dataType:"json",
            type:"get",
            url:"../api/getPosts.php",
            data:{
              cat_id:cat_id,
              status:status,
              page:page
            },
            success:function (res){
              if (res.code == 200) {
                console.log("成功");
                var data = res.data;
                var context = {comments:data}
                //借助与模板引擎的api
                var html = template('tmpl',context);
                $("tbody").html(html);
              }
            },
            error:function () {
              console.log("最终失败");
            }
          });
      }
  }); 
  }

  //为渲染按钮注册点击事件
  $("#filtrate").on("click",function(){
    //获取当前所选分类
    var cat_id = $("select[name='cat_id']").val();
    //获取当前所选文章状态
    var status = $("select[name='status']").val();

    //发送ajax请求
    $.ajax({
      dataType:"json",
      type:"get",
      url:"../api/getPosts.php",
      data:{
        cat_id:cat_id,
        status:status
      },
      success:function(res){
        if (res.code == 200) {
          console.log("请学会给自己找麻烦");
          var pageCount = res.pageCount;
          var data = res.data;
          //调用模板引擎渲染数据
          var context = {comments:data}
          //借助模板引擎的api
          var html = template('tmpl',context);
          //将渲染结果的html设置到默认元素的innerHTML中
          $("tbody").html(html);
          console.log(pageCount);
          // 重新绘制分页导航
          pageList(pageCount);
        }
      },
      error:function(){
        console.log("失败");
      },
      complete:function(){}
    });

  });
  

  //为删除按钮注册点击事件--事件委托
  $("tbody").on("click","#delBtn",function(){
    // console.log("删除按钮被触发");
    // 获取当前对象
    var _self = $(this);
    //获取当前的value
    var post_id = _self.parents("tr").find("input").val();
    //友好提示用户,是否确认删除
    if (confirm("确认删除?")) {
      $.ajax({
        dataType: "json",
        type: "get",
        data: {
          post_id : post_id
        },
        url:"../api/delPost.php",
        success:function(res){
          if (res.code==200) {
              console.log(res);
              //移除自身
              _self.parents("tr").remove();
          }
        }
      });
    }
  });
</script>
</html>
