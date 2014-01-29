<?php
//装载数据库类
require_once('database_class.php');
require_once('common_functions.php');
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");
$conn = new DBClass();
$action = trim($_GET['action']);

switch ($action)
{
case 'submit':
	$goods_id = intval($_POST['gid']);
	$nick_name = $conn->sqlesc($_POST['user_name']);
	$content = $conn->sqlesc($_POST['comment']);
	$time = time();
	$ip = getip();
	if($conn->query("INSERT INTO `shop_comments` (`goods_id`, `nick_name`, `content`, `add_time`, `ip_address`) VALUES ($goods_id, '$nick_name', '$content',$time, '$ip')")) echo json_encode(array('status'=>'ok'));
	else echo json_encode(array('status'=>'fail'));
	die();
case 'get':
	$goods_id = intval($_POST['gid']);
	$count = empty($_POST['count'])? 0 : intval($_POST['count']);
	$num = empty($_POST['num'])? 5 : intval($_POST['num']);
	$type = trim($_POST['type']);
	$method = $type=='time' ? '`add_time`' : '`comment_rank`';
	$result = $conn->query("SELECT `nick_name`, `content`, `comment_id`, `comment_rank` FROM `shop_comments` WHERE `goods_id` = $goods_id ORDER BY $method DEC LIMIT $count,$num");
	if($result)	echo genComments($result['nick_name'], $result['content'], $result['comment_id'], $result['comment_rank']);
	else echo json_encode(array('status'=>'fail'));		  
	die();
case 'rank':
	$comment_id = intval($_POST['comment_id']);
	if($conn->query("UPDATE `shop_comments` SET `comment_rank` = `comment_rank` + 1 WHERE `comment_id` = $comment_id")) echo json_encode(array('status'=>'ok'));
	else echo json_encode(array('status'=>'fail'));
	die();
default:
	echo json_encode(array('status'=>'fail'));
	die();
}


function genComments($user_name, $comment, $comment_id, $light_count)
{
	$data = array();
	$num = count($comment_id);
	for($i=0; $i<$num; $i++)
	{
		$item = array(
					'user_name'=>$user_name[$i],
					"comment"=>$comment[$i],
					"comment_id"=>$comment_id[$i],
					"light_count"=>$light_count[$i]);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'comments'=>$data);
	return json_encode($info);
}