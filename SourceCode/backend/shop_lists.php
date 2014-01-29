<?php
//调试用的跨域http头
header("Access-Control-Allow-Origin: *");

$action = trim($_GET['action']);

switch ($action)
{
case 'new':
	$info = array('status'=>'ok',
				  'goods_new'=>array(
								array(
									'goods_id'=>1,
									"img_url"=>"img/grad1.jpg"),
								array(
									'goods_id'=>2,
									"img_url"=>"img/jiaotong1.jpg"),
								array(
									'goods_id'=>3,
									"img_url"=>"img/batman.jpg")
								)
					);
	echo json_encode($info);		  
	break;
case 'rank':
	array('status'=>'ok',
				  'goods_top'=>array(
								array(
									'goods_id'=>1,
									"img_url"=>"img/grad1.jpg"),
								array(
									'goods_id'=>2,
									"img_url"=>"img/jiaotong1.jpg"),
								array(
									'goods_id'=>3,
									"img_url"=>"img/batman.jpg")
								)
					);
	break;
default:
	echo json_encode(array('status'=>'fail'，
							'msg'=>'没有了~'));
	break;
}