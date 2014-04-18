<?php
require_once('../common.inc.php');
//装载数据库类
require_once(COMMON_PATH.'weixin_class_manage.php');

//检查管理权限


$weixin = new WeiXinAPIManage();
$weixin->addArticle('学长就是行动派！','我们是行动派，我们相信动作比语言有力，我们是实践派，我们相信动手比幻想实用！','http://bcs.duapp.com/xuezhang/201402131935057428.jpg','http://xuezhang.duapp.com/');
$weixin->addArticle('学长热销榜','手速慢就什么都没了哦','http://bcs.duapp.com/xuezhang/fire.jpg','http://xuezhang.duapp.com/index.html#page_bestsales');
$weixin->addQuestionAnswer('1', $weixin->addArticleFinish());
echo $weixin->getQuestionAnswer('1');
/*
switch (trim($_POST['action']))
{
	case 'add':
	
	die();
}	
//1
*/