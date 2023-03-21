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

if(SYGAMESDK::DEBUG) API::log($p, 'sygame_pay_lili', 'request');

if($p['sign'] !== SYGAMESDK::createBondSignmall($p)) SYGAMESDK::outSy(-1, 'sign验证失败');


    $roleIds  = array();
    $account  =  trim($p['account']);
    $platform = $p['platform'];
    $zoneId   =(int)$p['zone_id'];
    $item     =(int)$p['item'];
	$itemnum  =(int)$p['itemnum'];   

    $itemArr = array();
    $itemArr[] = array($item, 1, $itemnum);
	
$roleInfo = GameApi::call('Role')->getRoleByAccount($account, $platform, $zone_id);	
if ($roleInfo['error'] != 'OK') 
{
    API::log(array('msg' => '无角色信息'), 'sygame_pay_lili', 'request');
    exit('无角色信息！');
}
$role_rid = $roleInfo['data'][0]['rid'];
file_put_contents("/data/sszg_code/v200408/web/api/api/platform/sygame/Result0.log",var_export($roleInfo,true));			
		
$roleIds[] = array( (int)$role_rid , $platform, (int)$zoneId );
		
file_put_contents("/data/sszg_code/v200408/web/api/api/platform/sygame/Result1.log",var_export($roleIds,true));	

$ret = GameApi::call('GM')->maill($roleIds, "GM邮件", "尊敬的冒险者大人，GM为您发送了一封邮件，请查收，祝您游戏愉快！" , $itemArr , "Black");
//通知服务端
if(SYGAMESDK::DEBUG) API::log($ret, 'sygame_pay_lili', 'maill');
switch ($ret['error']) {
    case 'SUCCESS':	
     exit($ret['msg']);	
	break;
    case 'MAIL_HANDLE_FAILURE':
     exit($ret['msg']);
	 break;
    case 'ORDER_HANDLE_FAILURE':
    default:
        exit('fail'); break;
}