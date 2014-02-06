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
	$result = $conn->query("SELECT `goods_id`, `img_new` FROM `shop_goods` ORDER BY `add_time` DESC LIMIT $count,$num");
	if($result)	echo genNewLists($result);
	else echo json_encode(array('status'=>'fail'));	
	die();
case 'rank':
	$result = $conn->query("SELECT `goods_id`, `img_rank`, `price`, `sale_num` FROM `shop_goods` ORDER BY `sale_num` DESC LIMIT $count,$num");
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
					"img_url"=>$result['img_new']);
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
					"img_url"=>$result['img_rank'],
					"price"=>$result['price'],
					"sale_num"=>$result['sale_num']);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'goods_top'=>$data);
	return json_encode($info);
}