<?php
//装载数据库类
require_once('database_class.php');

//调试用的跨域http头
header("Access-Control-Allow-Origin: *");

$action = trim($_GET['action']);

$conn = new DBClass();

$result = $conn->query("SELECT `goods_id`, `img_new` FROM `shop_goods` ORDER BY $method DEC LIMIT $count,$num");


switch ($action)
{
case 'new':
	$count = empty($_POST['count'])? 0 : intval($_POST['count']);
	$num = empty($_POST['num'])? 5 : intval($_POST['num']);
	$result = $conn->query("SELECT `goods_id`, `img_new` FROM `shop_goods` ORDER BY `add_time` DEC LIMIT $count,$num");
	if($result)	echo genNewLists($result['goods_id'], $result['img_new']);
	else echo json_encode(array('status'=>'fail'));	
	die();
case 'rank':
	$count = empty($_POST['count'])? 0 : intval($_POST['count']);
	$num = empty($_POST['num'])? 5 : intval($_POST['num']);
	$result = $conn->query("SELECT `goods_id`, `img_rank`, `price`, `sale_num` FROM `shop_goods` ORDER BY `sale_num` DEC LIMIT $count,$num");
	if($result)	echo genRankLists($result['goods_id'], $result['img_rank'], $result['price'], $result['sale_num']);
	else echo json_encode(array('status'=>'fail'));	
	die();
default:
	echo json_encode(array('status'=>'fail'));	
	break;
}

function genNewLists($goods_id, $img_url)
{
	$data = array();
	$num = count($goods_id);
	for($i=0; $i<$num; $i++)
	{
		$item = array(
					'goods_id'=>$goods_id[$i],
					"img_url"=>$img_url[$i]);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'goods_new'=>$data);
	return json_encode($info);
}
function genRankLists($goods_id, $img_url, $price, $sale_num)
{
	$data = array();
	$num = count($goods_id);
	for($i=0; $i<$num; $i++)
	{
		$item = array(
					'goods_id'=>$goods_id[$i],
					"img_url"=>$img_url[$i]);
					"price"=>$price[$i]);
					"sale_num"=>$sale_num[$i]);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'goods_top'=>$data);
	return json_encode($info);
}