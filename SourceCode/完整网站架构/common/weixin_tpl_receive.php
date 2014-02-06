<?php

define("WEIXIN_TEXT_MSG", "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0</FuncFlag>
            </xml>"); 
			
define("WEIXIN_IMAGE_MSG", "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[image]]></MsgType>
			<Image>
			<MediaId><![CDATA[%s]]></MediaId>
			</Image>
			</xml>"); 
			
define("WEIXIN_VOICE_MSG", "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[voice]]></MsgType>
			<Voice>
			<MediaId><![CDATA[%s]]></MediaId>
			</Voice>
			</xml>"); 	

define("WEIXIN_VIDEO_MSG", "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[video]]></MsgType>
			<Video>
			<MediaId><![CDATA[%s]]></MediaId>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			</Video>
			</xml>"); 
			
define("WEIXIN_MUSIC_MSG", "<xml>
             <ToUserName><![CDATA[%s]]></ToUserName>
             <FromUserName><![CDATA[%s]]></FromUserName>
             <CreateTime>%s</CreateTime>
             <MsgType><![CDATA[music]]></MsgType>
             <Music>
             <Title><![CDATA[%s]]></Title>
             <Description><![CDATA[%s]]></Description>
             <MusicUrl><![CDATA[%s]]></MusicUrl>
             <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			 <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
             </Music>
             <FuncFlag>0</FuncFlag>
             </xml>");		
			 
define("WEIXIN_NEWS_MSG_BEGIN", "<xml>
           <ToUserName><![CDATA[%s]]></ToUserName>
           <FromUserName><![CDATA[%s]]></FromUserName>
           <CreateTime>%s</CreateTime>
           <MsgType><![CDATA[news]]></MsgType>
           <ArticleCount>%s</ArticleCount>
           <Articles>");
define("WEIXIN_NEWS_MSG_BODY", "<item>
			<Title><![CDATA[%s]]></Title> 
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>");
define("WEIXIN_NEWS_MSG_END", "
           </Articles>
           <FuncFlag>1</FuncFlag>
           </xml> ");


?>