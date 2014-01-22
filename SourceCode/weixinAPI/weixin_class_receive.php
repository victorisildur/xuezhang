<?php
require_once('weixin_class_base.php');
//装载模板文件
require_once('weixin_tpl_receive.php');

class WeiXinAPIReceive extends WeiXinAPIBase
{
	public $postObj;
	private $fromUsername;
	private $toUsername;
	public $form_MsgType;
	
	//NEWS相关
	private $newsCount = 0;
	private $newsBody = '';
/* 
* 构造函数
*/
	function __construct()
	{
		$this->parsePostStr();
	}

/* 
* 把post来的数据进行处理
*/
	private function parsePostStr()
	{
		//获取微信发送数据
		//if(isset($HTTP_RAW_POST_DATA)) $postStr = $HTTP_RAW_POST_DATA;
		//else return false;
		
		$postStr =  trim($GLOBALS['HTTP_RAW_POST_DATA']);

		//返回回复数据
		if (!empty($postStr))
		{  
			//解析数据
			  $this->postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			//发送消息方ID
			  $this->fromUsername = $this->postObj->FromUserName;
			//接收消息方ID
			  $this->toUsername = $this->postObj->ToUserName;
			//消息类型
			  $this->form_MsgType = $this->postObj->MsgType;
			  
			  return true;
		}
		else 
		{
			  return false;
		}
	}
/*
* 各种类型消息返回信息生成开始
*/
	//纯文本消息
	public function genTextMsg($contentStr)
	{
		return sprintf(WEIXIN_TEXT_MSG, $this->fromUsername, $this->toUsername, time(), $contentStr);
	}
	//图文消息
	//$url点击图文消息跳转链接
	//$picUrl图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
	public function addArticle($title, $description, $picUrl, $url)
	{
		if($this->newsCount > 10) return false;
		$this->newsBody += sprintf(WEIXIN_NEWS_MSG_BODY, $title, $description, $picUrl, $url);
		$this->newsCount += 1;
		return true;
	}
	public function addArticleFinish()
	{
		$this->newsBody = sprintf(WEIXIN_NEWS_MSG_BEGIN, $this->fromUsername, $this->toUsername, time(), $this->newsCount) + $this->newsBody;
		$this->newsBody += WEIXIN_NEWS_MSG_END;
		return $this->newsBody;
	}
	//图片消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genImageMsg($mediaId)
	{
		return sprintf(WEIXIN_IMAGE_MSG, $this->fromUsername, $this->toUsername, time(), $mediaId);
	}
	//语音消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genVoiceMsg($mediaId)
	{
		return sprintf(WEIXIN_VOICE_MSG, $this->fromUsername, $this->toUsername, time(), $mediaId);
	}
	//视频消息
	//$mediaId通过上传多媒体文件，得到的id，必须
	//其余项非必须
	public function genVideoMsg($mediaId, $title = '', $description = '')
	{
		return sprintf(WEIXIN_VIDEO_MSG, $this->fromUsername, $this->toUsername, time(), $mediaId, $title, $description);
	}
	//音乐消息
	//$mediaId缩略图的媒体id，通过上传多媒体文件，得到的id
	//$mediaId为必须项，其他项非必须
	//$HQMusicUrl高质量音乐链接，WIFI环境优先使用该链接播放音乐
	public function genMusicMsg($mediaId, $title = '', $description = '', $musicUrl = '', $HQMusicUrl = '')
	{
		return sprintf(WEIXIN_MUSIC_MSG, $this->fromUsername, $this->toUsername, time(), $title, $description, $musicUrl, $HQMusicUrl, $mediaId);
	}
//结束
}