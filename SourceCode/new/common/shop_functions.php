<?php
//参数在查询时过滤
/******************
    some functions
*******************/
//array(detail_id, goods_id, color, size, sex, left)
function GetDetailsByGID($gid)
{
	global $conn;
	$gid = intval($gid);
	$sql = "SELECT * FROM `shop_goods_detail` WHERE `goods_id` = $gid";
	$query = $conn->query($sql);
	$details = array();
	while($result=mysql_fetch_array($query))
	{
		if($result['left']>0) $details[] = $result;
	}
	return $details;
}
//array(detail_id, goods_id, color, size, sex, left)
function GetInfoByDetailID($detail_id)
{
	global $conn;
	$detail_id = intval($detail_id);
	$sql = "SELECT * FROM `shop_goods_detail` WHERE `detail_id` = $detail_id";
	$result = $conn->get_one($sql);
	return $result;
}


/******************
    take order functions
*******************/

//格式或者类型不正确即认为hacking行为，阻止继续操作
//$_POST['orders'][0] = array(detail_id, num)
//$_GET['gid']
function InputFilterForOrder()
{
	//判断$_POST['orders']数组
	if(!is_array($_POST['orders'])) return false;
	foreach ($_POST['orders'] as $order) {
		if(!is_int($order['detail_id'])) return false;
		if(!is_int($order['num'])) return false;
	}
	//用户信息phone, real_name, best_time, addres, message
	//用户信息只有best_time是整数型，其他都是字符串
	if(!is_int($_POST['best_time'])) return false;
	//gid
	if(!is_int($_REQUEST['gid'])) return false;

	return true;
}

function GetGoodsPrice($gid)
{
	global $conn;
	$gid = intval($gid);
	$sql = "SELECT `price` FROM `shop_goods` WHERE `goods_id` = $gid";
	$result = $conn->get_one($sql);
	return $result['price'];
}

function GetLeftByDetailID($detail_id)
{
	global $conn;
	$detail_id = intval($detail_id);
	$sql = "SELECT `left` FROM `shop_goods_detail` WHERE `detail_id` = $detail_id";
	$result = $conn->get_one($sql);
	return $result['left'];
}


//copy from internet
function GenerateOrderSN()
{
	$year_code = array('A','B','C','D','E','F','G','H','I','J');
	$order_sn = $year_code[intval(date('Y'))-2014].
	strtoupper(dechex(date('m'))).date('d').
	substr(time(),-5).substr(microtime(),2,5).sprintf('%d',rand(10,99));
	return $order_sn;
}
//$order_array[0] = array(detail_id, num)
//$userinfo = array(phone, real_name, best_time, addres, message)
function TakeOrder($gid, $order_array, $price, $userinfo)
{
	global $conn;
	$total_num = 0;

	$order_sn = GenerateOrderSN();
	//将订单信息写入数据库
	foreach($order_array as $order)
	{
		$detail_id = $order['detail_id'];
		$num = intval($order['num']);
		$sql = "INSERT INTO `shop_orders` (`order_sn`, `detail_id`, `num`) VALUES ('$order_sn', $detail_id, $num);";
		if(!$conn->query($sql)) return false;
		//更新shop_goods_detail中该类的数量
		$sql = "UPDATE `shop_goods_detail` SET `left` = `left` - $num WHERE `detail_id` = $detail_id";
		if(!$conn->query($sql)) return false;

		$total_num = $total_num + $num;
	}
	//计算总价格
	$total_fee = $total_num * $price;
	$time_now = time();
	$phone = mysql_real_escape_string($userinfo['phone']);
	$name = mysql_real_escape_string($userinfo['real_name']);
	$time = intval($userinfo['best_time']);
	$addr = mysql_real_escape_string($userinfo['address']);
	$message = mysql_real_escape_string($userinfo['message']);
	$sql = "INSERT INTO `shop_order_status` (`order_sn`, `phone`, `real_name`, `best_time`, `address`, `message`, `total_fee`, `pay_status`, `create_time`, `complete_time`) VALUES ('$order_sn', '$phone', '$name', $time, '$addr', '$message', $total_fee, 0, $time_now, 0);";
	if(!$conn->query($sql)) return false;
	//更新已售数量
	$gid = intval($gid);
	$sql = "UPDATE `shop_goods` SET `sale_num` = `sale_num` + $total_num WHERE `goods_id` = $gid";
	if(!$conn->query($sql)) return false;

	return $order_sn;
}