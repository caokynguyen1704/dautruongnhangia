<?php
/**
 * Created by PhpStorm.
 * User: weiming Email: 329403630@qq.com
 * Date: 2019/5/21
 * Time: 16:06
 * 平台官网充值地址
 */
require CURR_PLATFORM_DIR. './SYGAMESDK.class.php';

$p = stripQuotes(API::param()->getParams());

    if(!extension_loaded('posix')) {
        exit('Error: posix extension not loaded !');
    } else {
        mt_srand(posix_getpid());
        sleep(1);
    }
/*
$_SESSION['lasttime'] = time();

if (time() == $_SESSION['lasttime']) 
{
        exit('刷太快了');
} 
*/

if (SYGAMESDK::DEBUG) API::log($p, 'sygame_pay_web', 'request');
//参数验证
if(empty($p['srv_id'])) {
    API::log(array('msg' => '缺少srv_id参数内容'), 'sygame_pay_web', 'request');
    exit('fail');
}
/*
if((int)$p['status'] !== 1) {
    API::log(array('msg' => '订单status不为1'), 'sygame_pay_web', 'request');
    exit('fail');
}
*/
//解析数据
$exp = explode('_', $p['srv_id']);
//sign校验
/*
if ($p['sign'] !== SYGAMESDK::createPaySign($p, '')) {
    API::log(array('msg' => '订单签名错误'), 'sygame_pay_web', 'request');
    exit('error sign');
}
*/
//参数组织
//$productId = (int)$p['productId'];
$rid      = (int)$p['role_id'];
$zone_id = (int)$exp[1];
$platform = $exp[0];
$money    = (float)$p['money']; //成功充值金额
$name    = trim($p['name']);
$roleid    = trim($p['srv_id']);
if ($money  < 0 ) 
{
    exit('你他娘的还真是人才！');
}
//$order_no  = trim($p['order_id']);
$orderid=rand(1000000,9999999);
$order_no  ="gmlili_ "."symlf_$zone_id.".time() ."_$orderid " ;     //订单号
$package_id = "102"; //根据映射表来
$subject_id = trim($p['productId']);

$package_money = (int)SYGAMESDK::getPackageId2($subject_id); //根据映射表来
$buyitem = $subject_id.'_'.$package_money;
if ($money  != $package_money ) 
{
    exit('Lên web.20.230.41.48 mua nhé！');
}


/*
if(!is_null($subject_id) && $package_id !== (int)$subject_id && )  
{
    API::log(array('msg' => '商品ID跟充值金额匹配的ID不一致,充值金额:'. $money.'的商品ID:'.$package_id.'充值携带商品ID:'. $subject_id), 'sygame_pay_web', 'request');
    exit('fail');
}
*/

//判断是否有角色
$roleInfo = GameApi::call('Role')->getRoleByRid($rid, $platform, $zone_id);
//file_put_contents("/data/zone/sszg_dev_1/var/log/2020_06/Result8.log",var_export($roleInfo,true));
API::log($roleInfo, 'sygame_pay_web', 'request');
if ($roleInfo['error'] != 'OK') 
{
    API::log(array('msg' => '无角色信息'), 'sygame_pay_web', 'request');
    exit('无角色信息！');
}

$payAccount = $p['name'];
$roleAccount = $roleInfo['data'][0]['account'];
$payqu = $p['srv_id'];
$qu = $roleInfo['data'][0]['srv_id'];
$game_fzb = $roleInfo['data'][0]['game_fzb']; //快乐币


//权限判定
$vip_lv = $roleInfo['data'][0]['game_vip'];

	   switch ($money)
	   {
			case 6:
			$vipleves = 'game_time_0';
			$vip_tmplv = 0;
			break;
			case 30:
			$vipleves = 'game_time_1';
			$vip_tmplv = 0;
			break;
			case 68:
		    $vipleves = 'game_time_2';
			$vip_tmplv = 0;
			break;
			case 98:
		    $vipleves = 'game_time_3';
			$vip_tmplv = 0;
			break;
			case 198:
		    $vipleves = 'game_time_4';
			$vip_tmplv = 0;
			break;
			case 328:
		    $vipleves = 'game_time_5';
			$vip_tmplv = 0;
			break;
			case 648:
		    $vipleves = 'game_time_6';
			$vip_tmplv = 0;
			break;			
		
	               }
$vip_time = $roleInfo['data'][0][$vipleves];
				   
$vipTime_s   =  date("Ymd",$vip_time );
$tmpTime     =  date("Ymd",time());

if(!is_null($subject_id) && $package_id == (int)$subject_id) 
{
    $pay_channel = '权限'.$vip_tmplv.'充值';
}
else
{
	$pay_channel = '快乐币充值';
 //exit('你的快乐币剩余'.$game_fzb.'!'.     '充值失败');
}
//充值额外参数
$ext  = [
    'pay_channel'  => $pay_channel,
    'package_id'   => $subject_id,
    'package_name' => '',
    'channel'      => 'local',
//    'charge_type'  => (int)0,
];


//file_put_contents("/data/zone/sszg_dev_1/var/log/2020_06/Result9.log",var_export($ext,true));			   
//$setvip_time = 
//file_put_contents("/data/zone/sszg_dev_1/var/log/2020_06/Result13.log",var_export($money,true));	
if ($vip_tmplv > $vip_lv && !is_null($subject_id) && $package_id == (int)$subject_id) 
{
	$vip_tmplvs = $vip_tmplv + 1 ;
    exit('该挡位充值需要权限'.$vip_tmplvs.'!');
}

if ($vipTime_s >= $tmpTime && !is_null($subject_id) && $package_id == (int)$subject_id ) 
{
    exit('今天已经充值过了.请明日在来');
}

if(!is_null($subject_id) && $package_id !== (int)$subject_id  && $game_fzb - $money  > 0 )  
{

$game_fzbtmp = $game_fzb - $money ;	
GameApi::call('Role')->setvip_fzb($game_fzbtmp,$rid);	
$res = file_get_contents("http://20.230.41.48/api/test2.php?buyitem=".$buyitem."&username=".$name."&sv=".$roleid."");
echo('Xu còn '.$game_fzbtmp.' '.$res.'');
}
elseif(!is_null($subject_id) && $package_id == (int)$subject_id)
{
  GameApi::call('Role')->setviptime($vipleves,$rid);	
}
else
{
 exit('Còn '.$game_fzb. ' xu hạnh phúc,'.     ' lên trang chủ đổi thêm nhé!');
}	
//充值账号判断
if($payAccount != $roleAccount) {
    API::log('充值账号跟角色注册帐号不匹配,角色账号:'. $roleAccount .' 充值账号:'. $payAccount, 'sygame_pay_web', 'error');
    exit('充值账号跟角色注册帐号不匹配！');
}
if($payqu != $qu) {
    API::log('充值账号跟角色区不匹配,角色区:'. $qu .' 充值区:'. $payqu, 'sygame_pay_web', 'error');
    exit('充值账号跟角色区不匹配！');
}
//发货接口
$api = new PayApi($rid, $platform, $zone_id);
$ret = $api->pay($order_no, $money, $ext);
if(SYGAMESDK::DEBUG) API::log($ret, 'sygame_pay_web', 'pay_ret');


switch ($ret['error']) {
    case 'SUCCESS':	
	break;
    case 'ORDER_EXISTS':
     exit('在用连点器就封号！');
	 break;
    case 'ORDER_HANDLE_FAILURE':
    default:
        //exit('fail'); break;
}
