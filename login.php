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
</style>
<login_box>
<l>
<input placeholder="请输入用户名" c="username" onkeyUp="check_input()">
</l>
<l>
<input placeholder="请输入登录密码" c="password" type="password" onkeyUp="check_input()">
</l>
<l>
<bt class="m" onclick="login($(this))">登录</bt>
</l>
</login_box>

<script>
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
post('login',data.data,'',function(res){
location.reload();
});

}
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