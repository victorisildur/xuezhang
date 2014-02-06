<?php
require_once('weixin_class_base.php');
//装载模板文件
require_once('weixin_tpl_receive.php');

class WeiXinAPIReceive extends WeiXinAPIBase
{
	public $postObj;
	private $fromUsername = '';
	private $toUsername = '';
	public $form_MsgType;
	
/* 
* 构造函数,子类的构造函数实际上是覆盖(override)了父类的构造函数
*/
	function __construct()
	{
		parent::__construct();
		$this->parsePostStr();
	}
/*
记录用户回复到数据库中
*/
	private function logToDatabase()
	{
	
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
			  if(!$this->postObj) return false;
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
	
	private function getQuestionAnswer($question)
	{
		$question = $this->conn->sqlesc($question);
		$result = $this->conn->get_one("SELECT `answer` from `weixin_autoreply` WHERE question = $question");
		return $result['answer'];
	}
	
	public function answerText()
	{
		$ans = $this->getQuestionAnswer($this->postObj->Content);
		$ans = str_replace('{FromUsername}', $this->fromUsername, $ans);
		$ans = str_replace('{ToUsername}', $this->toUsername, $ans);
		return empty($ans) ? $this->genTextMsg($this->robotAnswer($this->postObj->Content)) : $ans;
	}
	
	private function robotAnswer($keyword)
	{

		$curlPost=array("chat"=>$keyword);
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,'http://www.xiaojo.com/bot/chata.php');//抓取指定网页
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		if(!empty($data)){
			return $data;
		}else{
			$ran=rand(1,4);
			switch($ran){
				case 1:
					return "小爷累了，还不快陪爷就寝~";
					break;
				case 2:
					return "哎..哎呦...这下舒服多了...";
					break;
				case 3:
					return "嗯哼~~嗯哼哼哼~~终于出来了...";
					break;
				case 4:
					return "来人，护驾！！！";
					break;
				default:
					return "感谢您关注【学长看看】"."\n"."微信号：xuezhangcc";
					break;
			}
		}
	}
	//纯文本消息
	public function genTextMsg($contentStr)
	{
		return sprintf(WEIXIN_TEXT_MSG, $this->fromUsername, $this->toUsername, time(), $contentStr);
	}
}