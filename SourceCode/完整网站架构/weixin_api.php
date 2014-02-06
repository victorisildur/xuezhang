<?php
require_once('common.inc.php');
require_once(COMMON_PATH.'weixin_class_receive.php');
require_once(COMMON_PATH.'auth_crypt_class.php');

$weixin = new WeiXinAPIReceive();

//仅仅第一次用于接口验证时调用
//$weixin->valid();
header('Content-Type: text/xml');
switch ($weixin->form_MsgType)
{
	case 'event'://关注/取消关注事件
		if($weixin->postObj->Event == 'subscribe')
		{
			echo $weixin->genTextMsg('来来来，客官里面请！');
		}
		else if($weixin->postObj->Event == 'unsubscribe')
		{
			echo $weixin->genTextMsg('兄弟，下次来的时候记得拉点妹子，一群大老爷们不好过啊！');
		}
		die();
	case 'text'://文本消息
		$content = $weixin->postObj->Content;
		if (preg_match("/dg\d+$/i", $content)) echo $weixin->genTextMsg(genAuthURL(str_replace('dg', '', $content)));
		else echo $weixin->answerText();
		die();
	/*
	case '':
	
		break;
	case '':
	
		break;
	*/
	default:
		echo $weixin->genTextMsg('麻烦客官再说一遍~');
		die();
}

function genAuthURL($id = '')
{
	global $weixin;
	$Crypt=new Crypt();
	$fromUser = $weixin->postObj->FromUserName;
	//产生验证url
	$src = $fromUser.'_'.time().'_xuezhang';
	$authcode = $Crypt->tripleDESEncrypt($src, true);
	return SITE_ROOT.'weixin_auth.php?authcode='.$authcode.'&user='.$fromUser.'&url=';
}