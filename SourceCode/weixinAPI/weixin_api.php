<?php
require_once('weixin_class.php');

$weixin = new WeiXinAPI();

//������һ�����ڽӿ���֤ʱ����
//$weixin->valid();
header('Content-Type: text/xml');
switch ($weixin->form_MsgType)
{
	case 'event'://��ע/ȡ����ע�¼�
		if($weixin->postObj->Event == 'subscribe')
		{
			echo $weixin->genTextMsg('���������͹������룡');
		}
		else if($weixin->postObj->Event == 'unsubscribe')
		{
			echo $weixin->genTextMsg('�ֵܣ��´�����ʱ��ǵ��������ӣ�һȺ����ү�ǲ��ù�����');
		}
		die();
	case 'text'://�ı���Ϣ
		echo $weixin->genTextMsg('�͹�Ī�ļ������ȥ��ͷ�ƹ�����ƣ�');
		die();
	/*
	case '':
	
		break;
	case '':
	
		break;
	*/
	default:
		echo $weixin->genTextMsg('�ţ������ˣ��Ե�Ƭ�̣�');
		die();
}
