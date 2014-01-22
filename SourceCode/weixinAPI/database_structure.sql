/* access_token数据表，只有一行数据，只能用UPDATE操作 */
DROP TABLE IF EXISTS `weixin_access_token`;
CREATE TABLE IF NOT EXISTS `weixin_access_token`
(
	`access_token` varchar(100) NOT NULL DEFAULT '0',
	`expire_time` int(15) NOT NULL DEFAULT 0,
	PRIMARY KEY (`access_token`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `weixin_access_token` (`access_token`, `expire_time`) VALUES ('0', 0);