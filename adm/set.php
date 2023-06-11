<?php 
include('../fun.php');
$conn=my_sql($cfg);
$user=is_login();
if(!$user){no_login();}
$sql='select * from '.$cfg['e'].'sys_set where id=1 ';
$l=db1($sql);

if($user['power']=='adm'){
$sql='select * from '.$cfg['e'].'adm order by id desc ';
$users=db($sql);
}

?>
<style>
set_box{float:left;width:100%;}
set_box b{float:left;width:100%;line-height:20px;font-size:15px;padding-bottom:10px;border-bottom:1px solid #eee;}
set_box l{float:left;width:100%;min-height:32px;line-height:32px;margin-top:10px;padding-left:80px;}
set_box l text{position:absolute;left:0;}
set_box logo,set_box no_data{float:left;width:32px;border-radius:5px;height:32px;}
set_box l input{float:left;width:100%;height:32px;line-height:32px;}
set_box bt{float:left;padding-left:20px;padding-right:20px;margin-right:10px;background:#ccc;height:32px;line-height:32px;border-radius:5px;color:#fff;}
set_box help{float:left;width:100%;line-height:16px;margin-top:3px;font-size:10px;color:#999}
</style>

<set_box>
<b>基本设置</b>
<l><text>站点LOGO</text>
<logo style="background:url(<?php echo $l['logo']?>) no-repeat center;background-size:cover;"  onclick="win({title:'选择LOGO',url:'./img.php?ac=select_logo&max=1',width:800,id:'img'})"></logo>
</l>

<l><text>名称</text>
<input placeholder="文档站点名称" c="webname" value="<?php echo $l['webname']?>" need="1" onkeyup="sys_need_save()">
</l>

<l><text>没有数据</text>
<no_data style="background:url(<?php echo $l['no_data']?>) no-repeat center;background-size:cover;"  onclick="win({title:'文章为空显示',url:'./img.php?ac=select_no_data&max=1',width:800,id:'img'})"></logo>
</l>

<l><text>Debug</text>
<switch class="m <?php echo $l['debug']==1?'switch_true':''?> " onclick="switch_this($(this))" c="debug" val="<?php echo $l['debug']==1?'false':'1'?>"><x></x></switch>
<help>开启和关闭debug，左侧导航链接将会有不同的变化。</help>
</l>

<l><text>锁定编辑</text>
<switch class="m <?php echo $l['bind_edit']==1?'switch_true':''?> " onclick="switch_this($(this))" c="bind_edit" val="<?php echo $l['bind_edit']==1?'false':'1'?>"><x></x></switch>
<help>锁定编辑后，在一定时间内，其他用户将无法进行编辑。</help>
</l>

<l><text>锁定时间</text>
<input placeholder="锁定编辑的时间" type="number" c="bind_timeout" value="<?php echo $l['bind_timeout']?>" onkeyup="sys_need_save()">
<help>开启锁定编辑后，在该时间内，如果没有操作将解锁文档，默认600秒。</help>
</l>

<l><text>自动保存</text>
<input placeholder="自动保存文档的时间" type="number" c="save_timeout" value="<?php echo $l['save_timeout']?>" onkeyup="sys_need_save()">
<help>文档自动保存时间，默认180秒，设置为0，则不开启自动保存。</help>
</l>

<l><bt class=" m save_bt" onclick="save_set($(this))">保存设置</bt></l>

<?php if($user['power']=='adm' and $users){?>
<style>
user_item{float:left;width:100%;padding-left:10px;height:32px;line-height:32px;padding-right:30px;font-size:13px;margin-top:5px;border-bottom:1px solid #eee}
user_item name{float:left;width:30%;height:32px;line-height:32px;overflow:hidden;}
user_item power{float:left;width:10%;height:32px;line-height:32px;overflow:hidden;}
user_item date{float:left;width:50%;height:32px;line-height:32px;overflow:hidden;color:#ccc}
user_item icon{position:absolute;right:0;width:50px;text-align:center;font-size:20px !important;}
user_item icon:hover{color:crimson;}
user_item .icon-check-fill{color:green}
set_box .state_0{background:#eee}
set_box b bt{float:right;height:32px;line-height:32px;padding-left:20px;padding-right:20px;border-radius:5px;font-weight:normal;font-size:13px;}
</style>

<b style="margin-top:20px;">用户管理
<bt class="green_bg " onclick="win({title:'新用户',url:'./adm/new_user.php',width:'500px',id:'new_user'})">新用户</bt>
</b>
<?php 
foreach($users as $v){

echo '<user_item class="state_'.$v['state'].'">
<name>'.$v['username'].'</name>
<power>'.$v['power'].'</power>
<date>最后登录:'.date('Y-m-d H:i:s',$v['last_login']).'</date>
<icon class="icon '.($v['state']==1?'icon-close-fill':'icon-check-fill').'" onclick="user_stop($(this))" i="'.$v['id'].'"></icon>

';

echo '</user_item>';
}
?>
<?php }?>
</set_box>





<script>
function user_stop(o){
var id=o.attr('i');
post('user_state','',id,function(res){
right_reload();

});
}



function save_set(o){
var data=form_data($('set_box input'));
if(data.can>0){return;}
post('set_edit',data.data,'<?php echo $l['id']?>',function(res){
   msg('保存修改'); 
});
}



function switch_this(o){
var c=o.attr('c');
var val=o.attr('val');
var data={}
data[[c]]=val==1?1:0;
post('set_edit',data,'<?php echo $l['id']?>',function(res){
if(val==1){val='false';}else{val=1;}
if(val==1){
o.removeClass('switch_true');
}else{
o.addClass('switch_true');
}
o.attr('val',val);

});
}


function sys_need_save(ac){
    if(ac){$('set_box .save_bt').removeClass('green_bt');}else{
    $('set_box .save_bt').addClass('green_bt');
    }

}

function select_logo(o){
console.log(window.is_select_img);
var img=window.is_select_img;
var im='';
for(var i in img){
if(!im){im=img[i].img_240}
}

var data={logo:im}
post('set_edit',data,1,function(res){
    msg('修改成功');
    $('#img').remove();
    $('logo,set_box logo').css({'background':'url('+im+') no-repeat center','background-size':'cover'});
});
}

function select_no_data(){
var img=window.is_select_img;
var im='';
for(var i in img){
if(!im){im=img[i].img_750}
}
var data={no_data:im}
post('set_edit',data,1,function(res){
    msg('修改成功');
    $('#img').remove();
    $('set_box no_data').css({'background':'url('+im+') no-repeat center','background-size':'cover'});
});
}


</script>