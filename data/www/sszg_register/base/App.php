<?php
/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2015/12/28
 * Time: 11:48
 * 基类
 */

class App {
    protected $db;

    /**
     * 一天时间总秒数
     */
    const DAY_SEC = 86400;


    public  function __construct()
    {
        $this->db = Db::getInstance();

    }

    /**
     * 通过渠道ID查询 平台标志
     * @param $channel_id
     * @return array
     */
    public function getPlatformByChannelId($channel_id)
    {
        $pf = [];
        $group = require VAR_DIR . '/platform.cache.php';
        foreach($group as $p=>$gp) {
            if(in_array($channel_id, $gp)) {
                $pf[] = $p;
            }
        }

        return $pf;
    }

    /**
     * 获取渠道开始时间（用来过滤服务器列表）
     * @param $channel_id
     * @return int
     */
    public function getPlatformSt($channel_id)
    {
        $platform = require VAR_DIR . '/channel.cache.php';

        return isset($platform[$channel_id]['start_time']) ? strtotime($platform[$channel_id]['start_time']) : 0;
    }
    
    public function isWhiteIp()
    {
        $ip = getIP();
        $white_ip = require VAR_DIR . '/white_ip.cache.php';
        return in_array($ip, $white_ip) ? true : false;
    }
    
    public function isWhiteAccount($account)
    {
        $account_file = VAR_DIR . '/white_account.cache.php';
        if (file_exists($account_file)) {
            $white_acc = require $account_file;
            return in_array($account, $white_acc) ? true : false;
        }
        return false;
    }

    /**
     * 获取最后登陆服务器
     * @param array $roles 角色信息数组
     * @return string
     */
    public static function getLastLoginSrv($roles)
    {
        $last_srv_id = '';
        if(empty($roles) || !is_array($roles)) return $last_srv_id;
        $max_time = 0;
        foreach ($roles as $srv_id=>$role) {
            if($role['login_time'] > $max_time) {
                $max_time = $role['login_time'];
                $last_srv_id = $srv_id;
            }
        }
        return $last_srv_id;
    }
}
