<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
<script src="js/jquery.js"></script>
<?php 
$uri = $_SERVER['REQUEST_URI'];
$root = substr($uri, 0,strpos($uri, "install"));
$admin = $root."../manage";
$host = $_SERVER['HTTP_HOST'];
?>
</head>
<body>
<div class="wrap">
  <?php require './templates/header.php';?>
  <section class="section">
    <div class="">
      <div class="success_tip cc"> <a href="<?php echo $admin;?>" class="f16 b">安装完成，进入后台管理</a>
		<p>为了您站点的安全，安装完成后删除网站根目录下的“install”文件夹下的所有文件。<p>
      </div>
	        <div class="bottom tac">
            <a href="<?php echo 'http://'.$host;?>" class="btn">进入前台</a>
            <a href="<?php echo 'http://'.$host;?>/manage" class="btn btn_submit J_install_btn">进入后台</a>
      </div>
      <div class=""> </div>
    </div>
  </section>
</div>
<?php require './templates/footer.php';?>
</body>
</html>