<?php
require_once('../common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');
require_once(BCS_PATH.'bcs.class.php');

//需要参数：
//img_type, gid, file, filename?
if(empty($_POST['submit'])) die();
if(!is_numeric($_POST['img_type'])) die();
if(!is_numeric($_POST['gid'])) die();

$gid = intval($_POST['gid']);
$img_type = intval($_POST['img_type']);
if($img_type >3) die();

if(!empty($_POST['filename'])) $object = '/'.trim($_POST['filename']);
else $object = '/'. date('YmdHis'). mt_rand(1000,9999) . get_file_ext($_FILES['file']["name"]);

$baidu_bcs = new BaiduBCS();
$bucket = 'xuezhang';
$opt = array ();
$opt ['acl'] = "public-read";
$response = $baidu_bcs->create_object ( $bucket, $object, $fileUpload );

if($response->isOK ()) 
{
	$conn = new DBClass();
	$image_name = $conn->sqlesc($object);
	$sql = "INSERT INTO `shop_images` (`image_name`, `image_type`, `goods_id`) VALUES ($image_name, $img_type, $gid)";
	if($conn->query($sql)) echo 'Image Url: '.BCS_ROOT.$object;;
	else echo '添加失败';
}

function get_file_ext($file_name){ 
	$extend =explode("." , $file_name); 
	$va=count($extend)-1; 
	return $extend[$va]; 
}