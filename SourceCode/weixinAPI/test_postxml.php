<?php
function curl_post($url, $post)
{
	$ch = curl_init();
	$header[] = "Content-type: text/xml";//定义content-type为xml
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch, CURLOPT_HEADER, 0);//启用时会将头文件的信息作为数据流输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 post提交的数据
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);//设置最大等待时间
	$data = curl_exec($ch); 
	if (curl_errno($ch))
	{
		return 'err';
	}		
	curl_close($ch);
	return $data;
}

$xml = '<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName> 
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>';
 
 $url = 'http://127.0.0.1/test_curl.php';
 header('Content-Type: text/xml');
 echo curl_post($url, $xml);