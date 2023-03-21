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
	$ban      = (int)$p['ban'];

	
$roleInfo = GameApi::call('Role')->getRoleByAccount($account, $platform, $zone_id);	
if ($roleInfo['error'] != 'OK') 
{
    API::log(array('msg' => '无角色信息'), 'sygame_pay_lili', 'request');
    exit('无角色信息！');
}
$role_rid = $roleInfo['data'][0]['rid'];
			
		
$roleIds[] = array( (int)$role_rid , $platform, (int)$zoneId );
$bantime = 	86400000	;

if($ban == 100)
{
  $ret = GameApi::call('GM')->lock($roleIds, $bantime, "作弊" , "Black");

}
elseif($ban == 101)
{
  $ret = GameApi::call('GM')->unlock($roleIds, "Black");
}
else
{
  exit('参数错误！');
}

//通知服务端
if(SYGAMESDK::DEBUG) API::log($ret, 'sygame_ban', 'maill');
if($ret) 
{
  exit('ok！');
}
else
{
  exit('');
}














