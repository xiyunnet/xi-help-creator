<?php 


function d($str){
$data=p($str);if(!$data){err('数据错误');}
$data=json_decode($data,1);if(!$data){err('数据错误');}
return $data;
}


function url_get($no){//获取网址地址
$g=$_GET;
if($no){
foreach($no as $v){unset($g[$v]);}}
if(!$g){return;}
foreach($g as $k=>$v){
$str.=$k.'='.$v.'&';
}
return $str;
}

function out($str){return stripslashes($str);}//输出

function direct($url){$url="Location: ".$url;header("HTTP/1.1 301 Moved Permanently");header($url); }//页面跳转函数

function g($ac){
$str=isset($_POST[$ac]) ? $_POST[$ac] : '';
if(!$str){$str=isset($_GET[$ac]) ? $_GET[$ac] : '';}
return trim($str);}

function get($ac){$str=isset($_GET[$ac]) ? $_GET[$ac] : '';return htmlspecialchars(trim($str));}
function p($ac){$str=isset($_POST[$ac]) ? $_POST[$ac] : '';;return trim($str);}

function s($ac){$str=isset($_SESSION[$ac]) ? $_SESSION[$ac] : '';return trim($str);}
function c($ac){$str=isset($_COOKIE[$ac]) ? $_COOKIE[$ac] : '';return trim($str);}
function a($t){return addslashes($t);}

function err($str){$err['err']=$str;echo json_encode($err);exit;}//输出错误信息



//对图片进行处理
function get_img($file,$size='_480',$search=[],$replace=[]){
global $cfg;
if(!strstr($file,'|')){
if($search and $replace){
return str_replace($search,$replace,$file);
}
if(substr($file,0,2)=='//'){$file='https:'.$file;}
return $file;
}

if(strstr($file,'||')){//获取首恶
if($size){//获取首个
$im=explode('||',$file);//print_r($im);
return get_img($im[0],$size);
}else{
$im=get_imgs($file);
return $im;
}
}
$im=explode('|',$file);
if(is_numeric($im[0])){
if($cfg['img_server']){
$num=count($cfg['img_server']);
if($num==1){$server_id=0;}else{
$server_id=substr($im[0],-1)%$num;
}

$path=$cfg['img_server'][$server_id];
}else{
$path='https://'.$cfg['domain'].'/'.($cfg['path']?$cfg['path'].'/':'').$cfg['img_path'].'/';
}
if(substr($path,-1)!='/'){$path=$cfg['img_path'].'/';}
return $path.ceil($im[0]/1000).'/'.$im[0].$size.'.'.$im[1];
}else{return $d;}
}



function get_imgs($img){global $cfg;
if(!$img){return;}
$im=explode('||',$img);
foreach($im as $k=>$v){
if($v){
foreach($cfg['img_size'] as $t){
$re[$k]['img_'.$t]=get_img($v,'_'.$t);
}
$re[$k]['img']=get_img($v,'_480');
$re[$k]['im']=$v;
}
}
return $re;
}

function get_img_arr($img,$c){
if(!$img){return;}
$im=explode('||',$img);
foreach($im as $k=>$v){
if($v){
$re[]=get_img($v,$c);
}
}return $re;
}


