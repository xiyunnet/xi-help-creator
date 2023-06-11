<?php 
//羲云科技版权所有，商用请联系我们
//联系电话：13758349211


include('./fun.php');
$ac=g('ac');if(!$ac){exit;}
$id=is_num('id');
$err['err']='ok';
check_sign();
$onn=my_sql($cfg);
$redis=redis();


switch($ac){
default:break;
case 'login':
$data=d('data');
if(!$data['username']){err($lan['no_username']);}
if(!$data['password']){err($lan['no_password']);}
$sql='select * from '.$cfg['e'].'adm where username=:username order by id desc';
$p['username']=$data['username'];
$l=db1($sql,$p);
if(!$l){err($lan['no_find_adm']);}
if($l['password']!=md5($data['password'])){err($lan['password_err']);}
if($l['state']!=1){err($lan['user_is_ban']);}
$time=time()+3600*24*30;
setcookie($cfg['path'].'_adm_id',$l['id'],$time);
$arr['session']=md5(time());
$arr['last_login']=time();
setcookie($cfg['path'].'_adm_session',$arr['session'],$time);
update($cfg['e'].'adm',$arr,' where id="'.$l['id'].'"');
r_set($cfg['path'].'_login','',600);
err('ok');
break;

case 'loginout':
$user=is_login();
if($user){
$arr['session']=md5(time());
update($cfg['e'].'adm',$arr,' where id="'.$user['id'].'"');
}
setcookie($cfg['path'].'_adm_id','',$time);
setcookie($cfg['path'].'_adm_session','',$time);
r_set($cfg['path'].'_login','',600);
err('ok');
break;

case 'creat_help':
$user=is_login();
if(!$user){err('no_login');}
$data=d('data');
$c=['title','s','tag','type','info','o'];
foreach($c as $v){
if($data[$v]){$arr[$v]=$data[$v];}
}
if(!$arr['title']){err('请输入标题');}
if(!$arr['type']){err('请选择分类');}
if($arr['type']=='arc'){
if(!$arr['tag']){
$arr['tag']=$arr['title'];
}
}
if(!$arr['s']){$arr['s']='';}

$arr['tag']=str_replace(['  ',' '],[' ','-'],$arr['tag']);

if(!$arr['lan']){$arr['lan']=get_lan();}

if(!$arr['o'] or !is_numeric($arr['o'])){$arr['o']=0;}
if($id){
$sql='select * from '.$cfg['e'].'help where id="'.$id.'" order by id desc limit 1';
$l=db1($sql);
if(!$l){err('抱歉，没有找到文章或者相关分组');}
}

//查询tag是否唯一
if($arr['type']=='arc'){
//print_r($arr);exit;    

$sql='select * from '.$cfg['e'].'help where s=:s and tag=:tag and lan="'.$arr['lan'].'" ';
if($l){$sql.=' and id!="'.$l['id'].'" ';}
$sql.=' order by id desc limit 1';
$p['s']=$arr['s'];
$p['tag']=$arr['tag'];
$ck=db1($sql,$p);
if($ck){err('抱歉，您的标签重复，请使用其他标签');}
}

$arr['last_update']=time();
if($l){

update($cfg['e'].'help',$arr,' where id="'.$l['id'].'"');

if($l['type']=='group'){//全部更新
if(!$l['s']){
    if($arr['s']){
   $arrs['s']=$arr['s'].'||'.$arr['title']; 
    }else{
$arrs['s']=$arr['title'];
}

update($cfg['e'].'help',$arrs,' where lan="'.$l['lan'].'" and s="'.$l['title'].'" ');

//文章
$sql='select id,s from '.$cfg['e'].'help where lan="'.$l['lan'].'" and s like "'.$l['title'].'||%" ';
$arc=db($sql);
if($arc){
foreach($arc as $t){
$x=explode('||',$t['s']);
$arrs['s']=$arr['title'].'||'.$x[1];
update($cfg['e'].'help',$arrs,' where id="'.$t['id'].'"');
}
}
}else{//存在s的
$tag=$l['s'].'||'.$l['title'];
if($arr['s']){
$arrs['s']=$arr['s'].'||'.$arr['title'];
}else{
$arrs['s']=$arr['title'];
}

update($cfg['e'].'help',$arrs,' where lan="'.$l['lan'].'" and s="'.$tag.'"');

}

}


}else{
$arr['date']=time();
$arr['user_id']=$user['id'];
$id=insert($cfg['e'].'help',$arr);
}

$err['id']=$id;
echo json_encode($err);
break;

case 'html_save':
$user=is_login();
if(!$user){err('no_login');}
$data=d('data');
if(!$id){err('请指定文章');}
$sql='select * from '.$cfg['e'].'help where id="'.$id.'" and type="arc" ';
$l=db1($sql);
if(!$l){err('没有找到相关帮助文档');}
if($set['bind_edit']==1 and $set['save_timeout']>0){//绑定
if($l['bind_user_id']!=$user['id']){
if($l['bind_date']>time()){err('抱歉，该文档已经绑定编辑，请稍后再试');}
}
$arr['bind_user_id']=$user['id'];
$arr['bind_date']=time()+$set['save_timeout'];
}
if(!$data){
$arr['data']='';
}else{
$arr['data']=base64(serialize($data),1);
}
$arr['last_update']=time();
$arr['is_publish']=0;



update($cfg['e'].'help',$arr,' where id="'.$l['id'].'"');
err('ok');
break;

case 'img_up'://图片上传
$user=is_login();
if(!$user){err('no_login');}


$arr['name']=p('name');
$arr['ext']=p('ext');
$data=$_POST['data'];
$err['item']=p('item');
$arr['c']=p('c');
$ext=['jpg','png','webp','jpeg'];
if(!in_array($arr['ext'],$ext)){err($arr['name'].'上传文件格式错误');}
$arr['date']=time();
$arr['state']=1;

if($user){$arr['user_id']=$user['id'];
if(!$arr['c']){$arr['c']='pro';}
}
if($adm){$arr['adm_id']=$adm['id'];
if(!$arr['c']){$arr['c']='my';}
}


$id=insert($cfg['e'].'img',$arr);

if(!$id){err('文件上传失败');}
$path='./'.$cfg['img_path'].'/';if(!file_exists($path)){mkdir($path,0700);}
$path='./'.$cfg['img_path'].'/'.ceil($id/1000).'/';
if(!file_exists($path)){mkdir($path,0700);}
$file_name=$id.'.'.$arr['ext'];
if(strstr($data,';base64,')){
$temp=explode(';base64,',$data);
$data=$temp[1];
}

save_file($path,$file_name,base64_decode($data));
resize_img($path,$file_name,$id,$arr['ext'],$cfg['img_size']);
unlink($path.$id.'.'.$arr['ext']);
$err['img']=$id.'|'.$arr['ext'];
foreach($cfg['img_size'] as $v){
$err['img_'.$v]=get_img($err['img'],'_'.$v);
}
$err['id']=$id;
echo json_encode($err);
break;



case 'help_state':
$user=is_login();
if(!$user){err('no_login');}
if(!$id){err('请指定文档');}
$data=d('data');
if($data['state']==1){$arr['state']=1;}else{$arr['state']=0;}
update($cfg['e'].'help',$arr,' where id="'.$id.'"');
echo json_encode($err);
break;


case 'publish':
$user=is_login();
if(!$user){err('no_login');}
if(!$id){err('请指定文档');}
$sql='select * from '.$cfg['e'].'help where id="'.$id.'"';
$l=db1($sql);if(!$l){err('没有找到文档');}
$arr['html']=$l['data'];
$arr['is_publish']=time();
update($cfg['e'].'help',$arr,' where id="'.$l['id'].'"');
err('ok');
break;


case 'set_edit':
$user=is_login();
if(!$user){err('no_login');}
if($user['power']!='adm'){err($lan['no_power']);}
$data=d('data');
update($cfg['e'].'sys_set',$data,' where id="1"');
err('ok');
break;

case 'user_state':
$user=is_login();
if(!$user){err('no_login');}
if($user['power']!='adm'){err($lan['no_power']);}
if(!$id){err('请指定用户');}
if($id==$user['id']){err('抱歉，不能对自己进行操作');}
$sql='select * from '.$cfg['e'].'adm where id="'.$id.'"';
$l=db1($sql);
if($l['state']==1){$arr['state']=0;}else{$arr['state']=1;}
update($cfg['e'].'adm',$arr,'where id="'.$l['id'].'"');
err('ok');
break;



case 'user_add'://添加用户
$user=is_login();
if(!$user){err('no_login');}
if($user['power']!='adm'){err($lan['no_power']);}
$data=d('data');
if(!$data['username']){err('请输入用户名');}
if(!$data['password']){err('请输入密码');}
if(!$data['power']){err('请选择用户权限');}
$arr['username']=$data['username'];
$arr['password']=$data['password'];
$arr['power']=$data['power'];
if($arr['power']!='adm'){$arr['power']='user';}
$sql='select * from '.$cfg['e'].'adm where username=:username ';
$p['username']=$arr['username'];
$l=db1($sql,$p);
if($l){err('抱歉，您的用户名已经存在');}
$arr['last_login']=time();
$arr['state']=1;
$arr['password']=md5($arr['password']);
insert($cfg['e'].'adm',$arr);
err('ok');
break;


case 'tag_edit':
$user=is_login();
if(!$user){err('no_login');}
$data=d('data');
if(!$data['title']){err('请输入标签名称');}
if(!$data['data']){err('请输入替换值');}
$arr['title']=$data['title'];
$arr['data']=$data['data'];

if($id){
update($cfg['e'].'data',$arr,' where id="'.$id.'" and c="tag"');
}else{
$arr['date']=time();
$arr['user_id']=$user['id'];
$arr['c']='tag';
insert($cfg['e'].'data',$arr);
}
err('ok');
break;


case 'tag_del':
$user=is_login();
if(!$user){err('no_login');}
if(!$id){err('请指定需要删除的标签');}
$sql='select * from '.$cfg['e'].'data where id="'.$id.'" and c="tag"';
$l=db1($sql);
if(!$l){err('没有找到标签');}
if($l['user_id']!=$user['id']){err('抱歉，您没有删除的权限');}
$sql='delete from '.$cfg['e'].'data where id="'.$l['id'].'"';
sql_query($sql);
err('ok');
break;


case 'helpful'://帮助
if(!$id){err('ok');}
$data=d('data');
if($data['val']=='yes'){
$sql='update '.$cfg['e'].'help set yes=yes+1 where id="'.$l['id'].'"';
}else{
$sql='update '.$cfg['e'].'help set no=no+1 where id="'.$l['id'].'"';
}
sql_query($sql);
err('ok');
break;











}








?>