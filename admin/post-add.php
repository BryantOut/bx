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
  $visitor = "posts-add";

  //获取category表中的cat_id列表
  include_once "../sqlHelper.php";

  //编写sql语句
  $sql = "SELECT * FROM category";

  //执行
  $data = read($sql,"bx");

  var_dump($data);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="alert alert-success" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="row" id="form">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <!-- <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea> -->
            <textarea id="content" name="content"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none" id="specialImg">
            <input type="hidden">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="cat_id">所属分类</label>
            <select id="cat_id" class="form-control" name="cat_id">
              <?php foreach ($data as $key => $val): ?>
                <option value="<?php echo $val['cat_id']?>"><?php echo $val['cat_name']?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <span class="btn btn-primary" id="saveBtn">保存</span>
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
  <script src="/static/plugins/laydate/laydate.js"></script>
  <script src="/static/plugins/ueditor/ueditor.config.js"></script>
  <script src="/static/plugins/ueditor/ueditor.all.min.js"></script>
  <script src="/static/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
  <!-- 建议手动加载语言，避免在ie下有时因为加载语言失败导致编辑器加载失败 -->
  <!-- 这里加载的语言文本会覆盖在你的配置项目里添加的语言类型，比如比在配置项目里配置的是英文，这里加载的中文，那最后就是中文 -->
  <script src="/static/plugins/layer/layer.js"></script>
  <script>
    //执行一个laydate实例
    laydate.render({
    elem: '#created', //指定元素
    type:'datetime' //指定日期的类型
  });
  
  var url = "";//用于存储上传文件的路径

  //初始化富文本编辑器
  //实例化编辑器
  //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用改编辑器
  var ue = UE.getEditor('content');

  //实现文章图片上传实时预览
  //为input[type:file]绑定change事件,完成ajax请求
  $("#feature").change(function(){
    //获取到文件的上传信息
    var file = this.files[0];
    //html5的一个特征,利用formData表单对象,可以用来传递二进制数据(文件流)
    var formdata = new FormData();
    //append('键','值')
    formdata.append('file',file);
    if (file) {
      //有文件上传，发送ajax请求，通过php帮助我们处理上传文件
      $.ajax({
          url: "../api/uploadImg.php",
          type: "post", //上传文件只能是post
          data: formdata,
          contentType: false, //上传文件不可以指定数据类型
          processData: false, //对数据不进行数据的序列化
          dataType: "json",
          success: function (res) {
            if (res.code == 200) {
              $(".help-block").show().attr("src", res.url);
              url = res.url;
            }
          }
        });
    }
  });

  //为保存按钮注册点击事件
  $("#saveBtn").on("click",function(){
    var param = $("#form").serialize();//表单元素必须还有name属性
    //拼接图片的地址
    param += "&url="+url;
    //发送ajax请求
    $.ajax({
      dataType: "json",
      type:"post",
      url:"../api/addPostData.php",
      data:param,
      success:function(res){
        // console.log("123");
        if (res.code == 200) {
          $(".alert-success").fadeIn(500).delay(1000).fadeOut(500).html(res.message);
          //跳转到文章列表页
          // location.href = "./posts.php";
        } else {
          $(".alert-danger").fadeIn(500).delay(1000).fadeOut(500).html(res.message);
        }
      },
      error:function(){
        console.log("失败");
      }
    });
  });
</script>
</body>
</html>
