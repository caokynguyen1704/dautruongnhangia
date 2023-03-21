<?php
error_reporting(0);
$qu = $_POST['qu'];
$usr = $_POST['usr'];
$pay=trim($_POST['pay'] /10);
$bao = $_POST['bao'];
$num = $_POST['num'];
$item = $_POST['item'];
$item2 = $_POST['item2'];
$gnxz = $_POST['gnxz'];
$xgb= $_POST['gb'];
$lx=trim($_POST['type']);
$qu == 0 && (die('Chọn máy chủ'));
$usr =='' && (die('Xin điền vào tài khoản'));
include "config.php";
$mysql = mysqli_connect($PZ['DB_HOST'],$PZ['DB_USER'],$PZ['DB_PWD'],$d_ssdb,$PZ['DB_PORT']) or die("Kho số liệu kết nối sai lầm");
$mysql->query('set names utf8');
$xx = mysqli_fetch_assoc($mysql->query("SELECT rid,vip FROM role WHERE account = '$usr' limit 1"));
$xx['rid'] =='' && (die('Máy chủ chưa được kích tool vui lòng liên hệ để mua'));
$xx['vip'] != 1 && (die('Tài khoản chưa là vip liên hệ page để mua'));
$rid = $xx['rid'];$TS = Order_id();$DD = "GZ{$TS}";
if($gnxz==1){
    $pay =='' && (die('Điền vào số lượng nạp'));
    $ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => 0,'package_name' =>"钻石");
    $ret = erls('adm', 'c234416c259bec7ccd807e0ef', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$pay,'CNY',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($gnxz==2){
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
	if($find==false){die('Không có quyền gửi gói này');}
    $bao =='0' && (die('请选择礼包种类'));
    $list = explode("_",$bao,2);
    $list[0] =='' && (die('礼包错误'));
    $ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => (int)$list[0],'package_name' =>"充值礼包");
    $ret = erls('adm', 'c234416c259bec7ccd807e0ef', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$list[1],'CNY',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($gnxz==3){
    $item=trim($_POST['item']);
    $num=trim($_POST['num']);
    $item =='' && (die('Vui lòng chọn phần thưởng')); 
    $item =='0' && (die('Vui lòng chọn loại mặt hàng'));
	$num =='' && ($num = 1); 
	$num > 10000000000 && ($num = 10000000000);
	$roles = array(array((int)$rid,$d_plat,(int)$d_zone));
    $items = array(array((int)$item, 1, (int)$num));
    $ret = erls('adm', 'ec7ccd807e0ef', "[[{i,b,i}], b, b, [{i,i,i}], b]", array($roles,"System","Please accept attachments", $items, "GM"),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($gnxz==4){
    $item =='0' && (die('请选择需要删除的物品'));
	$find=false;
	$file = fopen("item.txt", "r");
	while(!feof($file)){
		$line=fgets($file);
		$txts=explode('|',$line);
		if(trim($txts[0]) == trim($item)){
			$find=true;
			break;
		}		  
	}
	fclose($file);
	if($find==false){die('此物品您无权选择');}
	$num =='' && ($num = 1); 
	$num > 10000000000 && ($num = 10000000000);
	$info  = array($d_plat, (int)$d_zone, (int)$rid, (int)$item, 2, (int)$num);
    $ret = erls('adm', 'adm_ditmenochu', "[b,i,i,i,i,i]",$info,$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($gnxz==5){
    $item2=trim($_POST['item2']);
    $num=trim($_POST['num']);
    $item2 =='' && (die('Vui lòng chọn phần thưởng')); 
    $item2 =='0' && (die('Vui lòng chọn loại mặt hàng'));
	$num =='' && ($num = 1); 
	$num > 100 && ($num = 1);
	$roles = array(array((int)$rid,$d_plat,(int)$d_zone));
    $items = array(array((int)$item, 1, (int)$num));
    $ret = erls('adm', 'ec7ccd807e0ef', "[[{i,b,i}], b, b, [{i,i,i}], b]", array($roles,"System","Please accept attachments", $items, "GM"),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
die('未选取功能');
}elseif($gnxz==7){
    $item2=trim($_POST['item']);
    $num=trim($_POST['num']);
    $item2 =='' && (die('Vui lòng chọn phần thưởng')); 
    $item2 =='0' && (die('Vui lòng chọn loại mặt hàng'));
	$num =='' && ($num = 1); 
	$num > 10000000000 && ($num = 10000000000);
	$roles = array(array((int)$rid,$d_plat,(int)$d_zone));
    $items = array(array((int)$item, 1, (int)$num));
    $ret = erls('adm', 'ec7ccd807e0ef', "[[{i,b,i}], b, b, [{i,i,i}], b]", array($roles,"System","Please accept attachments", $items, "GM"),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;

}elseif($gnxz==9){
    $xgb =='' && (die('Vui lòng nhập số xu Hạnh phúc sẽ được thêm vào'));
    if($mysql->query("UPDATE role SET game_fzb = game_fzb + $xgb WHERE rid = '$rid' ;")){
		die('Tăng thành công');
	}else{
		die('Tăng thất bại');
	}
}
die('Chức năng chưa hoàn thiện');
?>