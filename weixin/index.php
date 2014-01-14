<?php
if (isset($_GET['signature']) && isset($_GET['time_stamp']) && isset($_GET['nonce'])) {
    
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