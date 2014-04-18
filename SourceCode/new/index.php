<?php
require_once('common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

$conn = new DBClass();
//设置默认显示数量
$num = 5;

//new
$result = $conn->query("SELECT shop_goods.goods_id, shop_images.image_name FROM `shop_goods`, `shop_images` WHERE shop_goods.goods_id = shop_images.goods_id AND shop_images.image_type = 1 ORDER BY `add_time` DESC LIMIT $num");
if($result) $goods_new_imgs_li = genNewLists($result);
//rank
$result = $conn->query("SELECT shop_goods.goods_id, shop_images.image_name, `price`, `sale_num` FROM `shop_goods`, `shop_images` WHERE shop_goods.goods_id = shop_images.goods_id AND shop_images.image_type = 2 ORDER BY `sale_num` DESC LIMIT $num");
if($result)	$goods_top_li = genRankLists($result);

require_once(TEMPLATES_PATH.'index.htm');
					
function genNewLists($query)
{
	$goods_new_imgs_li = '';
	while($result=mysql_fetch_array($query))
	{
		$gid = $result['goods_id'];
		$img_url = BCS_ROOT.$result['image_name'];
		$goods_new_imgs_li .= "<li>
						<a href='detail.php?gid=$gid' ><img src='$img_url'/></a>
					</li>";
	}

	return $goods_new_imgs_li;
}
function genRankLists($query)
{
	$goods_top_li = '';
	while($result=mysql_fetch_array($query))
	{
		$gid = $result['goods_id'];
		$img_url = BCS_ROOT.$result['image_name'];
		$price = $result['price'];
		$sale_num = $result['sale_num'];
		$goods_top_li .= "<li>
						<a href='detail.php?gid=$gid' ><img src='$img_url' /></a>
						已售：<span text ='$sale_num'> </span>
						售价：<span text ='$price'> </span>
					</li>";
	}
	return $goods_top_li;
}