function resize_img($path,$img,$new,$ext){
global $cfg;
$widths=$cfg['img_size'];
$img_info=getimagesize($path.$img);
$img_w=$img_info[0];
$img_h=$img_info[1];
$img_type=$img_info[2];
$can=0;
if($img_type>0){
switch($img_type){
case 2:$srcImageSource = imagecreatefromjpeg($path.$img);$can++;break;
case 3:$srcImageSource = imagecreatefrompng($path.$img);$can++;break;
case 18:$srcImageSource = imagecreatefromwebp($path.$img);$can++;break;
}
}
if($can){
foreach($widths as $width){
$new_name=$new.'_'.$width;
//if($img_w>=$width){
$height = ceil($width/$img_w*$img_h);
$tmpImage = imagecreatetruecolor($width,$height);
$color=imagecolorallocate($tmpImage,255,255,255);
imagecolortransparent($tmpImage,$color);
imagefill($tmpImage,0,0,$color);

if(!$srcImageSource){return 0;}
imagecopyresampled($tmpImage,$srcImageSource,0,0,0,0,$width,$height,$img_w,$img_h);
switch($img_type){
case 3:imagepng($tmpImage,$path.$new_name.'.'.$ext);break;
case 2:imagejpeg($tmpImage,$path.$new_name.'.'.$ext,100);break;
case 18:imagewebp($tmpImage,$path.$new_name.'.'.$ext,100);break;
}

//}else{//如果图片小于需要尺寸
//copy($path.$img,$path.$new_name.'.'.$ext);
//exec('ln '.$path.$img.' '.$path.$new_name.'.'.$ext);
//}
}
}}


function my_sql(){//链接数据库
global $conn,$cfg;
if($conn){return $conn;}
$data=$cfg['database'];
if($data['type']=='mysql' or $data['type']=='mysqli'){$data['type']='mysql';}
if(strstr($data['url'],'p:')){$data['ATTR_PERSISTENT']=1;}//长期链接
$data['url']=str_replace('p:','',$data['url']);

$dsn=$data['type'].':host='.$data['url'].';dbname='.$data['database'];

try {
    if($data['ATTR_PERSISTENT']){
    $conn = new PDO($dsn, $data['user'], $data['pass'],array(PDO::ATTR_PERSISTENT => true));//长连接
    }else{
    $conn = new PDO($dsn, $data['user'],$data['pass']);} //初始化一个PDO对象
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}
return $conn;
}



function db($sql,$p=[]){//查询数据
global $conn,$cfg;
$sth = $conn->prepare($sql);
if($p){
foreach($p as $k=>$v){//参数
$sth->bindValue(':'.$k,$v);
}
}

$sth->execute();
$err=$sth->errorInfo();
if($err[0]!=0){die($err[2]);}
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth->closeCursor();
return $result;
while($r = $sth->fetch(PDO::FETCH_ASSOC)){$result[]=$r;}
$rows=$sth->rowCount();
$sth->closeCursor();
return $result;
}

function db1($sql,$p=[]){
global $conn,$cfg;
$sth = $conn->prepare($sql);
if($p){
foreach($p as $k=>$v){//参数
$sth->bindValue(':'.$k, $v);
}
}
$sth->execute();
$err=$sth->errorInfo();
if($err[0]!=0){die($err[2]);}
$result = $sth->fetch(PDO::FETCH_ASSOC);
$sth->closeCursor();
return $result;
}


function insert($table,$arr){
global $conn,$cfg;
if(!$arr){die('数据库插入数据为空');}
foreach($arr as $k=>$v){
if(!$ids){$ids=$k;
$vid=':'.$k;
}else{$ids.=','.$k;$vid.=',:'.$k;}
}
$sql='insert into '.$table.' ('.$ids.') VALUES ('.$vid.') ';
$sth = $conn->prepare($sql);
foreach($arr as $k=>$v){//数据
$sth->bindValue(':'.$k,$v);
}
$sth->execute();
$err=$sth->errorInfo();
if($err[0]!=0){die($err[2]);}
$lastInsertId=$conn->lastInsertId();
$sth->closeCursor();
return $lastInsertId;
}


function update($table,$arr,$where,$sql_p=[]){
global $conn,$cfg;
if(!$arr){die('更新数据为空');}
if(!$where){die('请输入更新条件');}
foreach($arr as $k=>$v){
if(!$ids){$ids=' '.$k.'=:'.$k;}else{
$ids.=' ,'.$k.'=:'.$k;
}
}
$sql='update '.$table.' set '.$ids.' '.$where;
$sth = $conn->prepare($sql);
foreach($arr as $k=>$v){//数据
$sth->bindValue(':'.$k,$v);
}
if($sql_p){
foreach($sql_p as $k=>$v){//参数
$sth->bindParam(':'.$k, $v);
}
}
$sth->execute();
$err=$sth->errorInfo();
if($err[0]!=0){die($err[2]);}
$rows=$sth->rowCount();
$sth->closeCursor();
return $rows;
}


