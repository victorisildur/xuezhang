<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$info = '';

if(check_inputs())
{

	$conn = new DBClass();
	$title = $conn->sqlesc($_POST['title']);
	$price = floatval($_POST['price']);
	$description = $conn->sqlesc($_POST['description']);
	$time = time();

	//echo "INSERT INTO `shop_goods` (`title`, `price`, `img_new`, `img_rank`, `add_time`, `description`, `images`) VALUES ($title, $price, $img_new, $img_rank, $time, $description, $images)";
	$result = $conn->query("INSERT INTO `shop_goods` (`title`, `price`, `add_time`, `description`) VALUES ($title, $price, $time, $description)");
	if($result)	$info = '添加成功';
	else $info = '添加失败';

}

require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_itemadd.htm');
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

	 return true;
}