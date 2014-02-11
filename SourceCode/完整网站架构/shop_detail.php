<?php
//调试用的跨域http头
//header("Access-Control-Allow-Origin: *");
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

if(empty($_REQUEST['gid'])) die(json_encode(array('status'=>'fail')));

$gid = intval($_REQUEST['gid']);
$conn = new DBClass();

$sql = "SELECT `image_name` FROM `shop_images` WHERE `goods_id` = $gid AND `image_type` = 3";//img_detail
$query = $conn->query($sql);
$img_urls = array();
while($result=mysql_fetch_array($query))
{
	$img_urls[] = BCS_ROOT.$result['image_name'];
}

$sql = "SELECT `title`, `price`, `sale_num`, `description` FROM `shop_goods` WHERE `goods_id` = $gid";

$result = $conn->get_one($sql);

echo json_encode(array('status'=>'ok',
						'goods_detail'=>$img_urls,
						'price'=>$result['price'],
						'sale_num'=>$result['sale_num'],
						'title'=>$result['title'],
						'description'=>$result['description']				
						));