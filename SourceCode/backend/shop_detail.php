<?php
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");
//装载数据库类
require_once('database_class.php');

if(empty($_REQUEST['gid'])) die(json_encode(array('status'=>'fail')));

$gid = intval($_REQUEST['gid']);
$conn = new DBClass();

$sql = "SELECT `title`, `price`, `sale_num`, `description`, `images` FROM `shop_goods` WHERE `goods_id` = $gid";

$result = $conn->get_one($sql);

$img_detail = explode('|', $result['images']);

$img_urls = array();

foreach ($img_detail as $url)
{
	$img_urls[] = array('img_url'=>$url);
}

echo json_encode(array('status'=>'ok',
						'goods_detail'=>$img_urls,
						'price'=>$result['price'],
						'sale_num'=>$result['sale_num'],
						'title'=>$result['title'],
						'description'=>$result['description']				
						));