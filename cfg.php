<?php 
header("content-Type: text/html; charset=utf-8");
header("Cache-Control:no_cache");
ob_start();
date_default_timezone_set("Asia/Shanghai");
//ini_set('error_reporting','E_ALL & ~E_NOTICE & ~E_WARNING');

include(__DIR__.'/db.php');//数据库
include(__DIR__.'/config.php');
include(__DIR__.'/lan.php');
$cfg['time']=date('ym');//程序更新，取固定值即可
$cfg['img_size']=['240','480','750','1080'];
$cfg['server_path']=__DIR__;

//支付回单接收地址
if($cfg['img_domain']){
foreach($cfg['img_domain'] as $v){
$cfg['img_server'][]='https://'.$v.'/'.($cfg['path']?$cfg['path'].'/':'').$cfg['img_path'].'/';//图片地址

}}

$cfg['lan']=['zh'=>'简体中文','en'=>'English'];
$cfg['dft_lan']='zh';

$cfg['nav']=[
'帮助中心'=>['url'=>'./','select'=>'','target'=>'_blank'],
'编辑器'=>['url'=>'./editor.php','select'=>'','target'=>''],
'首页'=>['url'=>'https://www.xicloud.top','select'=>'','target'=>'']
];

?>