function sql_query($sql){//执行
global $conn,$cfg;
$sth = $conn->prepare($sql);
$sth->execute();
$err=$sth->errorInfo();
if($err[0]!=0){die($err[2]);}
$rows=$sth->rowCount();
$sth->closeCursor();
return $rows;
}

function sql_error(){global $conn,$cfg;return;}

//redis函数库
function redis(){
global $redis;
if(!$redis){
if(class_exists('Redis')){
$redis=new Redis();
$redis->connect('127.0.0.1',6379);
}
}
return $redis;
}

function r_set($key,$val,$time){
global $redis,$adm;
//if($adm['debug']==1){return;}//是否在调试模式下关闭
if($redis){
if(is_array($val)){$val=serialize($val);}
$redis->set($key,$val);
if($time){$redis->expire($key,$time);}
}
}

function r_get($key,$is_arr=1){
global $redis;
if(!$redis){return;}
$val=$redis->get($key);
if(!$val){return;}
if($is_arr){$val=unserialize(out($val));if(!is_array($val)){return;}}
if(!$val){return;}
return $val;
}

// 过滤掉emoji表情
function filterEmoji($str)
{$str = preg_replace_callback('/./u',function (array $match) {return strlen($match[0]) >= 4 ? '' : $match[0];},
 $str);
return $str;
 }
 
 //对数据进行base64加减密
function base64($str,$ac=0){if(!$str){return $str;}
if($ac){return base64_encode($str);}
$is_base64=checkStringIsBase64($str);
if($is_base64==true){$s=base64_decode($str);}
if($s){return $s;}else{return $str;}
}

function checkStringIsBase64($str){
$temp=base64_encode(base64_decode($str));
if($str==$temp){return true;}else{return false;}
}
 
 

function save_file($path,$name,$str){//保存文件 save_file('目录'，‘文件名’,'字符');
if(substr($path,-1,1)!="/"){$path.='/';}
if(!file_exists($path)){mkdir($path,0700);}
$filename=$path.$name;
$handle=fopen($filename,"w");
if (fwrite($handle,$str)){return true;}else{return false;}
fclose($handle);
}
function check_path($path){
if(!file_exists($path)){mkdir($path,0700);}
}


function get_time($t){//获取时间
$x=explode(' ',$t);
if($x[0]){$h=explode("-",$x[0]);}else{$h=array();}
if($x[1]){$hh=explode(":",$x[1]);}else{$hh=array();}
return mktime($hh[0],$hh[1],$hh[2],$h[1],$h[2],$h[0]);
}


function dis($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){//获取2地经纬度的距离
if(!$latitude1 or !$latitude2){return 0;}
  $EARTH_RADIUS = 6370.996; // 地球半径系数
  $PI = 3.1415926;
  $radLat1 = $latitude1 * $PI / 180.0;
  $radLat2 = $latitude2 * $PI / 180.0;
  $radLng1 = $longitude1 * $PI / 180.0;
  $radLng2 = $longitude2 * $PI /180.0;
  $a = $radLat1 - $radLat2;
  $b = $radLng1 - $radLng2;
  $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
  $distance = $distance * $EARTH_RADIUS * 1000;
  if($unit==2){
    $distance = $distance / 1000;
  }
  return round($distance, $decimal);
}


