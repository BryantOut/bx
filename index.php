<?php include_once __DIR__."/sqlHelper.php"?>
<?php 
  // //1、连接数据酷
  // $conn = mysqli_connect('127.0.0.1','root','root','bx');
  
  // //2、判断是否成功连接数据库
  // if (!$conn) {
  //   die ("数据库连接失败……") ;
  // }
  // //3、设置字符集编码格式
  // mysqli_set_charset($conn,'utf-8');

  //4、数据库查询语句
  $sql = "SELECT t1.*, t2.cat_name,t3.nickname,
  (select count(*) from comments  where post_id = t1.post_id) as commentTotal
  FROM posts t1
  LEFT JOIN category t2 ON t1.cat_id = t2.cat_id
  left join  users t3 on t1.user_id = t3.user_id
  where t2.cat_id !=1 
  ORDER BY post_id desc
  limit 5";

  // //5、查询数据库
  // $res = mysqli_query($conn,$sql);
  
  // //6、遍历数据库查询结果，将数据存入一个新建的数组
  // $dataList = [];
  // while ($data = mysqli_fetch_assoc($res)) {
  //   $dataList[] = $data;
  // }
  
  // print_r($dataList);
  $dataList = read($sql,"bx");

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
  <link rel="stylesheet" href="static/assets/vendors/nprogress-0.2.0/nprogress.css">
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
    <!-- 引入php外部公共文件 -->
    <?php 
      include_once "./aside.php";
    ?>
    <div class="content">
      <div class="swipe">
        <ul class="swipe-wrapper">
          <li>
            <a href="#">
              <img src="uploads/slide_1.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="uploads/slide_2.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="uploads/slide_1.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="uploads/slide_2.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
        </ul>
        <p class="cursor"><span class="active"></span><span></span><span></span><span></span></p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
      </div>
      <div class="panel focus">
        <h3>焦点关注</h3>
        <ul>
          <li class="large">
            <a href="javascript:;">
              <img src="uploads/hots_1.jpg" alt="">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战：原力觉醒视频演示 电影票68</span>
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
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="panel top">
        <h3>一周热门排行</h3>
        <ol>
          <li>
            <i>1</i>
            <a href="javascript:;">你敢骑吗？全球第一辆全功能3D打印摩托车亮相</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>2</i>
            <a href="javascript:;">又现酒窝夹笔盖新技能 城里人是不让人活了！</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span class="">阅读 (18206)</span>
          </li>
          <li>
            <i>3</i>
            <a href="javascript:;">实在太邪恶！照亮妹纸绝对领域与私处</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>4</i>
            <a href="javascript:;">没有任何防护措施的摄影师在水下拍到了这些画面</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>5</i>
            <a href="javascript:;">废灯泡的14种玩法 妹子见了都会心动</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
        </ol>
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
      <div class="panel new">
        <h3>最新发布</h3>
        <?php foreach($dataList as $val):?>
        <div class="entry" postId="<?php echo $val['post_id']?>">
          <div class="head">
            <span class="sort"><?php echo $val['cat_name']?></span>
            <a href="/detail.php?post_id=<?php echo $val['post_id']?>"><?php echo $val['title']?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $val['nickname']?> 发表于 <?php echo $val['created']?></p>
            <p class="brief"><?php echo $val['content']?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $val['views']?>)</span>
              <span class="comment">评论(<?php echo $val['commentTotal']?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $val['likes']?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $val['cat_name']?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $val['feature']?>" alt="">
            </a>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="static/assets/vendors/jquery/jquery.js"></script>
  <script src="static/assets/vendors/swipe/swipe.js"></script>
  <script src="static/assets/vendors/nprogress-0.2.0/nprogress.js"></script>
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
    //
    var swiper = Swipe(document.querySelector('.swipe'), {
      auto: 3000,
      transitionEnd: function (index) {
        // index++;

        $('.cursor span').eq(index).addClass('active').siblings('.active').removeClass('active');
      }
    });

    // 上/下一张
    $('.swipe .arrow').on('click', function () {
      var _this = $(this);

      if(_this.is('.prev')) {
        swiper.prev();
      } else if(_this.is('.next')) {
        swiper.next();
      }
    })

    //给点赞按钮添加按钮点击事件
    $(".like").on("click",function(){
      //获取当前点击对象
      var _self = $(this);
      //获取当前点击的文章的post_id
      var postId = _self.parents(".entry").attr("postId");
      // console.log(postId);
      $.ajax({
        dataType:"json",
        type:"get",
        url:"/api/updateLikes.php",
        data:{
          post_id : postId
        },
        success:function(res){
          if (res.code == 200) {
            _self.children('span').html("赞("+res.data+")");
          }
        }
      });
    });
  </script>
</body>
</html>