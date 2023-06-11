<?php 
include(__DIR__.'/cfg.php');
include(__DIR__.'/function.php');


function format_text($str){
global $cfg,$set,$redis,$conn;
$tags=get_tag();
if($tags){
$str=str_replace($tags['search'],$tags['replace'],$str);
}
return str_replace(['{{webname}}'],[$set['webname']],$str);
}

function get_tag(){
global $cfg,$set,$redis,$conn;
$tag=r_get($cfg['path'].'tag_list',1);
if($tag){return $tag;}
$sql='select * from '.$cfg['e'].'data where c="tag" order by id asc';
$l=db($sql);
if($l){
foreach($l as $v){
$tag['search'][]='{{'.$v['title'].'}}';
$tag['replace'][]=$v['data'];
}
r_set($cfg['path'].'tag_list',$tag,600);
return $tag;
}

}



function clean_tag($val){
return str_replace(['"',"'",' ','&','@','#'],'',$val);
}


function get_lan(){
global $cfg;
$lan=$_SERVER['HTTP_ACCEPT_LANGUAGE'];//die($lan);
$c=['zh-CN'=>'zh','en-US'=>'us'];
foreach($c as $k=>$v){
    if(!$this_lan){
if(stristr($lan,$k)){
$this_lan=$v;
}
}
}
if(!$this_lan){return $cfg['dft_lan'];}
return $this_lan;
}


function is_login($ac=0){//检查登录状态
global $conn,$cfg,$redis,$lan;
$user_id=g('adm_id');
$session=g('adm_session');
if(!$user_id or !$session){
$user_id=c($cfg['path'].'_adm_id');
$session=c($cfg['path'].'_adm_session');
}

if(!$user_id or !$session){return;}
if($redis and !$ac){
$user=r_get($cfg['path'].'_user_'.$user_id,1);
if($user){
if($user['session']==$session){return $user;}
}}

$sql='select * from '.$cfg['e'].'adm where id=:id ';
$p['id']=$user_id;
$l=db1($sql,$p);
if(!$l){return;}
if($l['state']!=1){err($lan['user_is_disabled']);}
$l['nickname']=base64($l['nickname']);
if($ac!=1){
r_set($cfg['path'].'user_'.$l['id'],$l,600);
}
return $l;
}


function no_data($str=''){
global $set;    
echo '<no_data style="float:left;width:100%;padding-left:10%;padding-right:10%;margin-top:20px;">
<img src="'.$set['no_data'].'" style="float:left;width:100%;">
<text style="float:left;width:100%;margin-top:20px;color:#666;font-size:13px;text-align:center;">'.$str.'</text>
</no_data>';

}

function no_login(){die('<script>show_login();</script>');}


//用户函数库
function is_num($text){
$str=g($text);
if(!$str){$str=0;}
if(!is_numeric($str)){$str=0;}
return $str;
}


function check_sign(){//验证登录
global $cfg,$redis,$app_id,$lan;
$sign=g('token');$app_id=is_num('app_id');
if(!$sign){$sign=g('web_sign');}
$timestep=g('timestep');
$check=md5($cfg['sign'].$timestep);
if($sign!=$check){err($lan['err_sign']);}
}



function get_set(){//获取设置
global $conn,$cfg,$redis,$lan;
if($redis){
$set=r_get($cfg['path'].'_set',1);
if($set){return $set;}
}
$sql='select * from '.$cfg['e'].'sys_set where id=1';
$l=db1($sql);
if(!$l){err($lan['sys_err']);}
r_set($cfg['path'].'_set',$l,3600);
return $l;
}
?>