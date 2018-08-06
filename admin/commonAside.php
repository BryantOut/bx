<?php 
    // session_start();
    // var_dump($_SESSION);
    /*   array(1) {
  ["user"]=>
  array(5) {
    ["nickname"]=>
    string(9) "刘人语"
    ["user_id"]=>
    string(1) "4"
    ["email"]=>
    string(15) "ligoudan@bx.com"
    ["password"]=>
    string(6) "goudan"
    ["islogin"]=>
    bool(true)
  }
} */
?>
<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $_SESSION['user']['avatar']?>">
      <h3 class="name"><?php echo $_SESSION['user']['nickname']?></h3>
    </div>
    <ul class="nav">
      <li class="<?php echo $visitor=='index'?'active':''?>">
        <a href="index.php">
          <i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li>
        <a href="#menu-posts" class="collapsed" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章
          <i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse 
        <?php 
          if (isset($visitor)) {
            if (in_array($visitor,['posts','categories','posts-add'])) {
              echo 'in';
            }
          }
        ?>">
          <li class="<?php echo $visitor=='posts'?'active':''?>">
            <a href="posts.php">所有文章</a>
          </li>
          <li class="<?php echo $visitor=='posts-add'?'active':''?>">
            <a href="post-add.php">写文章</a>
          </li>
          <li class="<?php echo $visitor=='categories'?'active':''?>">
            <a href="categories.php">分类目录</a>
          </li>
        </ul>
      </li>
      <li class="<?php echo $visitor=='comments'?'active':''?>">
        <a href="comments.php">
          <i class="fa fa-comments"></i>评论</a>
      </li>
      <li>
        <a href="users.php">
          <i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <a href="#menu-settings" class="collapsed" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置
          <i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse">
          <li>
            <a href="nav-menus.php">导航菜单</a>
          </li>
          <li>
            <a href="slides.php">图片轮播</a>
          </li>

        </ul>
      </li>
    </ul>
  </div>