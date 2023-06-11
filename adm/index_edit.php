<?php 
include('../fun.php');
$conn=my_sql($cfg);
$user=is_login();
if(!$user){no_login();}
$id=is_num('id');
if($id){
$sql='select * from '.$cfg['e'].'help where id="'.$id.'" order by id desc  limit 1';
$l=db1($sql);
if(!$l){die($lan['no_index_find']);}
}else{
$c=g('c');
$sub=g('sub');
$val=g('val');
if($sub){
$sql='select * from '.$cfg['e'].'help where type=:type and s=:s and title=:title order by id desc limit 1';
$p['type']=$c;
$p['s']=$sub;
$p['title']=$val;

$l=db1($sql,$p);
if(!$l){die($lan['no_index_find']);}
}
}






$lan=get_lan();
if($l){$lan=$l['lan'];}

$sql='select title,type,s from '.$cfg['e'].'help where type="group" and lan="'.$lan.'"  order by o desc,id asc';
$group=db($sql);



?>
<style>
index_edit_box{float:left;width:100%;}
index_edit_box l{float:left;width:100%;margin-top:10px;min-height:36px;padding-left:90px}
index_edit_box l text{position:absolute;left:0;line-height:36px;font-size:13px;width:80px;text-align:right;}
index_edit_box input{float:left;width:100%;height:36px;line-height:36px;background:#f6f6f6;border-radius:5px;box-sizing:border-box;padding-left:10px;padding-right:10px;}
index_edit_box bt{float:left;padding-left:20px;padding-right:20px;margin-right:10px;margin-bottom:5px;height:36px;line-height:36px;background:#eee;border-radius:5px;font-size:13px;color:#999}
index_edit_box bt:hover{color:#000;}
index_edit_box textarea{float:left;width:100%;height:120px;padding:10px;border:1px solid #eee;box-sizing:border-box;background:#f6f6f6;border-radius:5px;outline:none;}
index_edit_box help{float:left;line-height:16px;color:#999;font-size:10px;width:100%;margin-top:5px;}
index_edit_box no_group{float:left;width:100%;font-size:14px;line-height:36px;}
index_edit_box g1,index_edit_box g2{float:left;width:100%;}
index_edit_box .s{box-shadow:none}

</style>

<index_edit_box>
<l><text>标题/名称</text>
<input placeholder="文档的标题或帮助分组名称" c="title" need="1" value="<?php echo $l['title']?>" onkeyUp="val_change($(this))">
</l>

<l><text>分类</text>
<bt class="m <?php echo $l['type']=='arc'?'green_bt':''?>" onclick="select_type($(this))" c="arc" >文章</bt>
<bt class="m <?php echo $l['type']=='group'?'green_bt':''?>" onclick="select_type($(this))" c="group">组</bt>
</l>

<group_box>
<l class="s"><text>选择组</text>
<?php 
if($group){
foreach($group as $v){
if($v['s']){
if($l['type']!='group'){
$group2.='<bt class="m " onclick="select_this($(this))" need="1" c="s" val="'.$v['s'].'||'.$v['title'].'">'.$v['s'].'>'.$v['title'].'</bt>';
}
    
}else{
$group1.='<bt class="m '.($l['s']==$v['title']?'green_bt':'').'" onclick="select_this($(this))" need="1" c="s" val="'.$v['title'].'">'.$v['title'].'</bt>';
}

}

echo '<g1>'.$group1.'</g1>';
echo '<g2>'.$group2.'</g2>';

}else{
echo '<no_group>没有可用的分组</no_group>';
}
?>

<help>不选择组，则显示为一级分类，选择后为该组下文章。</help>
</l></group_box>



<l class="lan"><text>语言</text>
<?php 
foreach($cfg['lan'] as $k=>$v){
echo '<bt class="m '.($k==$l['lan']?'green_bt':'').'" onclick="select_this($(this))" need="0" c="lan" val="'.$k.'" >'.$v.'</bt>';

}
?>
<help>请选择一种默认语言</help>
</l>

<arc_box>


<l><text>文章标签</text>
<input placeholder="文章标签 用于显示文章的唯一关键词" c="tag" need="1" value="<?php echo $l['tag']?>" onkeyUp="val_change($(this))">
</l>

<l><text>文章介绍</text>
<textarea placeholder="介绍" c="info" onkeyUp="val_change($(this))"><?php echo $l['info']?></textarea>
</l>

</arc_box>
<l><text>排序</text>
<input placeholder="数值越高，排序越靠前" c="o" need="1" type="number" value="<?php echo $l['o']?>" onchange="val_change($(this))">
</l>

<l>
<bt  class="m save_bt" onclick="submit($(this))" >提交</bt>
</l>

</index_edit_box>

<script>
window.edit_data=<?php echo $l?json_encode($l):'""'?>;
if(!window.edit_data){window.edit_data={}}

function submit(){
if(!window.edit_data){err('请输入内容');return;}
post('creat_help',window.edit_data,'<?php echo $l['id']?>',function(res){
location.reload();
});
}

function select_this(o){
var c=o.attr('c');var val=o.attr('val');
var need=parseInt(o.attr('need'));
if(need==1){
if(o.hasClass('green_bt')){
o.removeClass('green_bt');
val='';
}else{
$('.'+c).find('bt').removeClass('green_bt');
o.addClass('green_bt');
}
}else{
$('.'+c).find('bt').removeClass('green_bt');
o.addClass('green_bt');
}
window.edit_data[[c]]=val;

}

function select_type(o){
var c=o.attr('c');
o.parent().find('bt').removeClass('green_bt');
o.addClass('green_bt');
window.edit_data['type']=c;
if(c=='group'){
$('index_edit_box arc_box').hide();
$('group_box g1').show();
$('group_box g2').hide();


}else{
$('index_edit_box arc_box').show();
$('group_box g1,group_box g2').show();
}
}

function val_change(o){
var val=o.val();if(!val){val='';}
var c=o.attr('c');
window.edit_data[[c]]=val;
need_save();
}

function need_save(ac){
var obj=$('index_edit_box .save_bt');
if(ac){
   obj.removeClass('green_bt') 
}else{
obj.addClass('green_bt')
}
}


</script>