function date_ff($d,$x=5){//获取时间距离现在 x为多少天后显示
$s=time()-$d;
if($s>0){
if($s<60){return $s.'秒前';}
$fen=floor($s/60);if($fen<60){return $fen.'分钟前';}
$h=floor($s/3600);if($h<24){return $h.'小时前';}
$day=floor($s/86400);if($day<$x){return $day.'天前';}
return date('Y年m月d日',$d);
}else{
$s=-$s;
if($s<60){return $s.'秒';}
$fen=floor($s/60);if($fen<60){return $fen.'分钟';}
$h=floor($s/3600);if($h<24){return $h.'小时';}
$day=floor($s/86400);if($day<$x){return $day.'天';}
return date('Y年m月d日',$d);
}
}


//数据推送
function post($url,$data,$heard='') {//获取微信认证
global $cfg;
  $html = curlPost($url,$data,$heard);
  $output = json_decode($html, true);
  return $output;
}

function send_post( $url, $post_data ) {//发送消息
  $options = array(
    'http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type:application/json',
      'content' => $post_data,
      'timeout' => 60
    )
  );
  $context = stream_context_create( $options );
  $result = file_get_contents( $url, false, $context );
  return $result;
}

function curlPost($Url, $PostRequest, $HTTP_headers)
{
   $ch=curl_init();
   curl_setopt($ch, CURLOPT_URL, $Url);
   // init
   curl_setopt($ch, CURLOPT_HEADER, 0);
   if($HTTP_headers){curl_setopt($ch, CURLOPT_HTTPHEADER, $HTTP_headers);}
   // headers
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // I don't want headers back from the serrver...
   curl_setopt($ch, CURLOPT_POST, 1) ;
   curl_setopt($ch, CURLOPT_POSTFIELDS, $PostRequest);
   // post data
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   $result = curl_exec($ch);
   curl_close($ch);
   return $result;
}


    //推算xml数据
function postXmlCurl($url,$xml,$second = 30){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            curl_close($ch);
            echo "curl 出错，错误码:$error"."<br>";
        }
    }

//数据处理
function arrayToXmlStr($arr) {   //封装xml 
ksort($arr);
foreach($arr as $k=>$v){
    if($v){
$h.='<'.$k.'>'.$v.'</'.$k.'>
';}
}
return '<xml>'.$h.'</xml>';
}

function xmlToArray($xml, $recursive = false )
{
    if (!$recursive){
        $array = simplexml_load_string($xml);
    } else  {
        $array = $xml;
    }

    $newArray = array ();
    $array = (array) $array ;
    foreach ($array as $key => $value ) {
        //$value = (array) $value ;
        if (isset ($value [0])){
            $newArray [$key] = trim($value [0]) ;
        } else {
            $newArray [$key] = xmlToArray($value, true) ;
        }
    }
    return $newArray;
}





function data_to_xml( $params ){//数据转xml
        if(!is_array($params)|| count($params) <= 0){return false;}
        $xml = "<xml>";
        foreach ($params as $key=>$val){
            if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
            }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
//xml转数据

   function xml_to_data($xml){
        if(!$xml){
            return false;
        }
        if (PHP_VERSION_ID < 80000) {
    libxml_disable_entity_loader(true);
}
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }
    


//网页函数
function page(){//输出页面
global $l,$page_num;
$page=g('page');if(!$page or !is_numeric($page)){$page=1;}
if(!$l and $page<2){return;}
if(!$l){$item_total=0;}else{$item_total=count($l);}

if($page<2 and $item_total!=$page_num){return;}

echo '<page>';
$u='./?'.url_get(['page']);
if(!$page){$page=1;}
if($page>1){echo '<a class=" m icon icon-back" href="'.$u.'page='.($page-1).'"></a>';}
$to=2;

if($item_total!=$page_num){$to=0;}

for($i=-2;$i<=$to;$i++){
$p=$page+$i;
if($p>0){
echo '<a class=" m '.($p==$page?'is_select':'').'" href="'.$u.'page='.$p.'">'.$p.'</a>';
}

}

if($item_total==$page_num and $item_total>0){
echo '<a class=" m icon icon-right" href="'.$u.'page='.($page+1).'"></a>';
}
echo '</page>';
}


?>