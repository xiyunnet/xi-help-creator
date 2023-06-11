<?php 
include('./fun.php');
$conn=my_sql($cfg);
$set=get_set();
$user=is_login();

$id=is_num('id');
if($id){
$sql='select * from '.$cfg['e'].'help where id=:id ';
$p['id']=$id;
if(!$user){$sql.=' and state=1 ';}
$sql.=' order by id desc limit 1';
$l=db1($sql,$p);
}else{
$s=g('s');$tag=g('tag');
if($s and $tag){
$sql='select * from '.$cfg['e'].'help where s=:s and tag=:tag ';
$p['s']=$s;$p['tag']=$tag;
if(!$user){$sql.=' and state=1 ';}
$sql.=' order by id desc limit 1';
$l=db1($sql,$p);
}
}

if($l){$lan=$l['lan'];}else{
$lan=get_lan();
}
//分组
$sql='select id,title,tag,s,type,lan from '.$cfg['e'].'help where lan="'.$lan.'" and type="arc"  order by o desc,id asc';
$left=db($sql);
if($left){
foreach($left as $v){
if($v['s']){
$x=explode('||',$v['s']);
if(!$x[1]){$x[1]=$v['id'];}
$left_menu[$x[0]][$x[1]][]=$v;
}else{
$left_menu[]=$v;
}
}
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<meta name="Cache-control" content="no-tranform">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="Author" content="web_author">
<meta name="apple-mobile-web-app-title" content="">
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">
<meta name="browsermode" content="application">
<meta name="x5-page-mode" content="app">
<meta name="HandheldFriendly" content="true">
<meta name="msapplication-tap-highlight" content="no">
<title><?php echo $set['webname'];?></title>
<script src="./jquery.js"></script>
<script src="./qrcode.js"></script>
<script src="./copy.js"></script>
<script src="./ckeditor.js"></script>
<script src="./fun.js?t=<?php echo $cfg['time']?>"></script>
<link type="text/css" rel="stylesheet" href="./style.css?<?php echo $cfg['time'].'23'?>">
</head>
<style>
main{float:left;width:100%;height:100vh;padding-left:300px;}
main_left{position:fixed;width:300px;height:100%;background:#f6f6f6;left:0;top:0;z-index:100;padding-top:64px;}
.nav_top{position:absolute;width:100%;height:64px;line-height:64px;top:0;left:0;padding-left:60px;color:#000;font-size:16px;overflow:hidden;font-weight:bold;padding-right:20px;}
.nav_top logo{position:absolute;width:32px;height:32px;border-radius:5px;left:20px;top:16px;}



main_left_box{float:left;width:100%;height:100%;overflow-y:auto;overflow-x:hidden;padding-right:15px;}
main_left_box span{position:absolute;width:2px;height:32px;top:2px;left:2px;background:#2c8cf4;display:none;}
main_left_box .select {background:#fff;color:#000;}
main_left .no_border{border-bottom:none}
left_sub{float:left;width:100%;height:36px;overflow:hidden;}
left_sub handle text{font-size:14px !important;}
.left_sub{padding-left:40px;}
main_left .view:hover{background:rgba(0,0,0,.05);}
main_left_box .select span{display:block;}
left_sub handle{padding-left:40px !important;}
left_sub .view{padding-left:40px !important}
main_left_box .view{float:left;width:100%;height:36px;line-height:36px;padding-left:20px;font-size:14px;color:#666}
main_left_box .select{color:#000;}
main_left_box .view{float:left;width:100%;height:36px;line-height:36px;overflow:hidden;}

left_group{float:left;width:100%;padding-bottom:5px;border-bottom:1px solid #eee;height:40px;overflow:hidden;line-height:40px}
left_group handle{float:left;width:100%;font-size:16px;font-weight:bold;padding-left:20px;height:36px;line-height:36px;padding-right:20px;}
left_group handle text{float:left;width:100%;height:36px;overflow:hidden;}
left_group handle icon{position:absolute;right:0;color:#999;font-weight:normal;}
 
main_left .is_group_show{height:auto}
main_left a{color:#999}
main_left a:hover{color:#000;}
main_left .is_sub_show{height:auto}
main_left .rotate{transform: rotate(90deg)}
main_left edit{float:right;display:none;}
main_left edit .del{color:crimson;}
main_left .can_edit edit{display:block;}


main_box{float:left;width:100%;height:100%;padding-top:64px;padding-left:50px;}
main_box nav{position:absolute;width:100%;left:0;height:64px;top:0;padding-left:50px;padding-right:20px;padding-top:16px;}
search_box{float:right;height:36px;}
search_box input{float:left;width:200px;height:36px;line-height:36px;background:#f6f6f6;border-radius:5px;box-sizing:border-box;padding-left:10px;padding-right:40px;margin-right:15px;font-size:13px;}
search_box icon{position:absolute;width:30px;line-height:36px;right:10px;top:0;}

nav a{float:right;height:36px;line-height:36px;color:#666;padding-left:20px;padding-right:20px;background:#fff;margin-right:20px;font-size:14px;}
nav a span{position:absolute;width:100%;height:2px;bottom:0;left:0;background:#2c8cf4;display:none;}
nav .select span{display:block;}
nav .select{color:#000;}
nav day_nignt{float:right;margin-right:10px;width:50px;height:28px;border:1px solid #ccc;padding-left:4px;background:#f6f6f6;padding-right:4px;border-radius:20px;margin-left:20px;margin-top:4px}
nav line{float:right;width:1px;height:22px;background:#ddd;top:8px;}
nav day_nignt icon{float:left;width:24px;height:24px;line-height:24px;font-size:14px !important;border-radius:50%;background:#fff;text-align:center;}
main_in_box{float:left;width:100%;height:100%;padding-right:260px;overflow-x:hidden;overflow-y:auto;}


tool{position:fixed;width:70px;min-height:50px;padding-top:5px;background:rgba(0,0,0,.1);border-radius:10px;top:80px;right:50px;z-index:5000;}
tool a,tool bt{float:left;width:50%;color:#000;height:35px;line-height:35px;text-align:center;font-size:24px !important;}
tool a:hover,tool .select,tool bt:hover{color:crimson;}
table_select_box{position:absolute;width:150px;height:165px;background:#fff;display:none;border-radius:5px;padding:10px;top:30px;right:0}
table_select_box item{float:left;width:10%;height:13px;padding:1px;}
table_select_box in{float:left;width:100%;height:100%;border:1px solid #ccc;}
table_select_box .select in{background:#bcdefb;border:1px solid #0e7fe1;}
table_select_box text{float:left;width:100%;font-size:10px;text-align:center;color:#000;margin-top:-5px;}


table,table tr th, table tr td { border:1px solid #eee;line-height:24px;font-size:13px;}
html_box table{float:left;width:100%;text-align: center; border-collapse: collapse;margin-top:10px;}
html_box th{height:36px;line-height:36px;background:#bcdefb;}
html_box table tr:nth-child(odd){background:#eee}
html_box .img_box{float:left;width:100%;display:flex !important;flex-direction:row;margin-top:10px;}
html_box .img_box img_item{float:left;padding:5px;}
html_box .img_box in{float:left;background:#f6f6f6;border-radius:5px;padding:10px;display:flex;flex-direction:column;}
html_box .img_box img{float:left;max-width:100%;}
html_box .img_box text{float:left;width:100%;text-align:center;font-size:12px;color:#999;margin-top:5px;line-height:16px;}
html_box li b{margin-right:5px;}
html_box edit_item{float:left;width:100%;}
html_box editor{float:left;width:100%;}

.ck-editor{float:left;width:100% !important;}
tool text{float:left;width:100%;font-size:10px;text-align:center;color:#999;}
index_box{width:250px;right:0px;top:80px;z-index:100;position:fixed;padding-right:30px;border-left:1px solid #ddd;z-index:100;}
index_box item{float:left;width:100%;height:32px;line-height:32px;padding-left:20px;font-size:13px;}
index_box .select{font-weight:bold;}
index_box a{float:left;width:100%;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}
index_box line{position:absolute;width:1px;height:20px;left:-0.5px;background:#2c8cf4;top:6px;}
index_box .h2{font-size:14px;overflow:hidden;}
index_box .h3{padding-left:30px;font-size:13px;height:24px;line-height:24px;}
index_box .h3 a{height:24px;line-height:24px;}

html_box{float:left;width:100%;min-height:100%;padding-right:30px;font-size:14px;padding-bottom:50px}
html_box info{float:left;width:100%;background:#f6f6f6;padding:10px;font-size:13px;color:#666;border-radius:5px;line-height:18px;}
html_box h2{float:left;width:100%;line-height:30px;margin:0;margin-top:10px;}
html_box h2 a{color:#000;}
html_box h3{float:left;width:100%;line-height:20px;margin:0;margin-top:10px;}
html_box h3 a{color:#000;}
html_box li{float:left;width:100%;line-height:20px;margin-top:10px}
arc_info{float:left;width:100%;line-height:20px;font-size:12px;color:#999;margin-top:10px;}
arc_info a{color:#00c8dc;}
html_box pre{margin:0;margin-top:10px;white-space: pre-wrap;word-wrap: break-word;}
edit_tool{position:absolute;min-width:50px;height:20px;line-height:20px;right:0;top:0px;display:none;background:#f6f6f6;border-radius:2px;}
edit_tool icon{float:right;height:20px;line-height:20px;margin-left:5px;}
edit_tool icon:hover{color:crimson;}
edit_item:hover edit_tool{top:-10px;display:block;}
edit_item:hover{background:#f6f6f6}
html_box warn{float:left;width:100%;margin-top:5px;background:rgba(244,0,0,0.1);padding:10px;border-radius:5px;font-size:12px;color:#666}
html_box warn a{float:right;padding-left:10px;padding-right:10px;height:24px;margin-left:10px;line-height:24px;background:#2c8cf4;color:#fff;border-radius:20px;}


loginout{float:right;height:30px;line-height:30px;margin-left:20px;margin-top:5px;background:#2c8cf4;color:#fff;padding-left:20px;padding-right:20px;border-radius:20px;}
loginout:hover{background:#ccc;color:#000;}
</style>
<body>
<main>
<main_left>

<a class="nav_top" href="./">
<logo style="background:url(<?php echo $set['logo']?>) no-repeat center,#ccc;background-size:cover;"></logo>
<text><?php echo $set['webname']?></text>
</a>
<main_left_box>
<?php $left_show=0;
if($left_menu){
foreach($left_menu as $k=>$v){
if($v['type']=='arc'){
echo '<a class="view m '.($l['id']==$v['id']?'select':'').'" href="?id='.$v['id'].'"><span></span>
<text>'.format_text($v['title']).'</text>
<edit>
<icon class="icon icon-close-fill del" onclick="left_index_del($(this))" i="'.$v['id'].'"></icon>
<icon class="icon icon-edit" onclick="index_edit($(this))" i="'.$v['id'].'"></icon>
</edit>
</a>';
}else{//组
echo '<left_group >
<handle onclick="left_show($(this))" c="group">
<text>'.$k.'</text>
<icon class="icon icon-arrow-right-s-line m"></icon>
<edit>
<icon class="icon icon-edit" onclick="index_edit($(this))" c="group" val="'.$k.'"></icon>
</edit>
</handle>
';


foreach($v as $m=>$n){
if(is_numeric($m) ){//文章
foreach($n as $z){
if($z['type']=='arc'){
if($z['id']==$l['id']){$left_show='group';}
echo '<a class="view m '.($l['id']==$z['id']?'select':'').'" href="?id='.$z['id'].'"><span></span>
<text >'.format_text($z['title']).'</text>
<edit>
<icon class="icon icon-close-fill del" onclick="left_index_del($(this))" i="'.$z['id'].'"></icon>
<icon class="icon icon-edit" onclick="index_edit($(this))" i="'.$z['id'].'"></icon>
</edit>
</a>';
}
}


}else{//子目录
echo '<left_sub >
<handle onclick="left_show($(this))" c="sub">
<text>'.$m.'</text>
<icon class="icon icon-arrow-right-s-line m"></icon>
<edit>
<icon class="icon icon-edit" onclick="index_edit($(this))" c="group" val="'.$m.'" sub="'.$k.'"></icon>
</edit>
</handle>';

foreach($n as $z){
if($z['type']=='arc'){
if($z['id']==$l['id']){$left_show='sub';}
echo '<a class="view m '.($l['id']==$z['id']?'select':'').'" href="?id='.$z['id'].'"><span></span>
<text >'.format_text($z['title']).'</text>
<edit>
<icon class="icon icon-close-fill del" onclick="left_index_del($(this))" i="'.$z['id'].'"></icon>
<icon class="icon icon-edit" onclick="index_edit($(this))" i="'.$z['id'].'"></icon>
</edit>
</a>';
    
}
}
echo '</left_sub>';
}
}
echo '</left_group>';

}
}
}

?>
</main_left_box>
</main_left>

<main_box>
<nav>
<loginout class="m" onclick="loginout($(this))">注销</loginout>
<line></line>
<?php 
$cfg['nav']['编辑器']['select']='select';
foreach($cfg['nav'] as $k=>$v){
echo '<a class="'.$v['select'].'" href="'.$v['url'].'" '.($v['target']?'target="'.$v['target'].'"':'').'>'.$k.'<span></span></a>';
}
?>

<search_box>
<input placeholder="请输入关键词"><icon class="icon icon-fenxiang"></icon>
</search_box>

</nav>

<main_in_box>
<html_box>
<h1><?php echo format_text($l['title']);?></h1>
<?php 

if($l['state']==0){
echo '<warn class="warn_state">您的文档还没有上线，点击此上线。<a onclick="state($(this))" i="'.$l['id'].'">上线</a></warn>';
}
if(!$l['is_publish'] and $l['data']){
echo '<warn class="warn_publish">您的文档最近编辑时间为'.date('Y年m月d日 H:i:s',$l['last_update']).' 点击此进行发布。
<a href="./?id='.$l['id'].'&preview=preview" target="_blank">预览</a><a onclick="publish($(this))" i="'.$l['id'].'">发布</a></warn>';
}

if($l['info']){echo '<info>'.format_text($l['info']).'</info>';}
?>

<arc_info>
最近更新:<?php echo date('Y-m-d H:i:s',$l['last_update'])?> 标签:<?php echo $l['tag'] ?> <a onclick="index_edit($(this))" i="<?php echo $l['id']?>">编辑</a>
</arc_info>

<editor_box>
<?php 
if($l['data']){
$data=unserialize(base64($l['data']));
if($data){
foreach($data as $k=>$v){
echo '<edit_item id="'.$k.'" c="'.$v['type'].'" class="'.($v['type']=='img'?'img_box':'').'">';
echo '<edit_tool>
<icon class="icon icon-dashboard-line" onclick="css_edit($(this))" i="'.$k.'"></icon>
<icon class="icon icon-jiantouxiangshang" onclick="up($(this))" i="'.$k.'"></icon>
<icon class="icon icon-jiantouxiangxia" onclick="down($(this))"  i="'.$k.'"></icon>
<icon class="icon icon-close-fill" title="删除" onclick="edit_item_del($(this))" i="'.$k.'"></icon>
</edit_tool>';
switch($v['type']){
default:break;
case 'text':
echo '<pre contenteditable="true" onkeyUp="need_save();" style="'.$v['style'].'">'.$v['val'].'</pre>';
break;
case 'h2':
echo '<h2 contenteditable="true" onkeyUp="heard_format();need_save();" style="'.$v['style'].'">'.$v['val'].'</h2>';
break;
case 'h3':
echo '<h3 contenteditable="true" onkeyUp="heard_format();need_save();" style="'.$v['style'].'">'.$v['val'].'</h3>';
break;  
case 'li':
echo '<li contenteditable="true" onkeyUp="need_save();" style="'.$v['style'].'">'.$v['val'].'</li>';
break;
case 'BLi':
echo '<li><b contenteditable="true" onkeyUp="need_save();">'.$v['b'].'</b><text contenteditable="true" onkeyUp="need_save();">'.$v['val'].'</text></li>';
break;
case 'table':
echo '<table>'.$v['val'].'</table>';
break;
case 'img':
if($v['val']){
foreach($v['val'] as $t){
echo '<img_item img="'.$t['img'].'">';
$img=get_img($t['img'],'_1080');
echo '<in><img src="'.$img.'">';
echo '<text contenteditable="true" onkeyUp="need_save();">'.($t['text']?$t['text']:'图片描述').'</text></in>';
echo '</in>';
echo '</img_item>';
}

}

break;
case 'ck_editor':
echo '<editor id="'.$k.'">'.$v['val'].'</editor>';
break;

}

echo '</edit_item>';
}}}
?>
</editor_box>
</html_box>
</main_in_box>
</main_box>


<index_box><line></line></index_box>

</main>
<right_box class="m">
<handle><text></text><icon class="icon icon-close" onclick="right_close($(this))"></icon></handle>
<right_in_box></right_in_box>
</right_box>
<bg onclick="right_close($(this));win_close($(this))" ></bg>

<?php 
if($user){

?>

<tool>
<text>导航</text>
<a class="icon icon-git-merge-line" onclick="index_edit($(this))" i="0" title="编辑导航"></a>
<a class="icon icon-list-check-2 left_menu_edit" onclick="left_menu_edit_show($(this))" title="展开全部导航"></a>

<text>编辑</text>
<a class="icon icon-h-21" onclick="text_add($(this))" c="h2" tag="h2" text="导航" ></a>
<a class="icon icon-h-31" onclick="text_add($(this))" c="h3" tag="h3" text="导航"></a>

<a class="icon icon-text" onclick="text_add($(this))" c="text" tag="pre" text="文本" title="文本"></a>
<a class="icon icon-edit" title="编辑器" onclick="ck_edit($(this));" title="富文本"></a>

<a class="icon icon-list-unordered" onclick="text_add($(this))" c="li" tag="li" text="列表"></a>
<a class="icon icon-list-check" onclick="text_add($(this))" c="BLi" tag="BLi" text="列表"></a>

<bt class="icon icon-fuwenbenbianjiqi_biaoge" onclick="$('tool  table_select_box').show()"  title="表单">
<table_select_box class="s">
<?php 
for($i=1;$i<=10;$i++){
for($j=1;$j<=10;$j++){
echo '<item  row="'.$i.'" column="'.$j.'" onmouseover="select_this_table($(this))" onclick="creat_this_table($(this))"><in></in></item>';
}
}
?>
<text></text>
</table_select_box>
</bt>

<a class="icon icon-fuwenbenbianjiqi_tupian" title="图片" onclick="right({title:'插入图片',url:'./img.php?max=3&ac=insert_img',width:'75vw'})"></a>
<text>操作</text>
<bt class="icon icon-tag" title="标签" onclick="right({title:'标签',url:'./adm/tag.php',width:'500px'})"></bt>
<bt class="icon icon-setting" onclick="right({title:'设置',url:'./adm/set.php',width:'600px'})"></bt>

<bt class="icon state icon-lightbulb-flash-line <?php echo $l['state']?'green':'select' ?>" title="状态" onclick="state($(this))" val="<?php echo $l['state']?'stop':'1'?>"></bt>

<bt class="icon icon-check-double-line publish <?php echo $l['is_publish']?'green':'select' ?>" onclick="publish($(this))" title="发布"></bt>

<bt class="icon icon-save save" title="保存" onclick="save($(this))"></bt>
<a class="icon icon-preview_fill" title="预览" href="./index.php?id=<?php echo $l['id']?>&preview=1" target="_blank"></a>
</tool>


<?php }else{?>
<script>
win({bg:1,title:'登录',url:'./login.php',id:'login',width:500});

</script>

<?php }?>
<script src="https://ace.c9.io/build/src/ace.js"></script>
<script>
$(document).ready(function(){
$('main_left left_group:last').addClass('no_border');
heard_format();
format_ck_edit();
});


function edit_item_del(o){
var id=o.attr('i');
if(!confirm("删除元素可能会造成无法恢复的情况，是否继续?")){return;}
$('editor_box #'+id).remove();
heard_format();
need_save();
}




function up(o){//向上
var id=o.attr('i');
var list=get_item_list();
var last=0;
for(var i in list){

if(i==id && last){
console.log('up',id,last);
item_replace(id,last);
}
last=i;
}
}

function down(o){
var id=o.attr('i');
var list=get_item_list();
var last=0;
for(var i in list){
if(last==id && i){
console.log('down',id,i);
item_replace(id,i);
}
last=i;
}

}



function item_replace(id,n){
var obj=$('editor_box #'+id);
var html=obj.html();
var c=obj.attr('class');if(!c){c='';}
var type=obj.attr('c');

var to=$('editor_box #'+n);
obj.html(to.html());
var to_c=to.attr('class');if(!to_c){to_c='';}
var to_type=to.attr('c');
obj.attr('class',to_c);
obj.attr('c',to_type);

to.html(html);
to.attr('class',c);
to.attr('c',type);
var temp_id=id;
obj.attr('id',n);
to.attr('id',id);
heard_format();
need_save();

}



function get_item_list(){
var data={};
$('editor_box edit_item').each(function(){
var id=$(this).attr('id');
var type=$(this).attr('c');
var c=$(this).attr('class');if(!c){c='';}
data[[id]]={type:type,c:c};
});
console.log(data);
return data;
}


function state(o){
var o=$('tool .state');
var val=o.attr('val');
var data={state:val}
post('help_state',data,'<?php echo $l['id']?>',function(res){
if(o.hasClass('select')){o.removeClass('select');o.addClass('green');o.attr('val','stop');
msg('上线成功');$('.warn_state').fadeOut(200);
}else{o.addClass('select');
o.removeClass('green');o.attr('val',1);msg('停用成功');
}
});
}

function publish(o){
var o=$('tool .publish');
if(!confirm("是否发布文档?")){return;}
post('publish','','<?php echo $l['id']?>',function(res){
if(o.hasClass('select')){o.removeClass('select');o.addClass('green');
msg('发布成功');$('.warn_publish').fadeOut(200);
}else{o.addClass('select');
}
});
}


function text_add(o){
var c=o.attr('c');var tag=o.attr('tag');var tt=o.attr('text');
var t=(new Date()).getTime();
var text='<edit_item id="item_'+t+'" c="'+c+'">';

text+= '<edit_tool>';
text+='<icon class="icon icon-dashboard-line" onclick="css_edit($(this))" i="item_'+t+'"></icon>';
text+='<icon class="icon icon-jiantouxiangshang" onclick="up($(this))" i="item_'+t+'"></icon>';
text+='<icon class="icon icon-jiantouxiangxia" onclick="down($(this))"  i="item_'+t+'"></icon>';
text+='<icon class="icon icon-close-fill" title="删除" onclick="edit_item_del($(this))" i="item_'+t+'"></icon>';
text+='</edit_tool>';



switch(c){
default:text+='<'+tag+' contenteditable="true" onkeyUp="need_save();">'+tt+'</'+tag+'>';break;
case 'h2':
text+='<'+tag+' contenteditable="true" onkeyUp="heard_format();need_save();">'+tt+'</'+tag+'>';
break;
case 'h3':
text+='<'+tag+' contenteditable="true" onkeyUp="heard_format();need_save();">'+tt+'</'+tag+'>';
break;
case 'BLi':
text+='<li>';
text+='<b contenteditable="true" onkeyUp="need_save();">标题</b><text contenteditable="true" onkeyUp="need_save();">文本</text>';
text+='</li>';
break;
}


text+='</edit_item>';
$('editor_box').append(text);
heard_format();
}

function format_ck_edit(){
if($('editor_box editor').length==0){return;}
$('editor_box editor').each(function(){
var id=$(this).attr('id');
ClassicEditor.create( document.querySelector('#'+id),{licenseKey:''} ).then( 
editor =>{
eval('window.'+id+'= editor;');
})
})
}


function ck_edit(){
var t=(new Date()).getTime();
var text='<edit_item id="item_'+t+'" c="ck_editor">';
text+='<editor id="editor_'+t+'">';
text+='</editor>';
text+='</edit_item>';
$('editor_box').append(text);
ClassicEditor.create( document.querySelector('#editor_'+t),{licenseKey:''} ).then( 
editor =>{
eval('window.editor_'+t+'= editor;');
} )
}



function insert_img(o){
var img=window.is_select_img;
window.is_select_img='';
var t=(new Date()).getTime();
var text='<edit_item id="item_'+t+'" c="img" class="img_box">';
text+= '<edit_tool>';
text+='<icon class="icon icon-dashboard-line" onclick="css_edit($(this))" i="item_'+t+'"></icon>';
text+='<icon class="icon icon-jiantouxiangshang" onclick="up($(this))" i="item_'+t+'"></icon>';
text+='<icon class="icon icon-jiantouxiangxia" onclick="down($(this))"  i="item_'+t+'"></icon>';
text+='<icon class="icon icon-close-fill" title="删除" onclick="edit_item_del($(this))" i="item_'+t+'"></icon>';
text+='</edit_tool>';

for(var i in img){
text+='<img_item img="'+img[i].img+'">';
text+='<in><img src="'+img[i].img_1080+'">';
text+='<text contenteditable="true" onkeyUp="need_save();">图片描述</text></in>';
text+='</img_item>';
}

text+='</edit_item>';
$('editor_box').append(text);
need_save();
right_close();
}

function need_save(ac){
if(ac==1){$('tool .save').removeClass('select');}else{
$('tool .save').addClass('select');
$('tool .publish').addClass('select');
}
}


function save(){
var obj=$('editor_box edit_item');
var data={};
if(obj.length>0){
obj.each(function(){
var c=$(this).attr('c');
var id=$(this).attr('id');
switch(c){
case 'text':
var val=$(this).find('pre').html();if(val=='文本'){val='';}
var style=$(this).find('pre').attr('style');if(!style){style='';}
if(val){data[[id]]={type:'text',val:val,style:style}}
break;
case 'h2':
var val=$(this).find('h2').text();if(val=='导航'){val='';}
var style=$(this).find('h2').attr('style');if(!style){style='';}
if(val){data[[id]]={type:'h2',val:val,style:style}}
break;

case 'h3':
var val=$(this).find('h3').text();if(val=='导航'){val='';}
var style=$(this).find('h3').attr('style');if(!style){style='';}
if(val){data[[id]]={type:'h3',val:val,style:style}}
break;

case 'li'://列表
var val=$(this).find('li').text();if(val=='列表'){val='';}
var style=$(this).find('li').attr('style');if(!style){style='';}
if(val){data[[id]]={type:'li',val:val,style:style}}
break;
case 'BLi':
var val=$(this).find('li text').text();if(!val || val=='文本'){val='';}
var b=$(this).find('li b').text();if(!b || b=='标题'){b='';}
data[[id]]={type:'BLi',val:val,style:'',b:b}
break;
case 'table':
var val=$(this).find('table').html();
if(val){data[[id]]={type:'table',val:val}}
break;

case 'img':
var val={};var num=0;
$(this).find('img_item').each(function(){
var img=$(this).attr('img');
var text=$(this).find('text').text();
if(text=='图片描述'){text='';}
num++;
val['item_'+num]={text:text,img:img}
})
data[[id]]={type:'img',val:val}
break;

case 'ck_editor':
var id=$(this).find('editor').attr('id');
var val='';
eval('val=window.'+id+'.getData();');
data[[id]]={type:'ck_editor',val:val}
break;


}

})
}
console.log(data);
post('html_save',data,'<?php echo $l['id']?>',function(res){
need_save(1);
msg('修改成功');
clearTimeout(window.save_time);
<?php 
if($set['bind_timeout']>0){
echo 'setTimeout(function(){save();},'.($set['bind_timeout']*1000).');';
}

?>
})
}

function heard_format(){
var html='';
$('editor_box edit_item').each(function(){
var c=$(this).attr('c');
if(c=='h2' || c=='h3'){
var text=$(this).find(c).text();
html+='<item class="'+c+'"><a>'+text+'</a></item>';
}

});
$('index_box').html(html);
}


left_show_box('<?php echo $left_show?>');
function left_show_box(ac){
if(ac=='group'){
left_show($('main_left .select').parent().find('handle:first'));
}
if(ac=='sub'){
left_show($('main_left .select').parent().parent().find('handle:first'));
left_show($('main_left .select').parent().find('handle:first'));
}
}

function left_show(o){
var obj=o.parent();var c=o.attr('c');
if(obj.hasClass('is_'+c+'_show')){
obj.removeClass('is_'+c+'_show');
if(c=='group'){
obj.find('handle:first .icon-arrow-right-s-line').removeClass('rotate');
}else{
obj.find('handle .icon-arrow-right-s-line').removeClass('rotate');
}

}else{
obj.addClass('is_'+c+'_show');
if(c=='group'){
obj.find('handle:first .icon-arrow-right-s-line').addClass('rotate');
}else{
obj.find('handle .icon-arrow-right-s-line').addClass('rotate');
}
}

}
//left_menu_edit_show($('tool .left_menu_edit'));
function left_menu_edit_show(o){
if(!o.hasClass('select')){
$('main_left_box left_group').each(function(){
if(!$(this).hasClass('is_group_show')){$(this).find('handle:first').click();}
});
$('main_left_box left_sub').each(function(){
if(!$(this).hasClass('is_sub_show')){$(this).find('handle:first').click();}
});
o.addClass('select');
$('main_left_box a').removeAttr('href');
$('main_left_box').addClass('can_edit');
}
}


<?php 
//开启服务器
$time=time();
$sign=md5($cfg['sign'].$time);    
?>

function index_edit(o){
var id=o.attr('i');
if(!id){id=0}
var c=o.attr('c');if(!c){c='';}
var val=o.attr('val');if(!val){val=''}
var sub=o.attr('sub');if(!sub){sub=''}
right({title:'导航创建编辑',url:'./adm/index_edit.php?id='+id+'&val='+val+'&sub='+sub+'&c='+c,width:'500px'})

}


function select_this_table(o){
var column=parseInt(o.attr('column'));
var row=parseInt(o.attr('row'));
$('table_select_box item').each(function(){
var c=parseInt($(this).attr('column'));
var r=parseInt($(this).attr('row'));
if(column>=c && row>=r){$(this).addClass('select');}else{$(this).removeClass('select');}
})
$('table_select_box text').html(row+'×'+column);
}

function creat_this_table(o){
var column=parseInt(o.attr('column'));
var row=parseInt(o.attr('row'));
var t=(new Date()).getTime();
var text='<edit_item id="item_'+t+'" c="table">';
text+='<table><tr>';
for(i=1;i<=column;i++){
text+='<th contenteditable="true" >'+i+'</th>';
}
text+='</tr>';

for(i=1;i<=row;i++){
text+='<tr>';
for(j=1;j<=column;j++){
text+='<td contenteditable="true">'+j+'</td>';
}

text+='</tr>';
}

text+='</table>';
text+='</edit_item>';
$('editor_box').append(text);
$('tool table_select_box').hide(1);
}

function loginout(){
if(!confirm("是否退出登录?")){return;}
post('loginout','','',function(res){
location.reload();
});
}


function post(ac,data,id,callback){if(!data){data={}}
console.log(data,id,ac);
$.post('./server.php',{ac:ac,data:data?JSON.stringify(data):'',id:id?id:0,web_sign:'<?php echo $sign?>',timestep:'<?php echo $time?>'},function(res){
    console.log(res);
    var d=json(res,1);
    if(d.err=='ok'){
    callback(d);
    }else{
    if(d.err=='no_login'){no_login();return;}
    
    msg(d.err,'icon-warnfill');
    }
});    
}




</script>
</body>
</html>