<?php

//与微信服务器通信代码未完待续！！！


//装载模板文件
require_once('weixin_tpl.php');
require_once('weixin_config.php');

//装载数据库类
require_once('database_class.php');

//自定义微信信息处理类
class WeiXinAPI
{
	public $postObj;
	private $fromUsername;
	private $toUsername;
	public $form_MsgType;
	
	//NEWS相关
	private $newsCount = 0;
	private $newsBody = '';
	
	//access_token相关
	private $access_token = '';
	private $access_token_expire_time = 0;
	
	//数据库相关
	private $conn;
	
	
	function __construct()()
	{
		this->$conn = new DBClass();
		//GetAccessToken();//要不要放在构造函数中呢？？？？？？？？？？？？
		this->parsePostStr();
	}
	
	
	//把post来的数据进行处理
	private function parsePostStr()
	{
		//获取微信发送数据
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		//返回回复数据
		if (!empty($postStr))
		{  
			//解析数据
			  $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			//发送消息方ID
			  $fromUsername = $postObj->FromUserName;
			//接收消息方ID
			  $toUsername = $postObj->ToUserName;
			//消息类型
			  $form_MsgType = $postObj->MsgType;
			  
			  return true;
		}
		else 
		{
			  return false;
		}
	}
/****************************
各种类型消息返回信息生成开始
*****************************/
	//纯文本消息
	public function genTextMsg($contentStr)
	{
		return sprintf(WEIXIN_TEXT_MSG, this->$fromUsername, this->$toUsername, time(), $contentStr);
	}
	//图文消息
	//$url点击图文消息跳转链接
	//$picUrl图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
	public function addArticle($title, $description, $picUrl, $url)
	{
		if(this->$newsCount > 10) return false;
		$newsBody += sprintf(WEIXIN_NEWS_MSG_BODY, $title, $description, $picUrl, $url);
		this->$newsCount += 1;
		return true;
	}
	public function addArticleFinish()
	{
		this->$newsBody = sprintf(WEIXIN_NEWS_MSG_BEGIN, this->$fromUsername, this->$toUsername, time(), this->$newsCount) + this->$newsBody;
		this->$newsBody += WEIXIN_NEWS_MSG_END;
		return this->$newsBody;
	}
	//图片消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genImageMsg($mediaId)
	{
		return sprintf(WEIXIN_IMAGE_MSG, this->$fromUsername, this->$toUsername, time(), $mediaId);
	}
	//语音消息
	//$mediaId通过上传多媒体文件，得到的id
	public function genVoiceMsg($mediaId)
	{
		return sprintf(WEIXIN_VOICE_MSG, this->$fromUsername, this->$toUsername, time(), $mediaId);
	}
	//视频消息
	//$mediaId通过上传多媒体文件，得到的id，必须
	//其余项非必须
	public function genVideoMsg($mediaId, $title = '', $description = '')
	{
		return sprintf(WEIXIN_VIDEO_MSG, this->$fromUsername, this->$toUsername, time(), $mediaId, $title, $description);
	}
	//音乐消息
	//$mediaId缩略图的媒体id，通过上传多媒体文件，得到的id
	//$mediaId为必须项，其他项非必须
	//$HQMusicUrl高质量音乐链接，WIFI环境优先使用该链接播放音乐
	public function genVoiceMsg($title = '', $description = '', $musicUrl = '', $HQMusicUrl = '', $mediaId)
	{
		return sprintf(WEIXIN_MUSIC_MSG, this->$fromUsername, this->$toUsername, time(), $title, $description, $musicUrl, $HQMusicUrl, $mediaId);
	}
/****************************
各种类型消息返回信息生成结束
*****************************/

/****************************
与微信服务器通信开始
*****************************/
//发送客服消息(暂时不考虑实现)
	private function SendServMsg()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.this->$access_token;
	}

//获取AccessToken
	private function GetAccessToken()
	{		
		$result = $conn->get_one('SELECT * FROM `weixin_access_token`');//从数据库中读取$access_token以及失效时间
		
		if(time()<$result['expire_time'] - 100)
		{
			this->$access_token = $result['access_token'];
			return true;
		}
		else
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WEIXIN_APPID.'&secret='.WEIXIN_APPSECRET;
			$data = curl_get($url);
			$data ? $jsonData = json_decode($data) : return false;//获取数据失败
			if(empty($jsonData->access_token)) return false;//返回结果错误
			this->$access_token = $jsonData->access_token;
			$access_token_expire_time = time() + $jsonData->expires_in;
			$conn->query("UPDATE `weixin_access_token` SET access_token = '".this->$access_token."', expire_time = $access_token_expire_time;");//将$access_token写入到数据库中
			return true;
		}
	}
//封装cURL库
	private function curl_get($url)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);// 从证书中检查SSL加密算法是否存在
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch, CURLOPT_HEADER, 0);//启用时会将头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);//设置最大等待时间
		$data = curl_exec($ch);
		if (curl_errno($curl))//0则成功，其他的就是不成功
		{
            return false;
        }		
		curl_close($ch);
		return $data;
	}
	private function curl_post($url, $post)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);// 从证书中检查SSL加密算法是否存在
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch, CURLOPT_HEADER, 0);//启用时会将头文件的信息作为数据流输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 post提交的数据
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);//设置最大等待时间
		$data = curl_exec($ch); 
		if (curl_errno($curl))
		{
            return false;
        }		
		curl_close($ch);
		return $data;
	}
/****************************
与微信服务器通信结束
*****************************/
	
/****************************
验证签名是否合法开始，仅用于最初的网址接入
*****************************/	
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if(this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = WEIXIN_TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
/****************************
验证签名是否合法结束
*****************************/	
}
