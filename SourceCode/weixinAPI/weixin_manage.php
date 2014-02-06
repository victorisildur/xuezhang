<?php
require_once('weixin_class_manage.php');

//检查管理权限


$weixin = new WeiXinAPIManage();
$weixin->addArticle('学长处女款，光棍衫！详情点击~','单身恒久远，光棍永流传。我们光棍不代表我们没有摆脱光棍的能力。单身无罪，光棍有理！','http://bcs.duapp.com/xuezhang/gg.jpg','http://xuezhang.duapp.com/');
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