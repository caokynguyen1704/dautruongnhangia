<?php
/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2015/12/24
 * Time: 16:00
 * 角色模块
 */

class Role extends App
{

    /**
     * 获取角色信息
     * @param $account
     * @return array
     */
    public static function getRoles($account, $acc_group)
    {
        if(empty($account)) return [];
        $file = self::getAccountFile($account, $acc_group);
        // $file =  VAR_DIR.'/acc/'.self::getAccountPath($account).'/'.md5($account).'.php';
        if(is_file($file)) {
            $info = require $file;
            if(is_array($info)){
               return $info;
            }else{
                return [];
            }
        } else {
            return [];
        }
        /*例子
         return [
            'dev_1' => [
                ['rid'=>10, 'name'=>'张三', 'lev'=>2, 'class'=>3, 'sex'=>1],
                ['rid'=>16, 'name'=>'张五', 'lev'=>20, 'class'=>1, 'sex'=>1],
            ],
            'beta_1' => [
                ['rid'=>10, 'name'=>'李四', 'lev'=>30, 'class'=>3, 'sex'=>2]
            ]
        ];*/
    }

    public static function test_get_account_roles()
    {
        $account = Api::getVar('account'); //角色账号
        $acc_group = Api::getVar('acc_group'); //角色账号
        $file = self::getAccountFile($account, $acc_group);
        if(is_file($file)) {
            $info = require $file;
            if(is_array($info)){
                $info['file'] = $file;
                exit(json_encode($info));
            }else{
                echo $file;
                exit("false");
            }
        }else{
            exit("false");
        }
    }

    public static function is_new_account()
    {
        $account = Api::getVar('account'); //角色账号
        $acc_group = Api::getVar('acc_group'); //角色账号
        $file = self::getAccountFile($account, $acc_group);
        if(is_file($file)) {
            exit("false");
        }else{
            exit("true");
        }
    }

    /**
     * 设置账号信息 （添加，更新）
     */
    public static function set()
    {
        $account = Api::getVar('account'); //角色账号
        $acc_group = Api::getVar('acc_group'); //角色账号
        $role = stripslashes(Api::getVar('role')); //角色信息json格式
        $sign = Api::getVar('sign'); //md5(account+role+key)
        if($sign !== md5($account.$role.$GLOBALS['cfg']['secret_key'])) Api::out(1, 'sign error');

        $ret = self::setAccountPath($account, VAR_DIR.'/acc_'.$acc_group);
        if($ret) {
            $file = self::getAccountFile($account, $acc_group);
            if(is_file($file)) {
                $info = require $file;
                if(!is_array($info)){
                    $info = [];
                }
            } else {
                $info = [];
            }
            //新角色信息
            $role_info = json_decode($role, true);
            $srv_id = $role_info['srv_id'];
            unset($role_info['srv_id']);

            $info[$srv_id] = $role_info;
            $make_ret = makePhp($file, $info);
            if($make_ret === false) {
                Api::out(2, '创建php文件失败');
            } else {
                Api::out(0, '成功');
            }
        } else {
            Api::out(3, '创建账号目录失败');
        }


    }

    /**
     * 获取账号文件
     * @param $acc
     * @return string
     */
    private static function getAccountFile($acc, $acc_group)
    {
        $file = VAR_DIR.'/acc_'.$acc_group.'/'.self::getAccountPath($acc, true).'/'.md5($acc).'.php';

        if(is_file($file)) {
            return $file;
        } else {
            return VAR_DIR.'/acc_'.$acc_group.'/'.self::getAccountPath($acc).'/'.md5($acc).'.php';
        }
    }

    /**
     * 获取账号目录
     *
     * @param int $acc 账号
     * @return string 账号信息目录
     */
    private static function getAccountPath($acc, $old = false)
    {

        $crc = sprintf('%u', crc32($acc));
        if($crc > PHP_INT_MAX) //32位是 2147483647
        {
            $acc_id = substr($crc, 0, 9);
        } else {
            $acc_id = sprintf("%09d", $crc);
        }

        $file = substr($acc_id, 0, 3);

        if($old) {
            $dir1 = substr($acc_id, 0, 3);
            $dir2 = substr($acc_id, 3, 2);
            $dir3 = substr($acc_id, 5, 2);
            $file = $dir1 . '/' . $dir2 . '/' . $dir3;
        }

        return $file;
    }

    /**
     *创建用户账号目录
     *
     * @param int $acc 用户id
     * @param string $dir 起始目录
     * @return bool
     */
    private static function setAccountPath($acc, $dir='.')
    {
        $path = self::getAccountPath($acc);
        return _mkdir($dir.'/'.$path, 0755);
    }

    /*
     * 白名单判断
     */
    public function isWhite(){
        // $account = Api::getVar('account'); //角色账号
        $ip = Api::getVar('ip'); //IP
        $white_ip = require VAR_DIR . '/white_ip.cache.php';
        if(in_array($ip, $white_ip)){
            exit("ok");
        }
        exit("false");
    }
}
