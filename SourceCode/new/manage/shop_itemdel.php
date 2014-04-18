<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$gid = intval($_POST['gid']);

$conn = new DBClass();
$sql = "DELETE FROM `shop_goods` WHERE `goods_id` = $gid";
if(!$conn->query($sql)) echo '{"status" : "failed"}';
else echo '{"status" : "ok"}';