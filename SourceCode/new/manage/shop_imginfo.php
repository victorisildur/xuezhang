<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

if(empty($_REQUEST['gid'])) die();

$gid = intval($_REQUEST['gid']);
$conn = new DBClass();

$sql = "SELECT `image_id`, `image_name`, `image_type` FROM `shop_images` WHERE `goods_id` = $gid";

$query = $conn->query($sql);

$img_new = SITE_ROOT.'static/img/nopic.jpg';
$img_rank = SITE_ROOT.'static/img/nopic.jpg';
$img_new_id = 0;
$img_rank_id = 0;

$html = '';
while($result=mysql_fetch_array($query))
{
	if($result['image_type'] == 1)
	{
		$img_new = BCS_ROOT.$result['image_name'];
		$img_new_id = $result['image_id'];
	}
	else if($result['image_type'] == 2)
	{
		$img_rank = BCS_ROOT.$result['image_name'];
		$img_rank_id = $result['image_id'];
	}
	else
	{
		$img = BCS_ROOT.$result['image_name'];
		$img_id = $result['image_id'];
		$html .= " <div class='col-md-4'>
				  <img src='$img' class='img-responsive'>
				  <p class='img-action'><button type='button' class='btn btn-default img-btn-del-detail' value='$img_id'>删除</button></p>
				  </div>";
	}
}

require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_imginfo.htm');
require_once(TEMPLATES_PATH.'manage/footer.htm');

