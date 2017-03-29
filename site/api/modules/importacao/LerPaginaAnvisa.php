<?php
	$hostname="www.google.com.br";
	$port=80;
	$timeout=30;
	$filepath="/";
	$txt = getXmlHttp($hostname,$port,$timeout,$filepath);
	$pos = strrpos($txt, "close\r\n\r\n")+strlen("close\r\n\r\n");
	$txt = substr($txt,$pos,strlen($txt));
	echo $txt;
?>