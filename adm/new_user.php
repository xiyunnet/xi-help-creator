<?php 
include('./fun.php');

$time=time();
$sign=md5($cfg['sign'].$time);   

?>
<style>
login_box{float:left;width:100%;}
login_box l{float:left;width:100%;margin-bottom:10px;}
login_box input{float:left;width:100%;height:50px;line-height:50px;border-radius:5px;background:#f6f6f6;border:1px solid #eee;box-sizing:border-box;padding-left:10px;padding-right:10px;}
login_box bt{float:left;width:100%;height:50px;line-height:50px;text-align:center;font-size:16px;background:#ddd;color:#fff;border-radius:5px;}
login_box l a{float:left;margin-left:10px;padding-left:20px;padding-right:20px;height:36px;line-height:36px;margin-top:6px;border-radius:5px;background:#ccc}
login_box l text{float:left;line-height:50px;}
</style>
<login_box>
<l>
<input placeholder="请输入用户名" c="username" onkeyUp="check_input()">
</l>
<l>
<input placeholder="请输入登录密码" c="password"  onkeyUp="check_input()">
</l>

<l>
<text>选择权限:</text>
<input c="power" need="1" type="hidden">
<a c="adm" onclick="select_power($(this))">管理员</a>
<a c="user" onclick="select_power($(this))">用户</a>
</l>

<l>
<bt class="m" onclick="login($(this))">登录</bt>
</l>
</login_box>

<script>
function select_power(o){
var val=o.attr('c');
o.parent().find('a').removeClass('green_bg');
o.addClass('green_bg');
o.parent().find('input').val(val)
}


function check_input(){
var data=form_data($('login_box input'));
if(data.data.username && data.data.password){
$('login_box bt').addClass('green_bg');
}else{
$('login_box bt').removeClass('green_bg');
}
}

function login(){
var data=form_data($('login_box input'));
if(data.data.username && data.data.password){
post('user_add',data.data,'',function(res){
right_reload();
$('#new_user').remove();
});

}
}



</script>