<?php
session_start();
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');
require_once(COMMON_PATH.'common_functions.php');
require_once(COMMON_PATH.'shop_functions.php');

$conn = new DBClass();
$gid = intval($_GET['gid']);
$data = GetDetailsByGID($gid);
/*
$color = array();
$size = array();
$sex = array();

foreach($data as $result)
{
	$color[] = $result['color'];
	$size[] = $result['size'];
	$sex[] = $result['sex'];
}
*/

$tmp['did_array'] = $data;
$json = json_encode($tmp);

require_once(TEMPLATES_PATH.'shop_order.html');