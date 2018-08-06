<?php include_once "./sqlHelper.php"?>
<?php 
  //获取当前文章的post_id
  $post_id = $_GET['post_id'];
  //根据当前的$post_id从数据库读取相关数据
  //编写sql语句
  $sql = "SELECT t1.*, t2.cat_name,t3.nickname,
  (select count(*) from comments  where post_id = t1.post_id) as commentTotal
  FROM posts t1
  LEFT JOIN category t2 ON t1.cat_id = t2.cat_id
  left join  users t3 on t1.user_id = t3.user_id
  where t1.post_id = $post_id";
  //数据库查询
  $res = read($sql,"bx");

  // var_dump($res);
  //规定不同的错误级别报告
  error_reporting(0);//关闭错误报告--对用户体验好
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="static/assets/css/style.css">
  <link rel="stylesheet" href="static/assets/vendors/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
    <?php include_once "./aside.php"?>
    <div class="content">
      <div class="article">
        <div class="breadcrumb">
          <dl>
            <dt>当前位置：</dt>
            <dd><a href="javascript:;"><?php echo $res[0]['cat_name']?></a></dd>
            <dd><?php echo $res[0]['title']?></dd>
          </dl>
        </div>
        <h2 class="title">
          <a href="javascript:;"><?php echo $res[0]['title']?></a>
        </h2>
        <div class="meta">
          <span><?php echo $res[0]['nickname']?> 发布于 <?php echo $res[0]['created']?></span>
          <div class="content-detail"><?php echo $res[0]['content']?></div>
          
          <span>分类: <a href="javascript:;"><?php echo $res[0]['cat_name']?></a></span>
          <span>阅读: (<?php echo $res[0]['views']?>)</span>
          <span>评论: (<?php echo $res[0]['commentTotal']?>)</span>
		      <a href="javascript:;"  class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $res[0]['likes']?>)</span>
          </a>
        </div>
      </div>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域与私处</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
</body>
<script src="./static/assets/vendors/jquery/jquery.js"></script>
<script>
  $(".like").on("click",function(){
    //获取当前文章的post_id
  $.ajax({
    dataType:"json",
    type:"get",
    data:{
      post_id:<?php echo $post_id?>
    },
    url:"./api/updateLikes.php",
    success:function(res){
      if (res['code']==200) {
         $('.like span').html('赞('+res['data']+')');
      }else {
      }
    }
  });
  });
  

</script>
</html> 
