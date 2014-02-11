<?php
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");
require_once('../common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

if(empty($_REQUEST['gid'])) die(json_encode(array('status'=>'fail')));

$gid = intval($_REQUEST['gid']);
$conn = new DBClass();

$sql = "SELECT `image_id`, `image_name`, `image_type` FROM `shop_images` WHERE `goods_id` = $gid";

$query = $conn->query($sql);

if(!$query)
{
	echo json_encode(array('status'=>'fail'));
}
else{
	$data = array();
	while($result=mysql_fetch_array($query))
	{
		$item = array(
					'img_url'=>BCS_ROOT.$result['image_name'],
					"img_id"=>$result['image_id'],
					"img_type"=>$result['image_type']);
		$data[] = $item;
	}
	$info = array('status'=>'ok',
				  'images'=>$data);
	echo json_encode($info);
}
