function img_preview(o){
var val=o.attr('val');
var html='<img_prevew onclick="$(this).remove()"><img src="'+val+'" style="background:#fff">';
html+='</img_prevew>';
$('body').append(html);
}


function is_phone(pone) {
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(pone)) {
      return false;
    } else {
      return true;
    }
  }
  
  
function json(d,ac){
if(ac){return JSON.parse(d);}else{return JSON.stringify(d);}
}

function err(text,time){if(!time){time=2000;}
var h='<msg_box class="m" onclick="msg_hide($(this))"><in_box>';
h+='<msg_in_box class="err_box"><icon class="icon '+(icon?icon:'icon-alert-line')+'"></icon><text>'+text+'</text></msg_in_box></in_box></msg_box>';
$('msg_box').remove();
$('body').append(h);
$('msg_box').fadeIn(200);
setTimeout(function(){msg_hide();},time);
}

function msg(text,icon,time){if(!time){time=2000;}
var h='<msg_box class="m" onclick="msg_hide($(this))"><in_box>';
h+='<msg_in_box><icon class="icon '+(icon?icon:'icon-alert-line')+'"></icon><text>'+text+'</text></msg_in_box></in_box></msg_box>';
$('msg_box').remove();
$('body').append(h);
$('msg_box').fadeIn(200);
setTimeout(function(){msg_hide();},time);
}

function msg_hide(){
$('msg_box').fadeOut(200,function(){$('msg_box').remove();})
}


function win(d){
var width=d.width;
var w=$(window).width();
var left=Math.ceil((w-width)/2);
if(d.id){
$('#'+d.id).remove();
}else{var id=(new Date()).getTime();d.id='win_'+id;}
var h='<win id="'+d.id+'" style="left:'+left+'px;width:'+d.width+'px"><handle class="m"><text>'+(d.title?d.title:'新窗口')+'</text><icon class="icon icon-close-fill" onclick="win_close($(this))" i="'+d.id+'"></icon></handle>';
h+='<win_box></win_box>';
h+='</win>';

$('body').append(h);
$('#'+d.id).draggable({handle:$('#'+d.id+' handle')});

if(d.html){$('#'+d.id+' win_box').html(d.html);}
if(d.url){$('#'+d.id+' win_box').load(d.url);}
if(d.bg==1){$('bg').show();$('bg').attr('i',d.id);}
}

function win_close(o){
var id=o.attr('i');
if(id){
$('#'+id).remove();
}else{
$('win').remove();
}
$('bg').hide();
}




function form_data(obj){
var data={};var can=0;
obj.each(function(){
var c=$(this).attr('c');
var val=$(this).val();
var need=$(this).attr('need');
var t=$(this).attr('t');
if(!t){t=$(this).attr('placeholder');}
$(this).removeClass('input_err');
if(need==1 && !val){msg(t+'不能为空');can++;
$(this).addClass('input_err');
return;}
data[[c]]=val;
})
var re={data:data,can:can};
console.log(re);
return re;
}





function right(d){
if(!d.title){d.title='新窗口';}
$('right_box handle text').html(d.title);
if(d.width){$('right_box').width(d.width);}
if(d.html){$('right_box right_in_box').html(d.html);}
if(d.url){right_load(d.url);}
$('right_box').addClass('right_show');
if(d.bg!=1){$('bg').show();}
}

function right_reload(){right_load(window.right_url);}
function right_load(url){
$('right_box right_in_box').load(url,function(){
window.right_url=url;
$('right_box right_in_box input').each(function(){
var need=$(this).attr('need');
if(need==1){
$(this).attr('onblur','check_null($(this))');
$(this).attr('onkeydown','check_null($(this))');
}
});
});
}

function right_close(){
$('right_box').removeClass('right_show');
$('bg').hide();
$('right_box right_in_box').html('');
}



function go_url(o){
var url=o.attr('url');
if(!url){var url=o.attr('href');}
if(!url){return;}
location.href=url;
}


