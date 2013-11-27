<?php defined('BASEPATH') OR exit('No direct script access allowed');

//协议名称
$config['scheme'] = 'tcp';

//redis-server 主机地址
$config['host']   = '172.17.0.202';

//redis-server 端口号
$config['port']   = 6379;

//普通记录缓存时间
$config['cache_time'] = 300;

//首页目录缓存时间
$config['class_cache_time'] = 86400;
