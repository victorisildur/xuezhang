<?php
session_start();

require_once('common_functions.php');
require_once('weixin_config.php');
require_once('auth_crypt_class.php');
require_once('database_class.php');

$src = empty($_GET['authcode']) ? die('hacking attempt!') : $_GET['authcode'];

$Crypt=new Crypt();
//进行验证
$info = $Crypt->tripleDESDecrypt($src);
$data = explode('_', $info);
//是否正确解密
if( empty($data[0]) || $data[0] != $_GET['user'] ) die('hacking attempt!');
if( empty($data[1]) || !intval($data[1]) ) die('hacking attempt!');
if( empty($data[2]) || $data[2] != 'xuezhang' ) die('hacking attempt!');
//5 min 有效期
if( time() - $data[1] >= 300 ) die('链接过期，请重新获取验证链接！');

//设置session，查数据库...
$_SESSION['open_id'] = $data[0];
$conn = new DBClass();
$sql = "SELECT * FROM `shop_users` WHERE `open_id` = ".$conn->sqlesc($data[0]);
$result = $conn->get_one($sql);
if(!empty($result))
{
	$_SESSION['name'] = $result['name'];
	$_SESSION['phone'] = $result['phone'];
	$_SESSION['addr'] = $result['address'];
	$_SESSION['time'] = $result['best_time'];
	$sql = 'UPDATE `shop_users` SET `last_login`='.time().', `last_ip`='.$conn->sqlesc(getip());
	$conn->query($sql);
}

//重定向到页面（只能站内重定向）
if(!empty($_GET['url'])) header('Location:'.SITE_ROOT.$_GET['url']);
else header('Location:'.SITE_ROOT);
