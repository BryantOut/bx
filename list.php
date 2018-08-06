<?php include_once __DIR__."/sqlHelper.php"?>
<?php 
  //1、接收参数
  //$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:0;
  //'kobe'+0=>把字符串转化成整形 0+0=0
  // $cat_id = $_GET['cat_id']+0;//强制转化为整形
  $cat_id = isset($_GET['cat_id'])?(int)$_GET['cat_id']:0;
  // echo $cat_id;
  //2、编写sql语句
  $sql = "SELECT t1.*,t3.nickname,t2.cat_name,
  (SELECT count(*) FROM comments t4 where post_id = t1.post_id) as commentCount
  FROM posts t1
  LEFT JOIN category t2 on t1.cat_id=t2.cat_id
  LEFT JOIN users t3 on t1.user_id = t3.user_id
  WHERE t1.cat_id = $cat_id
  ORDER BY post_id desc
  limit 5";
  //3、连接数据库，执行sql语句
  $resTwo = read($sql,"bx");
  //var_dump($resTwo);die;
  //规定不同的错误级别报告
  // error_reporting(0);//关闭错误报告--对用户体验好
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="static/assets/css/style.css">
  <link rel="stylesheet" href="static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="static/assets/vendors/nprogress-0.2.0/nprogress.css">
</head>

<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li>
          <a href="javascript:;">
            <i class="fa fa-glass"></i>奇趣事</a>
        </li>
        <li>
          <a href="javascript:;">
            <i class="fa fa-phone"></i>潮科技</a>
        </li>
        <li>
          <a href="javascript:;">
            <i class="fa fa-fire"></i>会生活</a>
        </li>
        <li>
          <a href="javascript:;">
            <i class="fa fa-gift"></i>美奇迹</a>
        </li>
      </ul>
    </div>
    <?php include_once __DIR__."/aside.php";?>
    <div class="content">
      <div class="panel new">
        <h3>
          <?php echo isset($resTwo[0]['cat_name'])?$resTwo[0]['cat_name']:'' ?>
        </h3>
        <?php foreach($resTwo as $val):?>
        <div class="entry" postId="<?php echo $val['post_id']?>">
          <div class="head">
            <a href="./detail.php?post_id=<?php echo $val['post_id']?>">
              <?php echo $val['title']?>
            </a>
          </div>
          <div class="main">
            <p class="info">
              <?php echo $val['nickname']?> 发表于
              <?php echo $val['created']?>
            </p>
            <p class="brief">
              <?php echo $val['content']?>
            </p>
            <p class="extra">
              <span class="reading">阅读(
                <?php echo $val['views']?>)</span>
              <span class="comment">评论(
                <?php echo $val['commentCount']?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(
                  <?php echo $val['likes']?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：
                <span>
                  <?php echo $val['cat_name'] ?>
                </span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $val['feature']?>" alt="">
            </a>
          </div>
        </div>
        <?php $lastPostId = $val['post_id']?>
        <?php endforeach; ?>
        <!-- 点击加载更多功能 -->
        <div class="loadmore">
          <span class="btn">加载更多</span>
        </div>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="static/assets/vendors/nprogress-0.2.0/nprogress.js"></script>
  <script src="./static/assets/vendors/art-template/template-web.js"></script>
  <!-- 准备一个模板 -->
  <script type="text/x-art-template" id="tmpl">
    {{each comments}}
      <!-- each 内部 $value 拿到的是当前被遍历的那个元素 -->
      <div class="entry">
        <div class="head">
          <a href="./detail.php">{{$value.title}}</a>
        </div>
        <div class="main">
          <p class="info">{{$value.nickname}} 发表于 {{$value.created}}</p>
          <p class="brief">{{$value.content}}</p>
          <p class="extra">
            <span class="reading">阅读({{$value.views}})</span>
            <span class="comment">评论({{$value.commentCount}})</span>
            <a href="javascript:;" class="like">
              <i class="fa fa-thumbs-up"></i>
              <span>赞({{$value.likes}})</span>
            </a>
            <a href="javascript:;" class="tags"> 分类：
              <span>'+this.cat_name+'</span>
            </a>
          </p>
          <a hef="javascript:;" class="thumb">
            <img src="{{$value.feature}}" alt="">
          </a>
        </div>
      </div>
    {{/each}}
  </script>
  <script>
    //jq的入口函数
    $(function () {
      $(document)
        .ajaxStart(function () {
          NProgress.start();
        })
        .ajaxStop(function () {
          NProgress.done();
        })
    });
    
    //获取当前页面最后一篇文章的post_id
    var lastPostId = "<?php echo isset($lastPostId)?$lastPostId:0 ?>";
    //给加载更多按钮注册点击事件
    $(".loadmore").on("click", function () {
      // console.log("触发加载更多按钮");
      //获取当前cat_id
      var cat_id = <?php echo $_GET['cat_id']?>;
      // console.log(cat_id);
      // console.log(lastPostId);
      $.ajax({
        dataType: "json",
        type: "get",
        url: './api/loadMorePost.php',
        data: {
          cat_id: cat_id,
          lastPostId: lastPostId
        },
        success: function (res) {
          console.log(res);
          if (res.code == 200) {
            var morePostData = res.data;
            console.log(morePostData);
            //通过模板引擎的JS提供的一个函数将模板和数据整合得到渲染结果HTML
            //模板所需数据
            var context = {comments:morePostData}
            //借助与模板引擎的api
            var html = template("tmpl",context);  
            //获得最后一篇文章的id
            //获取当前数组的长度
            var morePostDataLength = morePostData.length-1;
            console.log(typeof morePostDataLength);
            lastPostId = morePostData[morePostDataLength]['post_id'];
            // lastPostId = this.post_id;
            //把html动态追加在加载更多按钮的前面
            $('.loadmore').before(html);
          }
        },
        error: function () {
          console.log("加载失败2");
        }
      });
    });

    //为点赞按钮添加按钮点击事件
    $(".like").on("click", function () {
      //获取当前点击对象
      var _self = $(this);
      //获取当前点击的文章的post_id
      var postId = _self.parents(".entry").attr("postId");
      // console.log(postId);
      $.ajax({
        dataType: "json",
        type: "get",
        url: "/api/updateLikes.php",
        data: {
          post_id: postId
        },
        success: function (res) {
          if (res.code == 200) {
            _self.children('span').html("赞(" + res.data + ")");
          }
        }
      });
    });
  </script>
</body>

</html>