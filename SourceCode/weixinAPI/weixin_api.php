<?php
require_once('weixin_class_receive.php');

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
		echo $weixin->answerText();
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
