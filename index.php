<?php 
include('./fun.php');
$conn=my_sql($cfg);
$set=get_set();
$user=is_login();
$redis=redis();

$id=is_num('id');
if($id){
$sql='select * from '.$cfg['e'].'help where id=:id  ';
$p['id']=$id;
if(!$user){$sql.=' and state=1 ';}
$sql.=' order by id desc limit 1';
$l=db1($sql,$p);
}else{
$s=g('s');$tag=g('tag');if(!$s){$s='';}
if($tag){
$sql='select * from '.$cfg['e'].'help where s=:s and tag=:tag ';
$p['s']=$s;$p['tag']=$tag;
if(!$user){$sql.=' and state=1 ';}
$sql.=' order by id desc limit 1';
$l=db1($sql,$p);
}
}

if($l){$lan=$l['lan'];}else{$lan=get_lan();}

//分组
$sql='select id,title,tag,s,type,lan from '.$cfg['e'].'help where lan="'.$lan.'" and type="arc"  ';
if(!$user){$sql.=' and state=1 ';}
$sql.=' order by o desc,id asc';
$left=db($sql);

if($left){
foreach($left as $v){
if(!$first_arc){$first_arc=$v['id'];}
if($v['s']){
$x=explode('||',$v['s']);
if(!$x[1]){$x[1]=$v['id'];}
$left_menu[$x[0]][$x[1]][]=$v;
}else{
$left_menu[]=$v;
}
$arc_list['item_'.$v['id']]=$v;
}
}

$preview=g('preview');

if(!$l and $first_arc){
$sql='select * from '.$cfg['e'].'help where id="'.$first_arc.'"  ';
$sql.=' order by id desc limit 1';
$l=db1($sql);
}

$backgroud_style=c('background_style');
if(!$backgroud_style){$backgroud_style='day';}
$helpful=c('helpful_'.$l['id']);
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
<meta name="description" content="<?php echo ($l['info']?format_text($l['info']):format_text($l['title']))?>">
<meta property="”og:title”" content="<?php echo format_text($l['title']);?>">
<meta property="og:description" content="<?php echo ($l['info']?format_text($l['info']):format_text($l['title']))?>">
<meta property="og:url" content="https://<?php echo $cfg['domain']?>/">

<title><?php echo format_text($l['title']).' '.$set['webname'];?></title>
<script src="./jquery.js"></script>
<script src="./qrcode.js"></script>
<script src="./copy.js"></script>
<script src="./fun.js?t=<?php echo $cfg['time']?>"></script>
<link type="text/css" rel="stylesheet" href="./style.css?<?php echo $cfg['time']?>">
<link type="text/css" rel="stylesheet" href="./index.css?<?php echo $cfg['time'].'12'?>">
</head>

<body>
<main>
<main_left class="<?php echo $backgroud_style?>_left">

