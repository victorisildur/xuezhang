<?php
require_once('weixin_class_base.php');
class WeiXinAPISend extends WeiXinAPIBase
{
	//access_token相关
	private $access_token = '';
	private $access_token_expire_time = 0;
	
	function __construct()
	{
		$this->GetAccessToken();
	}
	
//发送客服消息(暂时不考虑实现)
	private function SendServMsg()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->access_token;
	}
	
//获取AccessToken
	private function GetAccessToken()
	{		
		$result = $this->conn->get_one('SELECT * FROM `weixin_access_token`');//从数据库中读取$access_token以及失效时间
		
		if(time()<$result['expire_time'] - 100)
		{
			$this->access_token = $result['access_token'];
			return true;
		}
		else
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WEIXIN_APPID.'&secret='.WEIXIN_APPSECRET;
			$data = curl_get($url);
			$data ? $jsonData = json_decode($data) : '';//获取数据失败
			if(empty($jsonData->access_token)) return false;//返回结果错误
			$this->access_token = $jsonData->access_token;
			$access_token_expire_time = time() + $jsonData->expires_in;
			$this->conn->query("UPDATE `weixin_access_token` SET access_token = '".$this->access_token."', expire_time = $access_token_expire_time;");//将$access_token写入到数据库中
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
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);//设置最大等待时间
		$data = curl_exec($ch);
		if (curl_errno($ch))//0则成功，其他的就是不成功
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
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));//设置请求头
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch, CURLOPT_HEADER, 0);//启用时会将头文件的信息作为数据流输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 post提交的数据
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);//设置最大等待时间
		$data = curl_exec($ch); 
		if (curl_errno($ch))
		{
            return false;
        }		
		curl_close($ch);
		return $data;
	}
}