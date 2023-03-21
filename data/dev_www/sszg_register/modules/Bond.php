<?php
/**
 * Created by PhpStorm.
 * User: weiming Email: 329403630@qq.com
 * Date: 2019/9/16
 * Time: 19:53
 * 诗悦平台 通知代金券显示转发接口
 */

class Bond extends App
{
    /**
     * 海外官网获取服务器列表
     */
    public function syncBond()
    {
        //获取参数
        $time = Api::getVar('ts');
        $sign = Api::getVar('sign');
        $type = Api::getVar('type');
        $uid = Api::getVar('uid');
        $params = [
            'ts' => $time,
            'sign' => $sign,
            'type' => $type,
            'uid' => $uid
        ];
        Api::log($params, 'sync_bond', 'request');
        //密钥认证
        $sysTime = time();
        if($sign != self::createSign($params) || $sysTime - $time >= 60) self::out(-1, '验证失败');
        if((int)$type === 1 && !empty($uid)) {
            $roles = array_merge(
                Role::getRoles($uid, "1"),
                Role::getRoles($uid, "3"),
                Role::getRoles($uid, "6")
            );

            foreach ($roles as $k => $v) {
                if(empty($v)) continue;
                list($platform, $zoneId) = explode('_', $k);
                $host = $this->db->getRow("select id,platform, zone_id, host 
            from game_servers 
            where srv_status in(0, 2, 3) and platform in ('symlf') and zone_id = {$zoneId} order by open_time desc,zone_id desc");
                if(!empty($host)) {
                    $host['rid'] = $v['rid'];
                    $this->createNoticeData($host, $type);
                }
            }

        } else {
            $lists = $this->db->getAll("select id,platform, zone_id, zone_name,host 
            from game_servers 
            where srv_status in(0, 2, 3) and platform in ('symlf') order by open_time desc,zone_id desc");

            foreach ($lists as $k => $v) {
                if(empty($v)) continue;
                $this->createNoticeData($v, $type);
            }
        }

        self::out(0, 'success');
    }


    public function noticeBond()
    {
        $hosts = $this->db->getAll("select * from notice_bond");
        if(!empty($hosts)) {
            $time = time();
            foreach ($hosts as $k => $v) {
                $data = [
                    'rid' => $v['rid'],
                    'platform' => $v['platform'],
                    'zone_id' => $v['zone_id'],
                    'type' => $v['type'],
                    'time' => $time,
                    'sign' => md5($v['platform'] . $v['zone_id']. $v['type'] . $time . 'ZMIUClkQieIL7tElz7VSjVrT')
                ];
                $url = 'https://'.$v['host'].'/api.php/pf/sygame/bond/';
                $ret = Api::callRemoteApiNum($url, $data, 2);
                API::log($ret, 'notiec_bood', 'request');
                //返回数据解析，并且返回给客户端
                if(!$ret['result']){
                    exit('error:'.$ret['msg']);
                } else {
                    $code = json_decode($ret['msg'], true);
                    if((int)$code['code'] === 0){
                        $this->db->delete('notice_bond', ['id' => $v['id']]);
                    }
                }
            }
        }
    }

    private function createNoticeData($params, $type)
    {
        $data = [
            'id' => $params['id'],
            'rid' => $params['rid'] ?? 0,
            'host' => $params['host'],
            'platform' => $params['platform'],
            'zone_id' => $params['zone_id'],
            'type' => $type
        ];
        return $this->db->replace('notice_bond', $data);
    }

    /**
     * sign 验证
     * @param $params
     * @return string
     */
    public function createSign($params)
    {
        unset($params['sign'], $params['uid']);
        ksort($params);

        $secret = '7569f01569f175c82fc6c29ac5bbc526';
        $str_arr = array();
        foreach ($params as $key => $val) {
            $str_arr[] = rawurlencode($key) . "=" . rawurlencode($val);
        }

        $signStr = implode('&', $str_arr);
        return sha1($signStr . $secret);
    }

    /**
     * 接口信息返回
     * @param $code
     * @param $msg
     * @param array $data
     */
    private function out($code, $msg, $data = [])
    {
        echo json_encode(array('status' => $code, 'message' => $msg, 'data' => $data));
        exit;
    }

}