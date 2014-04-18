<?php
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

//调试用的跨域http头
//header("Access-Control-Allow-Origin: *");

$action = trim($_GET['action']);
$count = empty($_POST['count'])? 0 : intval($_POST['count']);
$num = empty($_POST['num'])? 5 : intval($_POST['num']);

$conn = new DBClass();

switch ($action)
{
case 'new':
	$result = $conn->query("SELECT shop_goods.goods_id, shop_images.image_name FROM `shop_goods`, `shop_images` WHERE shop_goods.goods_id = shop_images.goods_id AND shop_images.image_type = 1 ORDER BY `add_time` DESC LIMIT $count,$num");
	if($result)	echo genNewLists($result);
	else echo json_encode(array('status'=>'fail'));	
	die();
case 'rank':
	$result = $conn->query("SELECT shop_goods.goods_id, shop_images.image_name, `price`, `sale_num` FROM `shop_goods`, `shop_images` WHERE shop_goods.goods_id = shop_images.goods_id AND shop_images.image_type = 2 ORDER BY `sale_num` DESC LIMIT $count,$num");
	if($result)	echo genRankLists($result);
	else echo json_encode(array('status'=>'fail'));	
	die();
default:
	echo json_encode(array('status'=>'fail'));	
	break;
}

function genNewLists($query)
{
	$data = array();
	while($result=mysql_fetch_array($query))
	{
		$item = array(
					'gid'=>$result['goods_id'],
					"img_url"=>BCS_ROOT.$result['image_name']);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'goods_new'=>$data);
	return json_encode($info);
}
function genRankLists($query)
{
	$data = array();
	while($result=mysql_fetch_array($query))
	{
		$item = array(
					'gid'=>$result['goods_id'],
					"img_url"=>BCS_ROOT.$result['image_name'],
					"price"=>$result['price'],
					"sale_num"=>$result['sale_num']);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'goods_top'=>$data);
	return json_encode($info);
}