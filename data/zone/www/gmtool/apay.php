<?php
error_reporting(0);
header('Content-type: text/json;charset=utf-8');
$usr = $_GET['role_id'];
$fu = explode("_",$_GET['srv_id']);
$qu = $fu[1];
$bao = $_GET['productId'].'_'.$_GET['money'];
$sign = $_GET['sign'];
$md5 = md5("Post".$_GET['srv_id'].$usr.$_GET['money'].$_GET['productId']."Cc");
$sign != $md5 && (die('url error'));
$usr =='' && (die('rid error'));
include "config.php";
$list = explode("_",$bao,2);
$list[0] =='' && (die('pid error'));
$list[1] =='' && (die('pid error'));
$find=false;
$file = fopen("libao.txt", "r");
while(!feof($file)){
	$line=fgets($file);
	$txts=explode('|',$line);
	if(trim($txts[0]) == trim($bao)){
		$find=true;
		break;
	}		  
}
fclose($file);
if($find==false){die('pid no find');}
$mysql = mysqli_connect($PZ['DB_HOST'],$PZ['DB_USER'],$PZ['DB_PWD'],$d_ssdb,$PZ['DB_PORT']) or die("server error");
$mysql->query('set names utf8');
$xx = mysqli_fetch_assoc($mysql->query("SELECT rid,rmb,vip FROM role WHERE rid ='$usr' limit 1"));
$xx['rid'] =='' && (die('rid no find')); 
$rid = $xx['rid'];
$gb = $xx['rmb'];
$vip = $xx['vip'];
if($vip==1){
    $TS = Order_id();$DD = "GZ{$TS}";
    $ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => (int)$list[0],'package_name' =>"充值礼包");
    $ret = erls('adm', 'paytiendine', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$list[1],'CNY',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
    if($ret['message']=='Thành công'){
		die('Hội viên người sử dụng miễn phí mua thành công');
    }else{
		die('购买失败');
    }
}else{
if($gb < $list[1]){die('角色'.$d_name.'不足,当前'.$d_name.': '.$gb);}
$xed = $gb - $list[1];
$TS = Order_id();$DD = "GZ{$TS}";
$ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => (int)$list[0],'package_name' =>"充值礼包");
$ret = erls('adm', 'paytiendine', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$list[1],'CNY',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
if($ret['message']=='Thành công'){
    if($mysql->query("UPDATE role SET rmb =  $xed WHERE rid = '$rid' ;")){
		die('购买成功,剩余'.$d_name.': '.$xed);
	}else{
		die('购买失败');
	}
}else{
	die('购买失败');
}
}
?>