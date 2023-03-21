<?php
error_reporting(0);
$qu=trim($_POST['qu']);
$sqm=trim($_POST['sqm']);
$usr=trim($_POST['usr']);
$pay=trim($_POST['pay'] /10);
$bao = $_POST['bao'];
$num=trim($_POST['num']);
$xgb=trim($_POST['gb']);
$lx=trim($_POST['type']);
$qu == 0 && (die('Vui lòng chọn khu vực'));
include "config.php";
if($sqm!= $d_gmrz){die('Quá trình xác thực đã thất bại');}
$mysql = mysqli_connect($PZ['DB_HOST'],$PZ['DB_USER'],$PZ['DB_PWD'],$d_ssdb,$PZ['DB_PORT']) or die("Lỗi kết nối cơ sở dữ liệu");
$mysql->query('set names utf8');
if($lx==6){
    $item=trim($_POST['item']);
    $num=trim($_POST['num']);
    $item =='' && (die('Vui lòng chọn phần thưởng')); 
    $xx = mysqli_fetch_all($mysql->query("SELECT rid FROM role"));
    $n = count($xx) - 1;
    for ($i=0; $i<=$n; $i++) {
        $rids[$i][0] = (int)$xx[$i][0];
        $rids[$i][1] = $d_plat;
        $rids[$i][2] = (int)$d_zone;
    }
    $num =='' && ($num = 1); 
    $items = array(array((int)$item, 1, (int)$num));
    $ret = erls('adm', 'ec7ccd807e0ef', "[[{i,b,i}], b, b, [{i,i,i}], b]", array($rids,"System","Please accept attachments", $items, "GM"),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}
$usr =='' && (die('Vui lòng nhập tài khoản người chơi'));
$xx = mysqli_fetch_assoc($mysql->query("SELECT rid FROM role WHERE account = '$usr' limit 1"));
$xx['rid'] =='' && (die('Không có tài khoản người chơi như vậy'));
$rid = $xx['rid'];
$TS = Order_id();$DD = "GZ{$TS}";
$roles = array(array((int)$rid,$d_plat,(int)$d_zone));
$type = is_int($roles[0][0]) ? 'i' : 'b';
$type = implode(', ', array_fill(0, count($roles), "{{$type}, b, i}"));
if($lx == 0){
    $vip == 1 && (die('Vai trò này đã là một VIP')); 
	if($mysql->query("UPDATE role SET vip = 1 WHERE rid = '$rid';")){
		die('Tăng thành công VIP');
	}else{
		die('Không tăng được VIP');
	}
}elseif($lx == 1){
    $vip == 0 && (die('Vai trò này không phải là VIP và không cần phải xóa')); 
	if($mysql->query("UPDATE role SET vip = 0 WHERE rid = '$rid';")){
		die('VIP đã được xóa thành công');
	}else{
		die('Không xóa được VIP');
	}
}elseif($lx==2){
    $ret = erls('adm', 'ac6aaa9cc6f13b8689b12bc48d5lock', "[[{$type}], i, b, b, i]", array($roles, 31536000, 'Tắt tiếng', 'GM', 1),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;  
}elseif($lx==3){
    $ret = erls('adm', 'unsilent', "[[{$type}], b]", array($roles, 'GM'),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;  
}elseif($lx==4){
    $ret = erls('adm', 'bc234416c259bec', "[[{$type}], i, b, b]", array($roles, 31536000, '封号', 'GM'),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;  
}elseif($lx==5){
    $ret = erls('adm', 'unlock', "[[{$type}], b]", array($roles, 'GM'),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;  
}elseif($lx==7){
    $pay =='' && (die('Vui lòng nhập số tiền nạp'));
    $ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => 0,'package_name' =>"钻石");
    $ret = erls('adm', 'c234416c259bec7ccd807e0ef', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$pay,'VND',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($lx==8){
    $item=trim($_POST['item']);
    $num=trim($_POST['num']);
    $item =='' && (die('Vui lòng chọn phần thưởng')); 
    $item =='0' && (die('Vui lòng chọn loại mặt hàng'));
	$num =='' && ($num = 1); 
	$num > 1000000000 && ($num = 1000000000);
	$roles = array(array((int)$rid,$d_plat,(int)$d_zone));
    $items = array(array((int)$item, 1, (int)$num));
    $ret = erls('adm', 'ec7ccd807e0ef', "[[{i,b,i}], b, b, [{i,i,i}], b]", array($roles,"System","Please accept attachments", $items, "GM"),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($lx==10){
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
	if($find==false){die('Bạn không có quyền gửi gói hàng này');}
    $bao =='0' && (die('Vui lòng chọn loại gói'));
    $list = explode("_",$bao,2);
    $list[0] =='' && (die('Lỗi gói quà'));
    $ext = array('pay_channel' => "sszg",'channel' => "sszg",'package_id' => (int)$list[0],'package_name' =>"Gói nạp tiền");
    $ret = erls('adm', 'c234416c259bec7ccd807e0ef', "[i, i, b, i, b, i, b, i, b]", array((int)$rid,2,$d_plat,(int)$d_zone,$DD,(int)$list[1],'CNY',1,json_encode($ext)),$d_plat,$d_keys,$d_port);
    print_r($ret['message']);die;
}elseif($lx==9){
    $xgb =='' && (die('Vui lòng nhập số xu Hạnh phúc sẽ được thêm vào'));
    if($mysql->query("UPDATE role SET game_fzb = game_fzb + $xgb WHERE rid = '$rid' ;")){
		die('Tăng thành công');
	}else{
		die('Tăng thất bại');
	}
}

?>