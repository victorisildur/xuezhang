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
$labelInputForSexMale = '<label for="male">
							<input type="radio" data-bind="checked: sex" name="sex" value="1" id="male">
							<span class="check"></span>男
						</label>';
$labelInputForSexFemale = '<label for="female">
							<input type="radio" data-bind="checked: sex" name="sex" value="0" id="female">
							<span class="check"></span>女
						</label>';
/*output <label/> and <input/> for color field*/
$labelInputForColorWhite = ' <label for="white">
						<input type="radio" data-bind="checked: color" name="color" value="0" id="white"/> 
						<span class="check"></span>白色
						</label>';
$labelInputForColorGrey =  ' <label for="gray">
						<input type="radio" data-bind="checked: color" name="color" value="2" id="gray"/> 
						<span class="check"></span>灰色
						</label>';
$labelInputForColorBlack = ' <label for="black">
						<input type="radio" data-bind="checked: color" name="color" value="1" id="black"/>
						<span class="check"></span>黑色
						</label>' ;
/*output <label/> and <input/> for size field*/
$labelInputForSizeM = ' <label for="m">
						<input type="radio" data-bind="checked: size" name="size" id="m" value="1"> 
						<span class="check"></span>M
						</label>';
$labelInputForSizeL = '<label for="l">
						<input type="radio" data-bind="checked: size" name="size" id="l" value="2">
						<span class="check"></span>L
						</label>';
$labelInputForSizeXL = '<label for="xl">
						<input type="radio" data-bind="checked: size" name="size" id="xl" value="3">
						<span class="check"></span>XL
						</label>';
$labelInputForSizeXXL = '<label for="xxl">
						<input type="radio" data-bind="checked: size" name="size" id="xxl" value="4">
						<span class="check"></span>XXL
						</label>';
require_once(TEMPLATES_PATH.'shop_order.html');