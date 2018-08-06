<?php
  //判断服务器的session中是否存了相关数据
  session_start();//一定要先开启
  // var_dump($_SESSION['user']);
  if (!isset($_SESSION["user"])) {
    header("location:./login.php");
    // print_r("请先登录");
    exit;
  }

  include_once "../sqlHelper.php";
  $sql = "select*from category";
  $res = read($sql,"bx");

  //给当前页设置一个标志
  $visitor = "categories";
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress-0.2.0/nprogress.css">
</head>

<body>
  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="profile.php">
            <i class="fa fa-user"></i>个人中心</a>
        </li>
        <li>
          <a href="login.html" onclick="return confirm('确认退出吗？')">
            <i class="fa fa-sign-out"></i>退出</a>
        </li>
      </ul>
    </nav>
    <div class="container-fluid">
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger fixAlert" style="display:none;">
        <strong>错误！</strong>发生XXX错误
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-success fixAlert" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="page-title">
        <h1>分类目录</h1>
      </div>

      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">类名</label>
              <input id="slug" class="form-control" name="classname" type="text" placeholder="classname">
            </div>
            <div class="form-group">
              <span class="btn btn-primary addBtn" type="submit" id="addCat">添加</span>
              <span class="btn btn-primary updCatBtn" type="submit" style="display:none" id="updCat">更新</span>
              <span class="btn btn-primary cancelUpdBtn" type="submit" style="display:none" id="cancelUpd">取消编辑</span>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="delMore">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40">
                  <input type="checkbox" id="selectAll">
                </th>
                <th>名称</th>
                <th>类名</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($res as $val):?>
              <tr>
                <td class="text-center">
                  <input type="checkbox" value="<?php echo $val['cat_id']?>">
                </td>
                <td>
                  <?php echo $val['cat_name']?>
                </td>
                <td>
                  <?php echo $val['classname']?>
                </td>
                <td class="text-center">
                  <a href="javascript:;" class="editBtn btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="delBtn btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php 
    include_once "./commonAside.php";
  ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/vendors/nprogress-0.2.0/nprogress.js"></script>
  <script src="../static/assets/vendors/art-template/template-web.js"></script>
  <!-- 准备一个模板 -->
  <script type="text/x-art-template" id="tmpl">
      <tr>
        <td class="text-center"><input type="checkbox" value="{{cat_id}}"></td>
        <td>{{cat_name}}</td>
        <td>{{classname}}</td>
        <td class="text-center">
          <a href="javascript:;" class="editBtn btn btn-info btn-xs">编辑</a>
          <a href="javascript:;" class="delBtn btn btn-danger btn-xs">删除</a>
        </td>
      </tr>
  </script>
  <script></script>
