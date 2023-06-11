<?php 
include('../fun.php');
$conn=my_sql($cfg);
$user=is_login();
if(!$user){no_login();}
$id=is_num('id');
if($id){
$sql='select * from '.$cfg['e'].'data where id="'.$id.'" and c="tag"';
$l=db1($sql);
if(!$l){die('没有找到相应的标签');}
}


?>
<style>
edit_tag_box{float:left;width:100%;}
edit_tag_box l{float:left;width:100%;min-height:32px;line-height:32px;margin-top:10px;padding-left:80px;}
edit_tag_box l text{position:absolute;left:0;}
edit_tag_box l input{float:left;width:100%;height:32px;line-height:32px;}
edit_tag_box bt{float:left;padding-left:20px;padding-right:20px;margin-right:10px;background:#ccc;height:32px;line-height:32px;border-radius:5px;color:#fff;}
edit_tag_box help{float:left;width:100%;line-height:16px;margin-top:3px;font-size:10px;color:#999}
</style>

<edit_tag_box>
<l><text>标签名</text>
<input placeholder="标签的名称 例如 {{webname}} 请输入webname" c="title" need="1" value="<?php echo $l['title']?>">
</l>
<l><text>替换值</text>
<input placeholder="该标签的替换值" c="data" need="1" value="<?php echo $l['data']?>">
</l>
<l><bt class="green_bg m" onclick="submit($(this))">提交</bt></l>
</edit_tag_box>
<script>
function submit(o){
var data=form_data($('edit_tag_box input'));
if(data.can>0){return;}

post('tag_edit',data.data,'<?php echo $l['id']?>',function(res){
$('#tag_edit').remove();
right_reload();
});
}

</script>