/* access_token数据表，只有一行数据，只能用UPDATE操作 */
DROP TABLE IF EXISTS `weixin_access_token`;
CREATE TABLE IF NOT EXISTS `weixin_access_token`
(
	`access_token` varchar(100) NOT NULL DEFAULT '0',
	`expire_time` int(15) NOT NULL DEFAULT 0,
	PRIMARY KEY (`access_token`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `weixin_access_token` (`access_token`, `expire_time`) VALUES ('0', 0);

/* autoreply数据表，用于匹配和返回自动回复内容，answer为xml格式 */
DROP TABLE IF EXISTS `weixin_autoreply`;
CREATE TABLE IF NOT EXISTS `weixin_autoreply`
(
	`id` int(10) NOT NULL auto_increment,
	`question` varchar(100) NOT NULL DEFAULT 0,
	`answer` text NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* 商品comment表 */
DROP TABLE IF EXISTS `shop_comments`;
CREATE TABLE `shop_comments` (
	`comment_id` int(10) unsigned NOT NULL auto_increment,
	`goods_id` int(10) unsigned NOT NULL,
	`nick_name` varchar(60) NOT NULL default '学长x',
	`content` text,
	`comment_rank` mediumint(8) unsigned NOT NULL default 0,
	`add_time` int(10) unsigned NOT NULL default 0,
	`ip_address` varchar(15) NOT NULL default '',
	PRIMARY KEY  (`comment_id`)
)  TYPE=MyISAM DEFAULT CHARSET=utf8;

/* 商品表，每个商品有新品和榜单两个img_url */
DROP TABLE IF EXISTS `shop_goods`;
CREATE TABLE `shop_goods` (
	`goods_id` mediumint(8) unsigned NOT NULL auto_increment,
	`title` varchar(120) NOT NULL default '',
	`price` decimal(10,2) unsigned NOT NULL default 0.00,
	`sale_num` int(10) unsigned NOT NULL default 0,
	`add_time` int(10) unsigned NOT NULL default 0,
	`description` text,
	PRIMARY KEY  (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* 订单表，一个order_id对应很多goods_id */
DROP TABLE IF EXISTS `shop_orders`;
CREATE TABLE `shop_orders` (
	`rec_id` mediumint(8) unsigned NOT NULL auto_increment,
	`order_sn` varchar(20) NOT NULL default '',
	`goods_id` mediumint(8) unsigned NOT NULL default '0',
	`color` varchar(10)  NOT NULL default 'white',
	`size` varchar(10)  NOT NULL default 'xl',
	`num` smallint(6)  NOT NULL default '1',
	PRIMARY KEY  (`rec_id`),
	KEY `shop_order_status` (`order_sn`),
	KEY `shop_goods` (`goods_id`)
)  TYPE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* 订单状态表，用户地址以及状态 */
/* pay_status 有三个状态：
0:待确定
1:配送中
2:已完成
3:已取消
order_sn 用来唯一标识一个订单，类似secret key，展示给客户
*/
DROP TABLE IF EXISTS `shop_order_status`;
CREATE TABLE `shop_order_status` (
	`order_id` mediumint(8) unsigned NOT NULL auto_increment,
	`order_sn` varchar(20) NOT NULL default '',
	`phone` varchar(60) NOT NULL default '',
	`real_name` varchar(120) NOT NULL default '',
	`best_time` varchar(120) NOT NULL default '',
	`address` varchar(255) NOT NULL default '',
	`total_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
	`pay_status` tinyint(2) unsigned NOT NULL default '0',
	`create_time` int(10) unsigned NOT NULL default '0',
	`complete_time` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (`order_id`),
	KEY `shop_orders` (`order_sn`)
	)  TYPE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	
/* 用户表 ---未完成 */
DROP TABLE IF EXISTS `shop_users`;
CREATE TABLE `shop_users` (
	`user_id` smallint(5) unsigned NOT NULL auto_increment,
	`open_id` varchar(40) NOT NULL default '',
	`name` varchar(60) NOT NULL default '',
	`address` varchar(255) NOT NULL default '',
	`phone` varchar(60) NOT NULL default '',
	`best_time` varchar(120) NOT NULL default '',
	`last_login` int(11) NOT NULL default '0',
	`last_ip` varchar(15) NOT NULL default '',
	PRIMARY KEY  (`user_id`),
	KEY `open_id` (`open_id`)
)TYPE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* 上传图片表 */
/* image_type
0 null
1 img_new
2 img_rank
3 img_detail
*/
DROP TABLE IF EXISTS `shop_images`;
CREATE TABLE `shop_images` (
	`image_id` mediumint(8) unsigned NOT NULL auto_increment,
	`image_name` varchar(255) NOT NULL default '',
	`image_type` tinyint(2) unsigned NOT NULL default '0',
	`goods_id` int(10) unsigned NOT NULL,
	PRIMARY KEY  (`image_id`),
	KEY `shop_goods` (`goods_id`)
)TYPE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
