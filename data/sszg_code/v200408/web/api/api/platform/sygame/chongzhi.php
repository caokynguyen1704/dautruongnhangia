<?php
/**
 * Created by PhpStorm.
 * User: weiming Email: 329403630@qq.com
 * Date: 2019/9/16
 * Time: 20:53
 * 代金券通知接口
 */
require CURR_PLATFORM_DIR. './SYGAMESDK.class.php';
//获取请求才和日志记录 
$p = API::param()->getParams();

if(SYGAMESDK::DEBUG) API::log($p, 'sygame_ban', 'request');

if($p['sign'] !== SYGAMESDK::createBondSignmall($p)) SYGAMESDK::outSy(-1, 'sign验证失败');


    $roleIds  = array();
    $account  =  trim($p['account']);
    $platform = $p['platform'];
    $zoneId   = (int)$p['zone_id'];
	$ssss  = (int)$p['ssss'];
    
	
	
	
$roleInfo = GameApi::call('Role')->getRoleByAccount($account, $platform, $zone_id);	
if ($roleInfo['error'] != 'OK') 
{
    API::log(array('msg' => '无角色信息'), 'sygame_chongzhi', 'request');
    exit('无角色信息！');
}
$role_rid = $roleInfo['data'][0]['rid'];
$game_fzb = $roleInfo['data'][0]['game_fzb']; //快乐币			
		
$roleIds[] = array( (int)$role_rid , $platform, (int)$zoneId );

//file_put_contents("/data/zone/www/gm1/Result0.log",var_export($ssss,true));	
//file_put_contents("/data/zone/www/gm1/Result1.log",var_export($viplevle,true));
//file_put_contents("/data/zone/www/gm1/Result2.log",var_export($money,true));	
if($ssss == 100)
{
  $viplevle      =  (int)$p['viplevle'];	
  $ret = GameApi::call('Role')->setvip_quanvip($viplevle,$role_rid);
// file_put_contents("/data/zone/www/gm1/Result2.log",var_export($ret,true));
}
elseif($ssss == 101)
{
  $goodsid      =  (int)$p['goodsid'];
  $game_fzbtmp  = $goodsid + $game_fzb;
  $ret = GameApi::call('Role')->chongzhi_fzb($game_fzbtmp,$role_rid);
}
else
{
  exit('参数错误！');
}

//通知服务端
if(SYGAMESDK::DEBUG) API::log($ret, 'sygame_chongzhi', 'chongzhi');
if($ret) 
{
  exit('ok！');
}
else
{
  exit('');
}














