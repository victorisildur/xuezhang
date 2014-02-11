<?php
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");
require_once('../common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');
require_once(BCS_PATH.'bcs.class.php');

$image_id = intval($_POST['img_id']);

//取出文件名
$conn = new DBClass();
$sql = "SELECT `image_name` FROM `shop_images` WHERE `image_id` = $image_id";
$result = $conn->get_one($sql);
if(!$result) echo '{"status" : "failed"}';
$object = $result['image_name'];
//数据库中删除
$sql = "DELETE FROM `shop_images` WHERE `image_id` = $image_id";
if(!$conn->query($sql)) echo '{"status" : "failed"}';
//bcs中删除
$baidu_bcs = new BaiduBCS();
$bucket = 'xuezhang';
$response = $baidu_bcs->delete_object ( $bucket, $object );
if($response->isOK ()) echo '{"status" : "ok"}';