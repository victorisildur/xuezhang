<?php
//调试用的跨域http头
//header("Access-Control-Allow-Origin: *");
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

if(empty($_REQUEST['gid']))
{
	header('Location: index.php');
	exit();
}

$gid = intval($_REQUEST['gid']);
$conn = new DBClass();

$sql = "SELECT `image_name` FROM `shop_images` WHERE `goods_id` = $gid AND `image_type` = 3";//img_detail
$query = $conn->query($sql);
$img_urls = array();
$result = mysql_fetch_array($query);
$img_top = BCS_ROOT.$result['image_name'];
while($result=mysql_fetch_array($query))
{
	$img_urls[] = '<div id="card_border">
						<li>
							<img src="'.BCS_ROOT.$result['image_name'].'"/>
						</li>
					</div>';
}

$sql = "SELECT `title`, `price`, `sale_num`, `description` FROM `shop_goods` WHERE `goods_id` = $gid";

$result = $conn->get_one($sql);

require_once(TEMPLATES_PATH.'detail.html');