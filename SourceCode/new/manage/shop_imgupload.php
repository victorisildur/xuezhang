<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');
require_once(BCS_PATH.'bcs.class.php');
$conn = new DBClass();
$info = '';
$gid = empty($_GET['gid'])? '': intval($_GET['gid']);
//需要参数：
//img_type, gid, file, filename?
if(check_inputs())
{
	$gid = intval($_REQUEST['gid']);
	$img_type = intval($_POST['img_type']);

	if(!empty($_POST['filename'])) $object = '/'.trim($_POST['filename']);
	else $object = '/'. date('YmdHis'). mt_rand(1000,9999) . '.'.get_file_ext($_FILES['file']["name"]);

	$baidu_bcs = new BaiduBCS();
	$bucket = 'xuezhang';
	$fileUpload = $_FILES['file']["tmp_name"];
	$opt = array ();
	$opt ['acl'] = "public-read";
	$response = $baidu_bcs->create_object ( $bucket, $object, $fileUpload );

	if($response->isOK ()) 
	{
		$image_name = $conn->sqlesc($object);
		$sql = "INSERT INTO `shop_images` (`image_name`, `image_type`, `goods_id`) VALUES ($image_name, $img_type, $gid)";
		if($conn->query($sql)) $info = '图片url: '.BCS_ROOT.$object;
		else $info = '添加失败';
	}
}
require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_imgupload.htm');
require_once(TEMPLATES_PATH.'manage/footer.htm');


function get_file_ext($file_name){ 
	$extend =explode("." , $file_name); 
	$va=count($extend)-1; 
	return $extend[$va]; 
}
function check_inputs()
{
	global $info;
	global $conn;
	if(!isset($_POST['submit'])) return false;
	if(!is_numeric($_REQUEST['gid']))
	{
		$info = '请填写正确的商品gid！';
		 return false;
	}
	$gid = intval($_REQUEST['gid']);
	if(!is_numeric($_POST['img_type']))
	{
		$info = '请填写正确的类型！';
		return false;
	}
	$img_type = intval($_POST['img_type']);
	if($img_type >3)
	{
		$info = '请填写正确的类型！';
		return false;
	}
	if($img_type == 1 || $img_type == 2)//check unique
	{
		$sql = "SELECT `image_id` FROM `shop_images` WHERE `goods_id` = $gid AND `image_type` = $img_type";
		$result = $conn->query($sql);
		if(mysql_num_rows($result) >= 1)
		{
			$info = '已存在该类型的图片！';
			return false;
		}
	}
	if(!is_uploaded_file($_FILES['file']['tmp_name']))
	{
		$info = '请选择文件！';
		return false;
	}
	 return true;
}