<?php
header("Access-Control-Allow-Origin: *");

$action = trim($_GET['action']);

switch ($action)
{
case 'submit':
	echo json_encode(array('status'=>'ok'));
	break;
case 'get':
	$info = array('status'=>'ok',
				  'comments'=>array(
								array(
									'user_name'=>'kobe',
									"comment"=>"这件够骚",
									"comment_id"=>1,
									"light_count"=>200),
								array(
									'user_name'=>'乌鲁木齐',
									"comment"=>"我那无悔的青春",
									"comment_id"=>2,
									"light_count"=>198),
								array(
									'user_name'=>'sss',
									"comment"=>"dsdsdsd",
									"comment_id"=>3,
									"light_count"=>122)
								)
					);
	echo json_encode($info);		  
	break;
case 'rank':
	echo json_encode(array('status'=>'ok'));
	break;
default:
	echo json_encode(array('status'=>'ok'));
	break;
}
