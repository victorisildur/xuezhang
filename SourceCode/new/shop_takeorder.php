<?php
session_start();
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');
require_once(COMMON_PATH.'common_functions.php');
require_once(COMMON_PATH.'shop_functions.php');

$conn = new DBClass();

if(!InputFilterForOrder())
{
	$err_info = '输入有误，请检查！';
	require_once(TEMPLATES_PATH.'order_fail.html');
	die();
}

$gid = $_REQUEST['gid'];

$order_array = array();
foreach ($_POST['orders'] as $order)
{
	$detail_id = $order['detail_id'];
	$left = GetLeftByDetailID($detail_id);
	//check是否有足够的货
	if($left<$order['num'])
	{
		$err_info = '库存不足，请检查数量！';
		require_once(TEMPLATES_PATH.'order_fail.html');
		die();
	}
	$order_array[] = array('detail_id'=>$detail_id, 'num'=>$order['num']);
}
$userinfo = array();
$userinfo['phone'] = $_POST['phone'];
$userinfo['real_name'] = $_POST['real_name'];
$userinfo['best_time'] = $_POST['best_time'];
$userinfo['address'] = $_POST['address'];
$userinfo['message'] = $_POST['message'];

$price = GetGoodsPrice($gid);

$order_sn = TakeOrder($gid, $order_array, $price, $userinfo);

if(!$order_sn)
{
	//order_fail.html 需填充$err_info
	die(json_encode(array('status'=>'fail', 'msg'=>$err_info)));
}
else
{
	//order_success.html 需填充$order_sn
	die(json_encode(array('status'=>'ok', 'msg'=>$order_sn)));
}