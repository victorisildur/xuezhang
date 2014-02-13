<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$info = '';
if(empty($_REQUEST['gid']))
{
	$info = '商品id不能为空！';
}
else if(check_inputs())
{
	$conn = new DBClass();
	$title = $conn->sqlesc($_POST['title']);
	$price = floatval($_POST['price']);
	$gid = intval($_REQUEST['gid']);
	$description = $conn->sqlesc($_POST['description']);
	$time = time();

	//echo "INSERT INTO `shop_goods` (`title`, `price`, `img_new`, `img_rank`, `add_time`, `description`, `images`) VALUES ($title, $price, $img_new, $img_rank, $time, $description, $images)";
	$result = $conn->query("UPDATE `shop_goods` SET `title` = $title, `price` = $price, `add_time` = $time, `description` = $description WHERE `goods_id` = $gid");
	if($result)	$info = '修改成功';
	else $info = '修改失败';
	$title = htmlspecialchars($_POST['title']);
	$description =htmlspecialchars($_POST['description']);

}
else
{
	$conn = new DBClass();
	$gid = intval($_REQUEST['gid']);
	$sql = "SELECT `title`, `price`, `description` FROM `shop_goods` WHERE `goods_id` = $gid";
	$result = $conn->get_one($sql);
	$title = htmlspecialchars($result['title']);
	$price = htmlspecialchars($result['price']);
	$description = htmlspecialchars($result['description']);
}

require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_itemchange.htm');
require_once(TEMPLATES_PATH.'manage/footer.htm');

function check_inputs()
{
	global $info;
	if(!isset($_POST['submit'])) return false;
	if(empty($_POST['title']))
	{
		$info = '商品名称不能为空！';
		 return false;
	}
	if(empty($_POST['price']))
	{
		$info = '商品价格不能为空！';
		 return false;
	}
	if(empty($_POST['description']))
	{
		$info = '商品描述不能为空！';
		 return false;
	}

	 return true;
}