<?php
/**----------------------------------------------------+
 * 客户端处理
 * @author whjing2012@gmail.com
 +-----------------------------------------------------*/
class Misc extends App
{
    const CDEBUG = true;
    public function log(){
        if (!Misc::CDEBUG) exit("fail");
        $p = array_merge($_POST, $_GET);
        Api::log($p, 'client_log', 'request');
        exit( "ok" );
    }

    public function ios_log(){
        if (!Misc::CDEBUG) exit("fail");
        $p = array_merge($_POST, $_GET);
        Api::log($p, 'client_ios_log', 'request');
        exit( "ok" );
    }

    public function idfa_log(){
        if (!Misc::CDEBUG) exit("fail");
        $p = array_merge($_POST, $_GET);
        Api::log($p, 'client_idfa_log', 'request');
        exit( "ok" );
    }

    public function bingniao_token(){
        $p = array_merge($_POST, $_GET);
        $url = "https://token.aiyinghun.com/user/token";
        $key = "5687d666f2eb1c22aac9c5e07cbaf173";
        $gameId = Api::getVar('gameId'); //
        $appId = Api::getVar('appId'); //
        $channelId = Api::getVar('channelId'); //
        $extra = Api::getVar('extra'); //
        $sid = Api::getVar('sid'); //
        $sign = md5("appId=${appId}channelId=${channelId}extra=${extra}gameId=${gameId}sid=${sid}${key}");
        $p['sign'] = $sign;
        $ret = Api::callRemoteApi($url, $p);
        echo $ret['msg'];
        exit;
    }

    public function bingniao_get_token(){
        $p = array_merge($_POST, $_GET);
        $url = "https://token.aiyinghun.com/user/token";
        $key = "5687d666f2eb1c22aac9c5e07cbaf173";
        $gameId = Api::getVar('gameId'); //
        $appId = Api::getVar('appId'); //
        $channelId = Api::getVar('channelId'); //
        $extra = Api::getVar('extra'); //
        $sid = Api::getVar('sid'); //
        $sign = md5("appId=${appId}channelId=${channelId}extra=${extra}gameId=${gameId}sid=${sid}${key}");
        $p['sign'] = $sign;
        $ret = Api::callRemoteApi($url, $p);
        // echo $ret['msg'];
        // "ret":"{\"ret\":0,\"msg\":\"\\u6210\\u529f\",\"content\":{\"data\":{\"gameId\":\"120000106\",\"channelId\":\"17\",\"appId\":\"110000907\",\"userId\":\"BY_oaDTfjhhC3Ls63OQS--GnnzRSIVI\",\"sdkData\":{\"channelUid\":\"oaDTfjhhC3Ls63OQS--GnnzRSIVI\"},\"accessToken\":\"25_4yIv01EflRBnpd11XuZIq6hTxonWO2bKRKzx9T0UXzWJePmch2GniHgBMiplWZMFomrg9yNKI2p0H1xegc_JNPGh51cPh-UJZ7S_Rg5seLQ,wx,2,869209020741648\"},\"cData\":{\"msg\":\"User is logged in\",\"ret\":0}}}"
        // jsonObj.content.data.userId
        $retdata = json_decode($ret['msg'], true);
        if(!empty($retdata) && isset($retdata['content']) && isset($retdata['content']['data']) && isset($retdata['content']['data']['userId'])){
            $time = time();
            $retdata['sy_sign'] = md5("__sszg_2019__".$retdata['content']['data']['userId'].$retdata['content']['data']['accessToken']);
            $retdata['sy_sign2'] = md5("___sszg_2019___".$retdata['content']['data']['userId'].$retdata['content']['data']['accessToken'].$time);
            $retdata['sy_time'] = $time;
            $p['sy_sign'] = $retdata['sy_sign'];
            $p['sy_sign2'] = $retdata['sy_sign2'];
            $p['sy_time'] = $retdata['sy_time'];
            echo json_encode($retdata);
        }else{
            echo $ret['msg'];
        }
        $p['ret'] = $ret['msg'];
        Api::log($p, 'client_bingniao_token_log', 'request');
        exit;
    }

}
