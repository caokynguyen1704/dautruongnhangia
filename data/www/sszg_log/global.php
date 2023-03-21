<?php
/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2015/12/24
 * Time: 18:08
 * 基础方法
 */

function getIP()
{
    $clientIP = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown'))
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown'))
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //web在内网，即web在vpn里的情况
    elseif (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP'])
        $clientIP = $_SERVER['HTTP_CDN_SRC_IP'];
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
        $clientIP = $_SERVER['REMOTE_ADDR'];

    preg_match('/[\d\.]{7,15}/', $clientIP, $clientIPmatches);
    $clientIP = $clientIPmatches[0] ? $clientIPmatches[0] : '0.0.0.0';
    unset($clientIPmatches);

    return $clientIP;
}

function _addslashes($var, $force=false)
{
    if (get_magic_quotes_gpc() && !$force)
        return $var;

    if (is_array($var))
    {
        foreach($var as &$v)
        {
            $v = _addslashes($v, $force);
        }
        return $var;
    }

    return addslashes($var);

}

/**
 * 清理空格 - 支持数组
 * @param mixed $var
 * @return mixed
 */
function _trim($var)
{
    if (is_array($var))
        return array_map("_trim", $var);
    return trim($var);
}

/**
 * 递归创建所有不存在的目录
 * @param string $path 目标路径
 * @param int $mode 权限，安全方案：注意:文件夹必须带有x权限才能有读取权限,文件带有x权限则有执行权限
 * @return bool
 */
function _mkdir($path, $mode = 0744)
{
    if (is_dir($path))
        return true;
    //bool mkdir ( string $pathname [, int $mode = 0777 [, bool $recursive = false [, resource $context ]]] )
    return mkdir($path, $mode, true);
}

/**
 * 创建一个PHP文件
 * @param string $file 路径
 * @param mixed $var 变量
 * @return boolean 是否写入成功
 */
function makePhp($file, $var)
{
    $data = '';
    if (!$file)
        return false;
    $data .= "<?php\n//this file create by fswy_register " . date('Y-m-d H:i:s') .
        "\nreturn " . var_export($var, true) . ';';
    return file_put_contents($file, $data);
}


/**
 * 返回一个整数
 * @param mixed $var 字符串或数字或数组
 * @return int
 */
function parseInt($var)
{
    if (is_array($var))
        return array_map('parseInt', $var);
    return (int)$var;
}

/**
 * 返回一个浮点数 注:.9 这样方式将解析为0
 * @param mixed $var 字符串或数字或数组
 * @return float
 */
function parseFloat($var)
{
    if (is_array($var))
        return array_map('parseFloat', $var);
    return (float)$var;
}

/**
 * 数组转换成lua_table
 */
function luatable_encode($arr){
    $str = "{";
    foreach($arr as $k => $v){
        if(is_int($k)){
            $k = $k + 1;  // lua的下标是从1开始的
            $str .= "[$k] = ";
        }else{
            $str .= "['$k'] = ";
        }
        if(is_int($v)){
            $str .= $v;
        }elseif(is_array($v)){
            $str .= luatable_encode($v);
        }else{
            $str .= "[[$v]]";
        }
        $str .= ",\n";
    }
    $str .= "}";
    return $str;
}
