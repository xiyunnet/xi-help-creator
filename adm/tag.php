<?php 
include('../fun.php');
$conn=my_sql();
$set=get_set();
$user=is_login();
if(!$user){no_login();}

$sql='select * from '.$cfg['e'].'data where c="tag" order by id desc';
$l=db($sql);

?>
<style>
tag_box{float:left;width:100%;}
tag_box bt_box{float:left;width:100%;}
tag_box bt_box a{float:left;height:32px;line-height:32px;font-size:14px;padding-left:20px;padding-right:20px;border-radius:5px;color:#fff;}
tag_box bt_box help{float:left;width:100%;line-height:16px;margin-top:5px;font-size:12px;color:#999}

tag_box item{float:left;width:100%;margin-top:5px;border-bottom:1px solid #eee;height:32px;line-height:32px;padding-left:10px;padding-right:80px;text-align:center;font-size:13px;}
tag_box item a{float:left;width:50%;overflow:hidden;height:32px;}
tag_box item text{position:absolute;right:0;}
tag_box item icon{float:right;width:25px;}
tag_box item icon:hover{color:crimson;}
</style>

<tag_box>
<bt_box>
<a class="green_bt" onclick="win({title:'新标签',url:'./adm/tag_edit.php',width:'500px',id:'tag_edit'})">新增标签</a>
<help>标签用于快速替换文本中的变量，例如在文本中输入{{webname}}，将所以该标签替换为站点的名称。</help>
</bt_box>

<?php 
if($l){
echo '<item>
<a>标签名</a>
<a>替换值</a>
<text>操作</text>
</item>';
foreach($l as $v){
echo '<item>
<a>{{'.$v['title'].'}}</a>
<a>'.$v['data'].'</a>
<text>
<icon class="icon icon-checkbox-multiple-blank-line1 tag_'.$v['id'].'" i="'.$v['id'].'" onclick="copy($(this))" data-clipboard-text="{{'.$v['title'].'}}"></icon>
<icon class="icon icon-edit" onclick="edit_tag($(this))" i="'.$v['id'].'"></icon>
<icon class="icon icon-close-fill" onclick="del($(this))" i="'.$v['id'].'"></icon>
</text>
</item>';


}
}else{
no_data('您还没有设置标签');
}
?>

</tag_box>
<script>
function edit_tag(o){
    var id=o.attr('i');
    win({title:'新标签',url:'./adm/tag_edit.php?id='+id,width:'500px',id:'tag_edit'})
}

function del(o){
var id=o.attr('i');
if(!confirm("删除将无法恢复，是否继续?")){return;}
post('tag_del','',id,function(res){
    right_reload();
});
}
function copy(o){
var id=o.attr('i');
var clipboard = new ClipboardJS('.tag_'+id);

clipboard.on('success', function(e) {
msg('复制成功');
clipboard.destroy();
    e.clearSelection();
});
}

</script>