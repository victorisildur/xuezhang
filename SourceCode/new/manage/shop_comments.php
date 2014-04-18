<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$conn = new DBClass();
$action = trim($_GET['action']);

switch ($action)
{
case 'change':
	if(check_inputs())
	{
		echo '111';
		$nickname = $conn->sqlesc($_POST['nickname']);
		$content = $conn->sqlesc($_POST['content']);
		$rank = intval($_POST['rank']);
		$cid = intval($_REQUEST['cid']);
		$sql = "UPDATE `shop_comments` SET `nick_name` = $nickname, `content` = $content, `comment_rank` = $rank WHERE `comment_id` = $cid";
		if($conn->query($sql))
		{
			echo json_encode(array('status'=>'ok'));
			die();
		}
	}
	echo json_encode(array('status'=>'fail'));
die();
case 'del':
	$cid = intval($_POST['cid']);
	$sql = "DELETE FROM `shop_comments` WHERE `comment_id` = $cid";
	if($conn->query($sql)) echo json_encode(array('status'=>'ok'));
	else echo json_encode(array('status'=>'fail'));
die();
default:
	$goods_id = intval($_REQUEST['gid']);
	$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	$start = ($page - 1) * 20;
	$end = $start + 20;
	$query = $conn->query("SELECT `nick_name`, `content`, `comment_id`, `comment_rank` FROM `shop_comments` WHERE `goods_id` = $goods_id ORDER BY `comment_id` DESC LIMIT $start, $end");
	$html = '';
while($result=mysql_fetch_array($query))
{
	$cid = $result['comment_id'];
	$rank = $result['comment_rank'];
	$nickname = htmlspecialchars($result['nick_name']);
	$content = htmlspecialchars($result['content']);
	$html .= "<tr id='cid_1'>
                  <td>$cid</td>
                  <td><input type='text' class='form-control' placeholder='昵称' name='nickname' value='$nickname'></td>
                  <td><input type='text' class='form-control' placeholder='内容' name='content' value='$content'></td>
				  <td><input type='number' class='form-control' placeholder='亮' name='rank' value='$rank'></td>
                  <td><button type='button' class='btn btn-info ajax-change'>修改</button> <button type='button' class='btn btn-info ajax-del'>删除</button></td>
                </tr>";
	}
	if($page <= 1)
	{
		$disable_pre = ' disabled';
		$goto_pre = '#';
	}
	else
	{
		$disable_pre = '';
		$goto_pre = basename(__FILE__).'?page='.($page-1);
	}
	if(mysql_num_rows($query) < 20)
	{
		$disable_next = ' disabled';
		$goto_next = '#';
	}
	else
	{
		$disable_next = '';
		$goto_next = basename(__FILE__).'?page='.($page+1);
	}

	$html_page = "<ul class='pager'>
	  <li class='previous$disable_pre'><a href='$goto_pre'>&larr; 上一页</a></li>
	  <li class='next$disable_next'><a href='$goto_next'>下一页 &rarr;</a></li>
	</ul>";
break;
}
require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_comments.htm');
require_once(TEMPLATES_PATH.'manage/footer.htm');

function check_inputs()
{
	if(empty($_POST['cid']))
	{
		 return false;
	}
	if(empty($_POST['nickname']))
	{
		 return false;
	}
	if(empty($_POST['content']))
	{
		 return false;
	}
	 return true;
}