<a class="nav_top" href="./">
<logo style="background:url(<?php echo $set['logo']?>) no-repeat center,#ccc;background-size:cover;"></logo>
<text><?php echo $set['webname']?></text>
</a>
<main_left_box>
<?php $left_show=0;
if($left_menu){
foreach($left_menu as $k=>$v){
if($v['type']=='arc'){
if($next_go==1){
$next=$v;
$next_go=0;
}

if($l['id']==$v['id']){
if(!$pre){$pre=$pre_temp;}
$next_go=1;
}

$pre_temp=$v;


//if($set['debug']){
$url='?lan='.$v['lan'].'&s='.urlencode($v['s']).'&tag='.urlencode($v['tag']);
//}else{
//$url='./help/'.$v['lan'].'/'.($v['s']?urlencode($v['s']).'/':'').urlencode($v['tag']);
//}

echo '<a class="view m '.($l['id']==$v['id']?'select':'').'" href="'.$url.'"><span></span>
<text>'.format_text($v['title']).'</text>
</a>';
}else{//组
echo '<left_group >
<handle onclick="left_show($(this))" c="group">
<text>'.$k.'</text>
<icon class="icon icon-arrow-right-s-line m"></icon>
</handle>
';


foreach($v as $m=>$n){
if(is_numeric($m) ){//文章
foreach($n as $z){
if($z['type']=='arc'){
if($z['id']==$l['id']){$left_show='group';}

if($next_go==1){
$next=$z;
$next_go=0;
}
if($l['id']==$z['id']){
if(!$pre){$pre=$pre_temp;}
$next_go=1;
}
$pre_temp=$z;


//if($set['debug']){
$url='?lan='.$z['lan'].'&s='.urlencode($z['s']).'&tag='.urlencode($z['tag']);
//}else{
//$url='./help/'.$z['lan'].'/'.urlencode($z['s']).'/'.urlencode($z['tag']);
//}
echo '<a class="view m '.($l['id']==$z['id']?'select':'').'" href="'.$url.'"><span></span>
<text >'.format_text($z['title']).'</text>

</a>';
}
}


}else{//子目录
echo '<left_sub >
<handle onclick="left_show($(this))" c="sub">
<text>'.$m.'</text>
<icon class="icon icon-arrow-right-s-line m"></icon>

</handle>';

foreach($n as $z){
if($z['type']=='arc'){
if($z['id']==$l['id']){$left_show='sub';}
if($next_go==1){
$next=$z;
$next_go=0;
}
if($l['id']==$z['id']){
if(!$pre){$pre=$pre_temp;}
$next_go=1;
}
$pre_temp=$z;

//if($set['debug']){
$url='?lan='.$z['lan'].'&s='.urlencode($z['s']).'&tag='.urlencode($z['tag']);
//}else{
//$url='./help/'.$z['lan'].'/'.urlencode($z['s']).'/'.urlencode($z['tag']);
//}

echo '<a class="view m '.($l['id']==$z['id']?'select':'').'" href="'.$url.'"><span></span>
<text >'.format_text($z['title']).'</text>

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

<main_box class="<?php echo $backgroud_style?>_main">


<nav>
<day_nignt  class="m" onclick="go_dark($(this))" val="<?php echo ($backgroud_style=='dark'?'day':'dark')?>">
<icon class="icon m icon-sun-line"></icon>
<icon class="icon m icon-moon-line"></icon>
</day_nignt>
<line></line>
<?php 
$cfg['nav']['首页']['select']='select';
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
<h1><?php echo format_text($l['title']); ?></h1>
<?php 
if($user){
if($l['state']==0){
echo '<warn>您的文档还没有上线，点击此上线。<a onclick="state($(this))" i="'.$l['id'].'">上线</a></warn>';
}
if(!$l['is_publish'] and $l['data']){
echo '<warn>您的文档最近编辑时间为'.date('Y年m月d日 H:i:s',$l['last_update']).' 点击此进行发布。
<a href="?id='.$l['id'].'&preview=preview">预览</a><a onclick="publish($(this))" i="'.$l['id'].'">发布</a></warn>';
}

}

if($l['info']){echo '<info>'.format_text($l['info']).'</info>';}
?>
<?php 
//数据
if($preview and $user){
$html_data=$l['data'];
}else{
$html_data=$l['html'];
}


if($html_data){
$data=unserialize(base64($html_data));
if($data){
foreach($data as $k=>$v){
echo '<edit_item id="'.$k.'"  c="'.$v['type'].'" class="'.($v['type']=='img'?'img_box':'').'">';
switch($v['type']){
default:break;
case 'text':
echo '<pre  style="'.$v['style'].'">'.format_text($v['val']).'</pre>';
break;
case 'h2':
$val=clean_tag(format_text($v['val']));
echo '<h2 style="'.$v['style'].'"><a href="#'.$val.'" id="'.$val.'">'.format_text($v['val']).'</a></h2>';
$index.='<item class="h2 '.$k.'"><a href="#'.$val.'">'.format_text($v['val']).'</a></item>';
break;
case 'h3':$val=clean_tag(format_text($v['val']));
echo '<h3  style="'.$v['style'].'"><a href="#'.$val.'" id="'.$val.'">'.format_text($v['val']).'</a></h3>';
$index.='<item class="h3 '.$k.'"><a href="#'.$val.'">'.format_text($v['val']).'</a></item>';
break;  
case 'li':
echo '<li  style="'.$v['style'].'">'.format_text($v['val']).'</li>';
break;
case 'BLi':
echo '<li><b >'.$v['b'].'</b><text>'.format_text($v['val']).'</text></li>';
break;
case 'table':
echo '<table>'.str_replace(['contenteditable="true"','onkeyUp="need_save();"'],'',format_text($v['val'])).'</table>';
break;
case 'img':
if($v['val']){
foreach($v['val'] as $t){
echo '<img_item img="'.$t['img'].'">';
$img=get_img($t['img'],'_1080');
echo '<in><img src="'.$img.'" onclick="img_preview($(this))" val="'.$img.'">';
if($t['text']){
echo '<text>'.($t['text']?$t['text']:'图片描述').'</text></in>';
}
echo '</in>';
echo '</img_item>';
}

}

break;
case 'ck_editor':
echo '<editor id="'.$k.'">'.str_replace(['<p>','</p>'],['<div>','</div>'],format_text($v['val'])).'</editor>';
break;

}

echo '</edit_item>';
}}}else{
echo '<h2 '.($user?'onclick="go_url($(this))" url="./editor.php?id='.$l['id'].'"':'').'>抱歉，该文档还没有编辑';
if($user){echo ',点击此编辑';}
echo '。</h2>';
}
?>


</html_box>



<helpful>
<text>本页是否对您有帮助?</text>
<bt class="icon icon-thumb-up-fill yes m <?php echo ($helpful=='yes'?'yes_select select':'')?>" onclick="helpful($(this))" val="yes"><text>Yes</text></bt>
<bt class="icon icon-thumb-down-fill no m <?php echo ($helpful=='no'?'no_select select':'')?>" onclick="helpful($(this))" val="no"><text>No</text></bt>
</helpful>

<a href="./editor.php?id=<?php echo $l['id']?>" class="edit_a"><icon class="icon icon-editor"></icon><text>编辑此页面</text></a>

<main_page>
<?php 
if($pre){
if($set['debug']){
$url='?lan='.$pre['lan'].'&s='.urlencode($pre['s']).'&tag='.urlencode($pre['tag']);
}else{
$url='./'.$pre['lan'].'/'.($pre['s']?urlencode($v['s']).'/':'').urlencode($pre['tag']);
}
echo '
<view class="pre">
<a class="m" href="'.$url.'"><text>上一页</text>
<span>'.format_text($pre['title']).'</span>
</a>
</view>';
}
if($next){
if($set['debug']){
$url='?lan='.$next['lan'].'&s='.urlencode($next['s']).'&tag='.urlencode($next['tag']);
}else{
$url='./'.$next['lan'].'/'.($next['s']?urlencode($v['s']).'/':'').urlencode($next['tag']);
}
echo '
<view class="next">
<a class="m" href="'.$url.'"><text>下一页</text>
<span>'.format_text($next['title']).'</span>
</a>
</view>';
}

?>



</main_page>
</main_in_box>

</main_box>

<?php 
if($index){
echo '<index_box class="'.$backgroud_style.'_index"><line class="m"></line>'.$index.'</index_box>';
}
?>

</main>
<right_box class="m">
<handle><text></text><icon class="icon icon-close" onclick="right_close($(this))"></icon></handle>
<right_in_box></right_in_box>
</right_box>
<bg onclick="right_close($(this));win_close($(this))" ></bg>


<script src="https://ace.c9.io/build/src/ace.js"></script>
<script>
$(document).ready(function(){
$('main_left item_box:last').addClass('no_border');
get_h();
});

window.select_index=0;
$('main_in_box').scroll(function(){
var top=-$('html_box').position().top;

if(top>30){
if(window.index_data){
var index_top=0;var num=0;
for(var i in window.index_data){
if(window.index_data[i].top<=top){num++;
if(window.index_data[i].c=='h2'){var hh=32;}else{var hh=24;}
if(num==1){
index_top=8;
}else{
index_top+=hh;
}

if(window.index_data[i].id!=window.select_index){
window.select_index=window.index_data[i].id;
$('index_box item').removeClass('select');
$('index_box .'+window.index_data[i].id).addClass('select');
}


}
}
console.log(index_top,num);
if(num>0){
$('index_box line').css({'display':'block',top:index_top});
}

}}else{
$('index_box line').css({'display':'none',top:0});
}

})

function go_dark(o){
var val=o.attr('val');
if(val!='dark'){val='day';}
if(val=='day'){
$('main_left').removeClass('dark_left');
$('main_box').removeClass('dark_main');
$('index_box').removeClass('dark_index');
}
$('main_left').addClass(val+'_left');
$('main_box').addClass(val+'_main');
$('index_box').addClass(val+'_index');
o.attr('val',val=='dark'?'day':'dark');
document.cookie="background_style="+val;
}


function helpful(o){
var val=o.attr('val');
var data={val:val}
post('helpful',data,'<?php echo $l['id']?>',function(res){
$('helpful bt').removeClass('yes_select');
$('helpful bt').removeClass('no_select');
$('helpful bt').removeClass('select');
o.addClass(val+'_select');
o.addClass('select');
document.cookie="helpful_<?php echo $l['id']?>="+val;
});
}



function get_h(){
var h_data={};var num=0;
$('html_box edit_item').each(function(){
var c=$(this).attr('c');
if(c=='h2' || c=="h3"){num++;
var top=$(this).position().top;
var id=$(this).attr('id');
h_data['item_'+num]={top:top,c:c,id:id};
}

});
console.log(h_data);
window.index_data=h_data;
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





<?php 
//开启服务器
if($user){
$time=time();
$sign=md5($cfg['sign'].$time);    

?>


function state(o){
var id=o.attr('i');
if(!confirm("是否上线文档?")){return;}
var data={state:1}
post('help_state',data,id,function(res){
location.reload();
msg('上线成功');
});
}

function publish(o){
var id=o.attr('i');
if(!confirm("是否发布新版本文档?")){return;}
post('publish','',id,function(res){
msg('发布成功');
setTimeout(function(){location.reload();},2000);
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



<?php }?>
</script>
</body>
</html>