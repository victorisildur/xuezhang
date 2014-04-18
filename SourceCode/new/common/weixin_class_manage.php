<?php
require_once(COMMON_PATH.'weixin_class_base.php');
require_once(COMMON_PATH.'weixin_tpl_receive.php');

//图文消息的时候，表单的name应为数组形式 eg: <input name = 'Url[]' />
//目前只能自动回复文本、音乐、图文三种消息
//即与MediaId有关的形式不能回复

//可能有xml的注入和dos，暂未考虑

class WeiXinAPIVairable
{
	public $MsgType;
	public $ToUserName;
	public $FromUserName;
	public $Content;//TEXT
	public $MediaId;
	public $Title;
	public $Description;
	public $MusicURL;
	public $HQMusicUrl;
	public $ThumbMediaId;
	public $ArticleCount;
	public $PicUrl;
	public $Url;
/*	
	function __construct()
	{
		$this->parsePost();
	}
*/	
	public function parsePost()
	{
		$acceptableMsgType = array('text', 'image', 'voice', 'video', 'music', 'news');
		$this->MsgType = trim($_POST['MsgType']);
		//MsgType check
		if(!in_array($this->MsgType, $acceptableMsgType)) return false;
		$this->ToUserName = trim($_POST['ToUserName']);
		$this->FromUserName = trim($_POST['FromUserName']);
		$this->Content = trim($_POST['Content']);
		$this->MediaId = trim($_POST['MediaId']);
		$this->Title = trim($_POST['Title']);
		$this->Description = trim($_POST['Description']);
		$this->MusicURL = trim($_POST['MusicURL']);
		$this->HQMusicUrl = trim($_POST['HQMusicUrl']);
		$this->ThumbMediaId = trim($_POST['ThumbMediaId']);
		$this->PicUrl = trim($_POST['PicUrl']);
		$this->Url = trim($_POST['Url']);
		$this->ArticleCount = count($_POST['Url']);
	}
	
	public function parseXML()
	{
	
	}

}

class WeiXinAPIManage extends WeiXinAPIBase
{
	public $postObj;
	//NEWS相关
	private $newsCount = 0;
	private $newsBody = '';

/* 
* 构造函数,子类的构造函数实际上是覆盖(override)了父类的构造函数
*/
	function __construct()
	{
		parent::__construct();
		$this->newsCount = 0;
		$this->newsBody = '';
	}

	public function addQuestionAnswer($question, $answer)
	{
		$question = $this->conn->sqlesc($question);
		$answer = $this->conn->sqlesc($answer);
		$this->conn->query("INSERT INTO `weixin_autoreply` (`question`, `answer`) VALUES ($question, $answer);");
		return true;
	}
	
	public function getQuestionAnswer($question)
	{
		$question = $this->conn->sqlesc($question);
		$result = $this->conn->get_one("SELECT `answer` from `weixin_autoreply` WHERE question = $question");
		return $result['answer'];
	}
	
	public function updateQuestionAnswer($question, $answer)
	{
		$question = $this->conn->sqlesc($question);
		$answer = $this->conn->sqlesc($answer);
		$this->conn->query("UPDATE `weixin_autoreply` SET (`question`, `answer`) VALUES ($question, $answer);");
		return true;
	}
	
/*
* 各种类型消息返回信息生成开始
*/
	//纯文本消息
	public function genTextMsg($contentStr)
	{
		return sprintf(WEIXIN_TEXT_MSG, '{FromUsername}', '{ToUsername}', time(), $contentStr);
	}
	//图文消息
	//$url点击图文消息跳转链接
	//$picUrl图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
	public function addArticle($title, $description, $picUrl, $url)
	{
		if($this->newsCount > 10) return false;
		$this->newsBody .= sprintf(WEIXIN_NEWS_MSG_BODY, $title, $description, $picUrl, $url);
		$this->newsCount += 1;
		return true;
	}
	public function addArticleFinish()
	{
		$out = sprintf(WEIXIN_NEWS_MSG_BEGIN, '{FromUsername}', '{ToUsername}', time(), $this->newsCount) . $this->newsBody;
		$out .= WEIXIN_NEWS_MSG_END;
		$this->newsBody = '';
		$this->newsCount = 0;
		return $out;
	}
	//图片消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genImageMsg($mediaId)
	{
		return sprintf(WEIXIN_IMAGE_MSG, '{FromUsername}', '{ToUsername}', time(), $mediaId);
	}
	//语音消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genVoiceMsg($mediaId)
	{
		return sprintf(WEIXIN_VOICE_MSG, '{FromUsername}', '{ToUsername}', time(), $mediaId);
	}
	//视频消息
	//$mediaId通过上传多媒体文件，得到的id，必须
	//其余项非必须
	public function genVideoMsg($mediaId, $title = '', $description = '')
	{
		return sprintf(WEIXIN_VIDEO_MSG, '{FromUsername}', '{ToUsername}', time(), $mediaId, $title, $description);
	}
	//音乐消息
	//$mediaId缩略图的媒体id，通过上传多媒体文件，得到的id
	//$mediaId为必须项，其他项非必须
	//$HQMusicUrl高质量音乐链接，WIFI环境优先使用该链接播放音乐
	public function genMusicMsg($mediaId, $title = '', $description = '', $musicUrl = '', $HQMusicUrl = '')
	{
		return sprintf(WEIXIN_MUSIC_MSG, '{FromUsername}', '{ToUsername}', time(), $title, $description, $musicUrl, $HQMusicUrl, $mediaId);
	}
//结束

}