</body>
<script>
  //jq的入口函数
  $(function(){
    $(document)
      .ajaxStart(function(){
        NProgress.start();
      })
      .ajaxStop(function(){
        NProgress.done();
      })
  });
  //给名称输入框注册失去焦点事件
  $("#name").on("blur", function () {
    // console.log('触发鼠标弹起事件');
    var name = $(this).val();
    // console.log(name);
    $.ajax({
      dataType: "json",
      url: "../api/checkName.php",
      type: "get",
      beforeSend: function () {
        //1、判断名称输入框是否为空
        if ($.trim(name) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>名称不能为空');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
      },
      data: {
        name: name
      },
      success: function (res) {
        // console.log(res);
        if (res.code == 200) {
          $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html(res.message);
        } else {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html(res.message);
        }
      }
    });
  });

  //给分类名称注册失去焦点事件
  $("#slug").on("blur", function () {
    // console.log('触发鼠标弹起事件');
    var slug = $(this).val();
    // console.log(name);
    $.ajax({
      dataType: "json",
      url: "../api/checkSlug.php",
      type: "get",
      beforeSend: function () {
        //1、判断分类名称输入框是否为空
        if ($.trim(slug) == "") {
          $(".alert-danger").fadeIn(500).delay(1000).fadeOut(500).html('<strong>错误！</strong>类名不能为空');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
      },
      data: {
        slug: slug
      },
      success: function (res) {
        // console.log(res);
        if (res.code == 200) {
          $(".alert-success").fadeIn(500).delay(1000).fadeOut(500).html(res.message);
        } else {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html(res.message);
        }
      }
    });
  });

  //给添加按钮注册点击事件
  $(".addBtn").on("click", function () {
    console.log('添加按钮触发');
    var name = $("#name").val();
    var slug = $("#slug").val();
    $.ajax({
      dataType: "json",
      url: "../api/addCat.php",
      type: "get",
      beforeSend: function () {
        //1、判断名称输入框是否为空
        if ($.trim(name) == "") {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>错误！</strong>名称不能为空');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
        //2、判断分类名称输入框是否为空
        if ($.trim(slug) == "") {
          $(".alert-danger").fadeIn(500).delay(1000).fadeOut(500).html('<strong>错误！</strong>类名不能为空');
          return false; //阻止发送请求，false--不仅仅结束beforeSend:function这个函数，还会结束整个外部函数
        }
      },
      data: {
        name: name,
        slug: slug
      },
      success: function (res) {
        if (res.code == -1) {
          $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html(res.message);
        } else if (res.code == 200) {
          $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html(res.message);
          // 拼接一个新建tr
          //通过模板引擎的JS提供的一个函数将模板和数据整合得到渲染结果HTML
          //模板所需数据
          var html = template('tmpl',{"cat_id":res.insert_id,"cat_name":name,"classname":slug});

          //将tr追加到tbody后
          $("tbody").append(html);
        }
      },
      complete:function(){
        $("#name").val('');
        $("#slug").val('');
      }
    });
  });

  //添加删除按钮的点击事件
  $("tbody").on("click",".delBtn",function () {

    //获取当前对象
    var _self = $(this);
    //获取当前的value
    var cat_id = _self.parents("tr").find("input").val();

    //友好提示用户，是否确认删除
    if (confirm("确认删除?")) {
      $.ajax({
        dataType: "json",
        type: "get",
        data: {
          cat_id: cat_id
        },
        url: "../api/delCat.php",
        success: function (res) {
          if (res.code == 200) {
            //移除自身
            _self.parents("tr").remove();
            $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>删除成功</strong>');
          } else {
            $(".alert-danger").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>服务器忙，请稍后再试</strong>');
          }
        }
      });
    }
  });

  var cat_id;
  var currTr;
  //给编辑按钮注册点击事件
  $("tbody").on("click",".editBtn",function(){
    //获取当前点击的类信息
    //1、获取当前的tr
    currTr = $(this).parents("tr");
    //2、获取当前$cat_id
    cat_id = currTr.find("input").val();
    // console.log(cat_id);
    //3、获取当前的name
    var name = currTr.children("td").eq(1).html();
    console.log(name);
    //4、获取当前类名
    var slug = currTr.children("td").eq(2).html();
    console.log(slug);

    //将数据显示出来
    name = $.trim(name);
    slug = $.trim(slug);
    $("#name").val(name);
    $("#slug").val(slug);

    //显示更新和取消编辑按钮，隐藏添加按钮
    $("#updCat").show();
    $("#cancelUpd").show();
    $("#addCat").hide();
  });

  //给更新按钮注册点击事件
  $("#updCat").on("click",function(){
    // console.log("更新按钮");
    var name = $("#name").val();
    var slug = $("#slug").val();
    console.log(name,slug,cat_id);
    //发送ajax请求
    $.ajax({
      dataType:"json",
      type:"get",
      url:"../api/editCat.php",
      data:{
        cat_id:cat_id,
        name:name,
        slug:slug
      },
      success:function(res){
        console.log('success');
        console.log(res);
        if (res.code == 200) {
          currTr.children("td").eq(1).html(name);
          currTr.children("td").eq(2).html(slug);
        }else {
          $(".alert-success").fadeIn(1000).delay(1000).fadeOut(1000).html('<strong>'+res.message+'</strong>');
        }
      },
      error:function(){
        console.log("失败");
      }
    });
  });
  
  //给取消编辑按钮注册点击事件
  $("#cancelUpd").on("click",function(){
    $("#name").val('');
    $("#slug").val('');
    $("#updCat").hide();
    $("#cancelUpd").hide();
    $("#addCat").show();
  });


  //为全选按钮注册点击事件
  $("#selectAll").on("click",function(){
    //获取全部单选框按钮
    $("tbody tr input").prop("checked",$(this).prop("checked"));
  
    //加入全选按钮勾选--批量删除按钮显示
    $("#delMore").toggle($(this).prop("checked"));
  });

  //为单选按钮注册点击事件
  $("tbody").on("click","tr input",function(){
    if ($("tbody tr input:checked").length == $("tbody tr input").length) {
      $("#selectAll").prop("checked",true);
    }else{
      $("#selectAll").prop("checked",false);
    }

    if ($("tbody tr input:checked").length>0) {
      $("#delMore").show();
    }else {
      $("#delMore").hide();
    }
  });
  
  //为批量删除注册点击事件
  $("#delMore").on("click",function(){

    //获取所有选中的单选框
    var checkList = $("tbody tr input:checked");
    //获取所有选中的单选框的$cat_id,并存进一个空数组
    var cat_ids = [];//先定义一个空数组
    $.each(checkList,function(k,v){
      cat_ids.push(v.value);
    });
    cat_ids = cat_ids.join();//因为我们要传过去的是一个字符串，如果我们这边是数组，则服务器端要用explode()!!!!!大坑
    //友好提示用户，是否确认删除
    if (confirm("确认删除?")) {
    //发送ajax请求，后台批量删除
    $.ajax({
      dataType:"json",
      type:"get",
      data:{
        cat_ids : cat_ids
      },
      url:"../api/delMore.php",
      success:function(res){
        if (res.code == 200 ) {
          checkList.parents("tr").remove();
        }
      },
      error:function(){
        console.log("失败");
      }
    });
  }
  });
</script>

</html>
