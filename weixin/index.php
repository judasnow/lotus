<?php
/**
 * Weixin 默认入口
 */
$userMessage = $GLOBALS['HTTP_RAW_POST_DATA'];
$xml = simplexml_load_string($userMessage, 'SimpleXMLElement', LIBXML_NOCDATA);

$time = time();
$ToUserName = $xml->FromUserName;
$FromUserName = $xml->ToUserName;

$text = "
<xml>
<ToUserName><![CDATA[$ToUserName]]></ToUserName>
<FromUserName><![CDATA[$FromUserName]]></FromUserName>
<CreateTime>$time</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[欢迎进入 maoejie 移动版]]></Content>
<ArticleCount>1</ArticleCount>
<Articles>
<item>
<Title><![CDATA[欢迎进入maoejie]]></Title>
<Description><![CDATA[点击图片进入我们的网站]]></Description>
<PicUrl><![CDATA[http://maoejiestatic.u.qiniudn.com/weixin.png]]></PicUrl>
<Url><![CDATA[http://maoejie.com]]></Url>
</item>
<item>
</Articles>
</xml>
";
echo $text;
