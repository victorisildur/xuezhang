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
$labelInputForSexMale = '<div class="radio ">
							<label for="male">
								<input type="radio" data-bind="checked: sex" name="sex" value="1" id="male">
								男
							</label>
						 </div>';
$labelInputForSexFemale = '<div class="radio">
								<label for="female">
								<input type="radio" data-bind="checked: sex" name="sex" value="0" id="female">
								女
								</label>
							</div>';
/*output <label/> and <input/> for color field*/
$labelInputForColorWhite = ' <div class="radio">
								<label for="white">
								<input type="radio" data-bind="checked: color" name="color" value="0" id="white"/> 
								白色
								</label>
							</div>';
$labelInputForColorGrey =  ' <div class="radio">
								<label for="gray">
								<input type="radio" data-bind="checked: color" name="color" value="2" id="gray"/> 
								灰色
								</label>
							</div>';
$labelInputForColorBlack = ' <div class="radio">
								<label for="black">
								<input type="radio" data-bind="checked: color" name="color" value="1" id="black"/>
								黑色
								</label>
							</div>' ;
/*output <label/> and <input/> for size field*/
$labelInputForSizeM = ' <div class="radio">
							<label for="m">
							<input type="radio" data-bind="checked: size" name="size" id="m" value="1"> 
							M
							</label>
						</div>';
$labelInputForSizeL = ' <div class="radio">
							<label for="l">
							<input type="radio" data-bind="checked: size" name="size" id="l" value="2">
							L
							</label>
						</div>';
$labelInputForSizeXL = '<div class="radio">
							<label for="xl">
							<input type="radio" data-bind="checked: size" name="size" id="xl" value="3">
							XL
							</label>
						</div>';
$labelInputForSizeXXL = '<div class="radio">
							<label for="xxl">
							<input type="radio" data-bind="checked: size" name="size" id="xxl" value="4">
							XXL
							</label>
						</div>';
require_once(TEMPLATES_PATH.'shop_order.html');