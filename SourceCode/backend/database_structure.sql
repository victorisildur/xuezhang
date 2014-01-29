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
	`answer` text NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* 商品comment表 */
DROP TABLE IF EXISTS `shop_comments`;
CREATE TABLE `shop_comment` (
	`comment_id` int(10) unsigned NOT NULL auto_increment,
	`goods_id` int(10) unsigned NOT NULL,
	`nick_name` varchar(60) NOT NULL default '学长x',
	`content` text,
	`comment_rank` mediumint(8) unsigned NOT NULL default '0',
	`add_time` int(10) unsigned NOT NULL default '0',
	`ip_address` varchar(15) NOT NULL default '',
	PRIMARY KEY  (`comment_id`),
)  TYPE=MyISAM DEFAULT CHARSET=utf8;

/* 商品表，每个商品有新品和榜单两个img_url */
DROP TABLE IF EXISTS `shop_goods`;
CREATE TABLE `shop_goods` (
	`goods_id` mediumint(8) unsigned NOT NULL auto_increment,
	`title` varchar(120) NOT NULL default '',
	`price` decimal(10,2) unsigned NOT NULL default '0.00',
	`sale_num` int(10) unsigned NOT NULL default '0',
	`img_new` varchar(255) NOT NULL default '',
	`img_rank` varchar(255) NOT NULL default '',
	`description` text,
	`images` text,
	PRIMARY KEY  (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;











