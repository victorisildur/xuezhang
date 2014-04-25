<?php
//装载数据库类
require_once('common.inc.php');
require_once(COMMON_PATH.'database_class.php');
require_once(COMMON_PATH.'common_functions.php');
//调试用的跨域http头
//header("Access-Control-Allow-Origin: *");
$conn = new DBClass();
$action = trim($_GET['action']);

switch ($action)
{
case 'submit':
	if($_COOKIE['comment_times'] == 3) die(json_encode(array('status'=>'many')));
	if(empty($_COOKIE['comment_times'])) setcookie('comment_times', 1);
	else setcookie('comment_times', $_COOKIE['comment_times']++);
	if(empty($_POST['comment'])) die(json_encode(array('status'=>'fail')));
	$goods_id = intval($_POST['gid']);
	$nick_name = $conn->sqlesc($_POST['user_name']);
	$content = $conn->sqlesc($_POST['comment']);
	$time = time();
	$ip = getip();
	//echo "INSERT INTO `shop_comments` (`goods_id`, `nick_name`, `content`, `add_time`, `ip_address`) VALUES ($goods_id, $nick_name, $content,$time, '$ip')";
	if($conn->query("INSERT INTO `shop_comments` (`goods_id`, `nick_name`, `content`, `add_time`, `ip_address`) VALUES ($goods_id, $nick_name, $content,$time, '$ip')")) echo json_encode(array('status'=>'ok'));
	else echo json_encode(array('status'=>'fail'));
	die();
case 'get':
	$goods_id = intval($_POST['gid']);
	$count = empty($_POST['count'])? 0 : intval($_POST['count']);
	$num = empty($_POST['num'])? 5 : intval($_POST['num']);
	$type = trim($_POST['type']);
	$method = $type=='time' ? '`add_time`' : '`comment_rank`';
	//echo "SELECT `nick_name`, `content`, `comment_id`, `comment_rank` FROM `shop_comments` WHERE `goods_id` = $goods_id ORDER BY $method DESC LIMIT $count,$num";
	$query = $conn->query("SELECT `nick_name`, `content`, `comment_id`, `comment_rank` FROM `shop_comments` WHERE `goods_id` = $goods_id ORDER BY $method DESC LIMIT $count,$num");
	if(!$query)
	{
		echo json_encode(array('status'=>'fail'));
	}
	else{
		$data = array();
		while($result=mysql_fetch_array($query))
		{
			$item = array(
						'user_name'=>$result['nick_name'],
						"comment"=>$result['content'],
						"comment_id"=>$result['comment_id'],
						"light_count"=>$result['comment_rank']);
			$data[] = $item;
		}
		$info = array('status'=>'ok',
					  'comments'=>$data);
		echo json_encode($info);
	}
	die();
case 'rank':
	$comment_id = intval($_POST['comment_id']);
	//echo "UPDATE `shop_comments` SET `comment_rank` = `comment_rank` + 1 WHERE `comment_id` = $comment_id";
	if($conn->query("UPDATE `shop_comments` SET `comment_rank` = `comment_rank` + 1 WHERE `comment_id` = $comment_id")) echo json_encode(array('status'=>'ok'));
	else echo json_encode(array('status'=>'fail'));
	die();
default:
	echo json_encode(array('status'=>'fail'));
	die();
}
