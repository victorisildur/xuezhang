<?php
//调试用的跨域http头
//header("Access-Control-Allow-Origin: *");
require_once('../common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');


if(empty($_POST['auth']) || trim($_POST['auth']) != 'xuezhangcc') die('err');
echo 'processing...';
$conn = new DBClass();
$title = $conn->sqlesc($_POST['title']);
$price = floatval($_POST['price']);
$img_new = $conn->sqlesc($_POST['img_new']);
$img_rank = $conn->sqlesc($_POST['img_rank']);
$description = $conn->sqlesc($_POST['description']);
$images = $conn->sqlesc($_POST['images']);
$time = time();

//echo "INSERT INTO `shop_goods` (`title`, `price`, `img_new`, `img_rank`, `add_time`, `description`, `images`) VALUES ($title, $price, $img_new, $img_rank, $time, $description, $images)";
$result = $conn->query("INSERT INTO `shop_goods` (`title`, `price`, `img_new`, `img_rank`, `add_time`, `description`, `images`) VALUES ($title, $price, $img_new, $img_rank, $time, $description, $images)");
if($result)	echo '添加成功';
else echo '添加失败';
