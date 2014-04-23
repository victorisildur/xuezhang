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

/*foreach( $tmp['did_array'] as $did_entry )
{
	$did_array[] = $did_entry['detail_id'];
	echo $did_array;
}*/
/*output <label/> and <input/> for sex field*/
$labelInputForSexMale = '<label for="male">男
							
							<input type="radio" data-bind="checked: sex" name="sex" value="1" id="male">
						</label>';
$labelInputForSexFemale = '<label for="female">女
							
							<input type="radio" data-bind="checked: sex" name="sex" value="0" id="female">
							
						</label>';
/*output <label/> and <input/> for color field*/
$labelInputForColorWhite = ' <label for="white">白色
						<input type="radio" data-bind="checked: color" name="color" value="0" id="white"/> 
						</label>';
$labelInputForColorGrey =  ' <label for="gray">灰色
						<input type="radio" data-bind="checked: color" name="color" value="2" id="gray"/> 
						</label>';
$labelInputForColorBlack = ' <label for="black">黑色
						<input type="radio" data-bind="checked: color" name="color" value="1" id="black"/>
						</label>' ;
/*output <label/> and <input/> for size field*/
$labelInputForSizeM = ' <label for="m">M
						<input type="radio" data-bind="checked: size" name="size" id="m" value="1"> 
						</label>';
$labelInputForSizeL = '<label for="l">L
						<input type="radio" data-bind="checked: size" name="size" id="l" value="2">
						</label>';
$labelInputForSizeXL = '<label for="xl">XL
						<input type="radio" data-bind="checked: size" name="size" id="xl" value="3">
						</label>';
$labelInputForSizeXXL = '<label for="xxl">XXL
						<input type="radio" data-bind="checked: size" name="size" id="xxl" value="4">
						</label>';
require_once(TEMPLATES_PATH.'shop_order.html');