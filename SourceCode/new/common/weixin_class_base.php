<?php
//装载数据库类
require_once(COMMON_PATH.'database_class.php');

//自定义微信信息处理类
class WeiXinAPIBase
{
	//数据库相关
	public $conn;
	
	function __construct()
	{
		$this->conn = new DBClass();
	}
/*
*	验证签名是否合法开始
*	仅用于最初的网址接入
*/	
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
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
//结束
}