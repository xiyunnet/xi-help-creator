<?php 
include('./fun.php');
$conn=my_sql();
$set=get_set();
$user=is_login();
if(!$user){no_login();}

$sql='select * from '.$cfg['e'].'img where ';
if($user){$sql.=' user_id="'.$user['id'].'" ';}else{
$sql.=' adm_id="'.$adm['id'].'" ';
}

$c=g('c');if($c){$sql.=' and c=:c ';$p['c']=$c;}
$s=g('s');if($s){$sql.=' and s=:s ';$p['s']=$s;}

$page=g('page');if(!$page){$page=1;}
$page_num=36;$start=($page-1)*$page_num;
$sql.=' order by id desc limit '.$start.','.$page_num;
$l=db($sql,$p);

unset($p);
$sql='select s from '.$cfg['e'].'img where ';
if($user){$sql.=' user_id="'.$user['id'].'" ';}else{
$sql.=' adm_id="'.$adm['id'].'" ';
}
$c=g('c');if($c){$sql.=' and c=:c ';$p['c']=$c;}
$sql.=' group by s order by s asc';
$cc=db($sql,$p);

$ac=g('ac');
$action=g('action');
if($action){$ac=$action;}
$max=g('max');if(!$max or !is_numeric($max)){$max=1;}
if(!$ac){$ac='select_img';}
?>
<style>
img_box{float:left;width:100%;height:100%;padding-top:50px;font-size:13px;padding-bottom:50px;}
img_box handle{position:absolute;width:100%;height:50px;z-index:1000;background:#fff;padding-left:0;}
img_box img_left{width:110px;position:absolute;left:0;top:0px;height:100%;padding-top:50px;}
.img_left_show{padding-left:120px;}
img_box handle a{float:left;height:34px;line-height:32px;color:#fff;padding-left:15px;padding-right:15px;margin-top:8px;margin-right:10px;}
img_box img_left a{float:left;width:100%;height:40px;line-height:40px;text-align:center;overflow:hidden;color:#000;padding-left:10px;padding-right:10px;}
img_box img_left a:hover,img_box img_left .select{background:rgba(0,0,0,0.2);}


img_item{float:left;width:150px;height:120px;padding:5px;}
img_item imgx{float:left;width:100%;height:100%;}
img_box img_list{float:left;width:100%;height:100%;overflow-y:auto;}
img_item img{width:100%;height:100%;position:absolute;left:0;top:0;}
img_item icon{position:absolute;top:12px;right:12px;z-index:1000;color:#06c58d;width:24px;height:24px;border:3px solid rgba(0,0,0,0.3);line-height:24px;background:rgba(0,0,0,0.1);border-radius:50%;text-align:center;font-size:17px;font-weight:bold;}
img_is_select{float:left;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:10;opacity:0}
img_box .is_select{border:1px solid #06c58d;background:#06c58d;color:#fff;}
img_box .select_show{opacity:1;}
img_box span{position:absolute;bottom:0;height:32px;left:0;width:100%;line-height:32px;background:rgba(0,0,0,0.5);color:#fff;overflow:hidden;text-align:center;color:#fff;}


</style>
<input type="file" id="up_img" accept="image/*" style="display:none;" name="up_img[]"  multiple="multiple">
<img_box class="<?php echo ($cc and count($cc)>1)?'img_left_show':''?>">
<handle>
<a class="green_bt" onclick="$('#up_img').click()">上传图片</a>

<a class="green_bt" onclick="<?php echo $ac?>()" id="confirm_click" style="display:none;">确定选择</a>
</handle>
<?php 
if($cc and count($cc)>1){
echo '<img_left>';
$url=url_get(['page','s','c']);
foreach($cc as $v){
echo '<a href="?'.$url.'&c='.$c.'&s='.($v['s']?$v['s']:'null').'&page=1">'.($v['s']?$v['s']:'默认').'</a>';
}
echo '</img_left>';
}
?>
<img_list>
<?php 
if($l){
foreach($l as $v){
$img=$v['id'].'|'.$v['ext'];
$im=get_img($img,'_240');
$im_750=get_img($img,'_750');
//echo $im;
$str='';
foreach($cfg['img_size'] as $t){
$str.=' img_'.$t.'="'.get_img($img,'_'.$t).'" ';
}

echo '<img_item id="item_'.$v['id'].'">
<icon class="m "  onclick="select_this_img($(this))" i="'.$v['id'].'" img="'.$img.'" '.$str.'></icon>
'.($v['s']?'<span>'.$v['s'].'</span>':'').'
<imgx style="background:url('.$im.') no-repeat center;background-size:contain !important;" onclick="img_preview($(this))" val="'.$im_750.'">
<img_is_select class="m"></img_is_select>


</imgx>

</img_item>';

}
}else{echo '<err>还没有上传图片</err>';}


?>

</img_list>

</img_box>




<script>
$(document).ready(function(){
$('#up_img').change(function(e){get_up_img(e);});

if(!window.is_select_img){window.is_select_img={}}
var max=<?php echo $max;?>;
if(max>1){
img_num_format();
}else{
window.is_select_img={};
}
});


function select_this_img(o){
var max=<?php echo $max?>;
var num=Object.keys(window.is_select_img).length;if(!num){num=0;}
if(num==max){msg('抱歉，最多选择'+max+'张图片');return;}
var img=o.attr('img');var i=o.attr('i');
var img_240=o.attr('img_240');var img_480=o.attr('img_480');var img_750=o.attr('img_750');var img_1080=o.attr('img_1080');
if(window.is_select_img['item_'+i]){
delete window.is_select_img['item_'+i];
o.removeClass('is_select');
o.parent().find('img_is_select').removeClass('select_show');
o.html('')
}else{
window.is_select_img['item_'+i]={img:img,img_240:img_240,img_480:img_480,img_750:img_750,img_1080:img_1080};
window.img_item='item_'+i;
o.addClass('is_select');
o.parent().find('img_is_select').addClass('select_show');
}

img_num_format();
}

function img_num_format(){
var num=Object.keys(window.is_select_img).length;if(!num){
$("#confirm_click").hide();
return;}
var max=<?php echo $max?>;
var n=0;
var img=window.is_select_img;
for(var i in img){
n++;
$('img_box #'+i+' icon').html(n);
$('img_box #'+i+' icon').addClass('is_select');
}
if(max==1){
eval('<?php echo $ac?>()');

}else{
$("#confirm_click").show();
}
}


function get_up_img(e){//上传图片
window.img_data={};window.read_up_img=0;
var max=10;
var num=e.target.files.length;
if(num>max){msg('抱歉，您最多一次上传'+max+'张图片');return;}

if(num>0){
window.up_img_num=num;
for(var i=0;i<=num-1;i++){
var file = e.target.files[i];//图片文件
console.log(e.target.files[i]);
read_file(file,i);
}
}else{msg('请选择图片');}//格式不正确

msg('图片上传中，请稍后...');
$('#up_img').val('');
window.read_time=1;
setTimeout("img_upload();",500);
}


function read_file(file,i){
window.read_up_img++;
var max_size=1024*1024*5;//20K
var big_size=1024*1024*5;
var type=file.type;
var size=file.size;
var name=file.name;
var ext='';
var can=1;
var text='上传中，请稍后';
if (type=='image/jpeg' || type=='image/png' || type=='image/webp'){
switch(type){
case 'image/jpeg':ext='jpg';break;
case 'image/png':ext='png';break;
case 'image/webp':ext='webp';break;
}
if(size>max_size){msg(name+'图片尺寸太大了');can=0;text='图片尺寸太大';}
var freader = new FileReader();
freader.readAsDataURL(file);
//读取图片数据
freader.onload=function(e){//读取完成
if(size>big_size){msg(name+'图片文件超过上传尺寸');can=0;text='图片尺寸太大';}
window.img_data['item_'+i]={name:name,type:type,size:size,ext:ext,data:this.result,can:can,ac:'img_up',item:'img_item_'+i}
h='<img_item  ><imgx><img_is_select class="m"></img_is_select>';
h+='<img class="img" src="'+this.result+'"  id="img_item_'+i+'"><span class="can_up_'+can+'">'+text+'</span>';
h+='<icon class="m " style="display:none;"  onclick="select_this_img($(this))" ></icon>';
h+='</imgx></img_item>';

$('img_box img_list').prepend(h);
}

}else{msg(name+'文件格式不正确，无法上传');}

}



function img_upload(){//获取图片并上传
if(window.read_up_img==window.up_img_num && window.img_data){
console.log('图片完全读取完成',window.img_data);
console.log('开始上传图片');
$.each(window.img_data,function(i,n){
console.log('正在上传'+n.name);
n.web_sign='<?php $time=time();echo md5($cfg['sign'].$time);?>';
n.timestep='<?php echo $time?>';

$.ajax({
type:'POST',
url:'./server.php',
timeout:120000,
data:n,
contentType:'application/x-www-form-urlencoded',
success:function(res){
var d=json(res,1);
if(d.err=='ok'){//alert(n.item)
var obj=$('#'+n.item);
obj.parent().find('span').addClass('is_up');
obj.parent().find('span').html('上传成功');
obj.parent().attr('id','item_'+d.id);
obj.attr('src',d.img_240);
var icon=obj.parent().find('icon');
icon.attr('img',d.img);
icon.attr('i',d.id);

icon.attr('img_240',d.img_240);
icon.attr('img_750',d.img_750);
icon.attr('img_480',d.img_480);
icon.attr('img_1080',d.img_1080);

icon.show();

}else{
msg(d.err);
}

}

});

})

}else{
window.read_time++;
if(window.read_time>10){return;}
setTimeout("img_upload();",500);
}
}

</script>