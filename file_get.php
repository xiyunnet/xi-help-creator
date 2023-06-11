<?php 

$data = $_POST;
if(!$data){exit;}

$sign='羲云网络';
$password='jakeycis';

if($data['ac']=='update'){
$token=$data['token'];
$timestep=$data['timestep'];
$the_token=md5($sign.$timestep);
//echo $data['token'].' '.$the_token.' '.$timestep;
$pass=$data['password'];
if($pass!=md5($password)){die('密码错误');}
if($token!=$the_token){die('验证码错误');}
$file=$data['file'].'.'.$data['ext'];
$path='./';
if($data['sub']){
$path='./'.$data['sub'].'/';
if(!file_exists($path)){mkdir($path,0700);}
}
if(!$data['code']){die('数据错误');}
save_file($path,$file,$data['code']);
echo 'ok';
}else{exit;}


function save_file($path,$name,$str){//保存文件 save_file('目录'，‘文件名’,'字符');
if(substr($path,-1,1)!="/"){$path.='/';}
if(!file_exists($path)){mkdir($path,0700);}
$filename=$path.$name;
$handle=fopen($filename,"w");
if (fwrite($handle,$str)){return true;}else{return false;}
fclose($handle);
}
?>