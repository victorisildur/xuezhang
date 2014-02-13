<?php
require_once('../common.inc.php');
//登陆检查
require_once(COMMON_PATH.'manage_functions.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
$start = ($page - 1) * 20;
$end = $start + 20;

$conn = new DBClass();
$sql = "SELECT `goods_id`, `title`, `price` FROM `shop_goods` ORDER BY `goods_id` DESC LIMIT $start, $end";


$query = $conn->query($sql);
$html = '';
while($result=mysql_fetch_array($query))
{
	$gid = $result['goods_id'];
	$title = htmlspecialchars($result['title']);
	$price = $result['price'];
	$html .= "<tr id='gid_$gid'>
                  <td>$gid</td>
                  <td>$title</td>
                  <td>$price</td>
                  <td><a href='shop_itemchange.php?gid=$gid'>修改</a> <a href='#' class='ajax-del'>删除</a> <a href='shop_imginfo.php?gid=$gid' >图片管理</a></td>
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

$page = "<ul class='pager'>
  <li class='previous$disable_pre'><a href='$goto_pre'>&larr; 上一页</a></li>
  <li class='next$disable_next'><a href='$goto_next'>下一页 &rarr;</a></li>
</ul>";

require_once(TEMPLATES_PATH.'manage/header.htm');
require_once(TEMPLATES_PATH.'manage/left.htm');
require_once(TEMPLATES_PATH.'manage/shop_itemlist.htm');
require_once(TEMPLATES_PATH.'manage/footer.htm');