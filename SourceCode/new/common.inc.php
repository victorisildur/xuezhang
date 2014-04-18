<?php
//微信常量定义
define("WEIXIN_TOKEN", "xuezhangkankandoulaikankana");
define("WEIXIN_APPID", "weixin");
define("WEIXIN_APPSECRET", "weixin");
define("AUTH_KEY", '88DB8EAC3AF3B0A7A28D3F10'); //24个字母，用于微信openid验证用户

//bcs api 常量定义
define ( 'BCS_AK', 'DkdnqXavvTUpgWxhAlfADlAr' );//AK 公钥
define ( 'BCS_SK', 'CV52cYDi8DcXoNljDUxqGgGYKj2Aj47W' );//SK 私钥
define ( 'BCS_SUPERFILE_POSTFIX', '_bcs_superfile_' );//superfile 每个object分片后缀
define ( 'BCS_SUPERFILE_SLICE_SIZE', 1024 * 1024 );//sdk superfile分片大小 ，单位 B（字节）

//后台用户名密码
define("ADMIN_NAME", "xuezhang");
define("ADMIN_PWD", "openthedoor");

//路径常量定义
define("SITE_ROOT", "http://xuezhang.cc/");
define("BCS_ROOT", "http://bcs.duapp.com/xuezhang");
define ('ROOT_PATH',  dirname(__FILE__).'/');
define ('COMMON_PATH',  ROOT_PATH.'common/');
define ('STATIC_PATH',  ROOT_PATH.'static/');
define ('TEMPLATES_PATH',  ROOT_PATH.'templates/');
define ('BCS_PATH',  ROOT_PATH.'bcs/');
define ('MANAGE_PATH',  ROOT_PATH.'manage/');


//其他常量
define("WEIXIN_TEXT", "0");
define("WEIXIN_NEWS", "1");
define("WEIXIN_IMAGE", "2");
define("WEIXIN_VOICE", "3");
define("WEIXIN_VIDEO", "4");
define("WEIXIN_MUSIC", "5");