<?php
if ($_GET['signature'] && $_GET['time_stamp'] && $_GET['nonce']) {
    
} else {
    return false;
}

$signature = $_GET["signature"];
$timestamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];	

$token = 'lotus';
$tmpArr = array($token, $timestamp, $nonce);
sort($tmpArr);
$tmpStr = implode( $tmpArr );
$tmpStr = sha1( $tmpStr );

if( $tmpStr == $signature ){
    return $nonce;
}else{
    return false;
}