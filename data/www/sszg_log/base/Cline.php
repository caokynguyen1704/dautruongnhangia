<?php
/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2016/2/27
 * Time: 14:14
 * 命令行公共函类
 */

class Cline {
    const DEBUG = true;
    const LOCAL_IP = '182.254.243.106';

    /**
     * 通过SSH在目标服务器上执行命令
     * @param string $ip ip地址
     * @param int $port 端口
     * @param $cmd
     * @param string $output_mode
     * @return string
     */
    public static function ssh($ip, $port, $cmd, $output_mode = "last")
    {
        if(self::DEBUG) {
            echo "[SSH] ssh -q -p{$port} -o StrictHostKeyChecking=no root@{$ip} \"$cmd\"\n";
        }
        $cmd = str_replace(array("\\", "\"", "`"), array("\\\\", "\\\"", "\`"), $cmd);
        $rtn = exec("ssh -q -p{$port} -o StrictHostKeyChecking=no root@{$ip} \"$cmd\"", $output, $return_value);
        if(255 == $return_value) {
            return "通过SSH访问服务器[{$ip}]时出现异常: 无法访问服务器";
        } elseif (0 != $return_value) {
            return "通过SSH访问服务器[{$ip}]时出现异常: 未知错误[$return_value]";
        }
        return self::out($output_mode, $rtn, $output);
    }

    /**
     * 通过SCP从本地复制文件到目标服务器
     * @param string $ip ip地址
     * @param int $port 端口
     * @param string | array $files 文件列表，需使用绝对路径
     * @param string $target 目标路径，需使用绝对路径
     * @param string $output_mode "last"|"full"|"line" 返回输出模式
     * @return string | array
     */
    public static function scp_to($ip, $port, $files, $target, $output_mode = "last")
    {
        if(is_array($files)){
            $files = implode(" ", $files);
        }
        $cmd = "scp -P{$port} $files root@{$ip}:$target";
        if(self::DEBUG) {
            echo "[SCP_TO] $cmd\n";
        }

        $rtn = exec($cmd, $output, $return_value);
        if(255 == $return_value) {
            return "复制文件到目标服务器[$ip]时出现异常: 无法访问服务器";
        } elseif (0 != $return_value) {
            return "复制文件到目标服务器[{$ip}]时出现异常，未知错误[$return_value]:\n$cmd";
        }
        return self::out($output_mode, $rtn, $output);
    }

    /**
     * 通过SCP从目标服务器复制文件到本地
     * @param string $ip ip地址
     * @param int $port 端口
     * @param string | array $files 远程文件路径，需使用绝对路径
     * @param string $local_path 本地目标路径，需使用绝对路径
     * @param string $output_mode "last"|"full"|"line" 返回输出模式
     * @return string
     */
    public static function scp_from($ip, $port, $files, $local_path, $output_mode = "last")
    {
        if(is_array($files)){
            $files = implode(" ", $files);
        }
        $cmd = "scp -P{$port} root@{$ip}:\"$files\" $local_path";
        if(self::DEBUG) {
            echo "[SCP_FROM] $cmd\n";
        }

        $rtn = exec($cmd, $output, $return_value);
        if(255 == $return_value){
            return("从服务器[{$ip}]复制文件到本地时出现异常: 无法访问服务器");
        }
        else if(0 != $return_value){
            return("从服务器[{$ip}]复制文件本地到时出现异常: 未知错误[$return_value]");
        }
        return self::out($output_mode, $rtn, $output);
    }

    /**
     * 执行系统命令
     * @param string $cmd 命令字串
     * @param string $output_mode "last"|"full"|"line" 返回输出模式
     * @return string | array
     */
    public static function cmd($cmd, $output_mode = 'last'){
        if(self::DEBUG) {
            echo "[EXEC] $cmd\n";
        }
        $rtn = exec("$cmd", $output, $return_value);
        if(0 != $return_value){
            return "执行系统命令时出现异常: 未知错误[$return_value]";
        }
        return self::out($output_mode, $rtn, $output);
    }

    private static function out($output_mode, $rtn, $output)
    {
        switch($output_mode) {
            case "last": return $rtn;
            case "full": return implode("\n", $output);
            case "line": return $output;
            default : return $rtn;
        }
    }

}