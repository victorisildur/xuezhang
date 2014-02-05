<?php
session_start();
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");
//装载数据库类
require_once('database_class.php');
$conn = new DBClass();

//检查session中数据
if(empty($_SESSION['name']) || empty($_SESSION['phone']) || empty($_SESSION['addr']) || empty($_SESSION['time']))
{
	$name = empty($_POST['name']) ? die('{ "status" : "failed"}') : $conn->sqlesc($_POST['name']);
	$phone = empty($_POST['phone']) ? die('{ "status" : "failed"}') : $conn->sqlesc($_POST['phone']);
	$addr = empty($_POST['addr']) ? die('{ "status" : "failed"}') : $conn->sqlesc($_POST['addr']);
	$time = empty($_POST['time']) ? die('{ "status" : "failed"}') : $conn->sqlesc($_POST['time']);
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['phone'] = $_POST['phone'];
	$_SESSION['addr'] = $_POST['addr'];
	$_SESSION['time'] = $_POST['time'];
}
else
{
	$name = $conn->sqlesc($_SESSION['name']);
	$phone = $conn->sqlesc($_SESSION['phone']);
	$addr = $conn->sqlesc($_SESSION['addr']);
	$time = $conn->sqlesc($_SESSION['time']);
}

$gid = empty($_POST['gid']) ? die('{ "status" : "failed"}') : intval($_POST['gid']);
$orders = !is_array($_POST['order']) ? die('{ "status" : "failed"}') : $_POST['order'];
//取出商品价格
if(!empty($_SESSION['price_'.$gid])) $price = $_SESSION['price_'.$gid];
else
{
	$sql = "SELECT `price` FROM `shop_goods` WHERE `goods_id` = $gid";
	$result = $conn->get_one($sql);
	$price = empty($result['price']) ? die('{ "status" : "failed"}') : floatval($result['price']);
	$_SESSION['price_'.$gid] = $price;
}
//产生order_sn
$order_sn = $conn->sqlesc(GenerateOrderSN());
//检查orders数组完整性
foreach($orders as $order)
{
	if(empty($order['color']) || empty($order['size']) || empty($order['num']))  die('{ "status" : "failed"}');
}
//每种插入数据库，并累加数量以计算总价
$total_num = 0;
foreach($orders as $order)
{
	$color = $conn->sqlesc($order['color']);
	$size = $conn->sqlesc($order['size']);
	$num = intval($order['num']);
	$sql = "INSERT INTO `shop_orders` (`order_sn`, `goods_id`, `color`, `size`, `num`) VALUES ($order_sn, $gid, $color, $size, $num);";
	$conn->query($sql);
	$total_num = $total_num + $num;
}
$total_fee = $total_num * $price;
$time_now = time();
//更新商品状态表
$sql = "INSERT INTO `shop_order_status` (`order_sn`, `phone`, `real_name`, `best_time`, `address`, `total_fee`, `pay_status`, `create_time`, `complete_time`) VALUES ($order_sn, $phone, $name, $time, $addr, $total_fee, 0, $time_now, 0);";
if($conn->query($sql)) echo '{ "status" : "ok"}';
else echo '{ "status" : "failed"}';

//copy from internet
function GenerateOrderSN()
{
	$year_code = array('A','B','C','D','E','F','G','H','I','J');
	$order_sn = $year_code[intval(date('Y'))-2014].
	strtoupper(dechex(date('m'))).date('d').
	substr(time(),-5).substr(microtime(),2,5).sprintf('%d',rand(10,99));
	return $order_sn;
}