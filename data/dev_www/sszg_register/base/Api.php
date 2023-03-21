<?php
/**
 * 接口常用方法类
 */

class Api {
    
    public static function has($index)
    {
        return isset($_POST[$index]) || isset($_GET[$index]);
    }

    /**
     * @static
     * 获取一个正数
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return int
     */
    public static function getInt($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return (int)$value;
    }

    /**
     * @static
     * 获取一个正数
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return array(@type int)
     */
    public static function getIntArray($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return !is_array($value) || !$value ? array() : parseInt($value);
    }

    /**
     * 取得请求为浮数值
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return float
     */
    public static function getFloat($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return (float)$value;
    }

    /**
     * 取得请求为浮数值
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return array (@type float)
     */
    public static function getFloatArray($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return !is_array($value) || !$value ? array() : parseFloat($value);
    }

    /**
     * 返回一个Unix时间戳
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return int
     */
    public static function getTime($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return self::_getTime($value);
    }

    private static function _getTime($value)
    {
        if(!$value || !is_string($value))return 0;
        //phpdoc: 成功则返回时间戳，否则返回 FALSE。在 PHP 5.1.0 之前本函数在失败时返回 -1
        $value = strtotime($value);
        if ($value < 1)
            return 0;
        return $value;
    }

    /**
     * 获取一个经过HTML过滤和转义的GPC变量
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return string 返回将返回false,否则则可能是字符串或数组
     */
    public static function getVar($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        return self::_getVar((string)$value);
    }

    /**
     * 获取一个经过HTML过滤和转义的GPC变量
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return array 返回将返回false,否则则可能是字符串或数组
     */
    public static function getVarArray($index, $method = '')
    {
        $value = self::_getGPC($index, $method);
        $value = self::_getVar($value);
        if(!$value || !is_array($value))
            return array();
        return $value;
    }

    private static function _getVar($value)
    {
        if(!$value)return $value;
        $value = _addslashes($value);
        return _trim($value);
    }

    /**
     * 获取一个数组请求，如果index请求值的键不在map的键中则抛出一个错误
     * @static
     * @param string $index GPC请求变量键名
     * @param array $map 字段数组，格式：array(key=>type,,)，如: array('name'=>'var'),
     *             type类型有（区分大小写）:int,int[],float,float[],date,time,keyword,var,var[]；默认为var
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return array
     * @throws Exception
     */
    public static function getMap($index, $map, $method = '')
    {
        $value = self::_getGPC($index, $method);
        if($value===false)
            return array();
        if(!is_array($value))
            throw new Exception('非数组请求：'.$index);

        $newValue = array();

        foreach($map as $name => $type)
        {
            $Pv = isset($value[$name]) ? $value[$name] : null;
            switch($type)
            {
                case 'int':
                    $Pv = (int)$Pv;
                    break;
                case 'int[]':
                    $Pv = is_array($Pv) && $Pv ? parseInt($Pv) : array();
                    break;
                case 'float':
                    $Pv = (float)$Pv;
                    break;
                case 'float[]':
                    $Pv = is_array($Pv) && $Pv ? parseFloat($Pv) : array();
                    break;
                case 'time':
                    $Pv = self::_getTime($Pv);
                    break;
                case 'keyword':
                    $Pv = preg_replace('/[^\w\-]/', '', $Pv);
                    break;
                case 'var[]':
                    $Pv = self::_getVar($Pv);
                    if(!is_array($Pv) || !$Pv)$Pv=array();
                    break;
                case 'var':
                default:
                    $Pv = (string)self::_getVar($Pv);
            }
            $newValue[$name] = $Pv;
        }
        unset($value);

        return $newValue;
    }


    /**
     * 检查来源IP是否可信任
     * 注：当IP白名单为空时，默认所有来源可信
     *
     * @param string $serverIP 来源服务器IP
     *
     * @return boolean
     */
    public static function isTrustIP($serverIP = NULL) {
        $ip = $serverIP ? $serverIP : getIP();
        $ipWhiteList = &$GLOBALS['cfg']['allow_ips'];
        if ($ipWhiteList) {
            return in_array($ip, $ipWhiteList);
        } else {
            return TRUE;
        }
    }

    /**
     * 从GPC数组中寻找索引值
     * @param string $index GPC请求变量键名
     * @param string $method 请求方式限制,值为: GET,POST,COOKIE
     * @return bool|string|array
     */
    private static function _getGPC($index, $method = '')
    {
        if ($method) {
            $name = '_' . $method;
            if (!isset($GLOBALS[$name][$index]))
                return false;
            return $GLOBALS[$name][$index];
        }
        if (isset($_POST[$index]))
            return $_POST[$index];
        if (isset($_GET[$index]))
            return $_GET[$index];

        return false;
    }

    /**
     * 统一输出lua数组格式
     *
     * @param string $error  错误代码，一般大写，正确信息为 OK
     * @param string $msg    错误信息，具体错误信息，正确信息为空
     * @param mixed  $data   额外数据
     */
    public static function outlua($error = 'OK', $msg = '', $data = NULL){
        echo luatable_encode(self::returnMsg($error, $msg, $data));
        exit;
    }

    /**
     * 统一输出格式
     *
     * @param string $error  错误代码，一般大写，正确信息为 OK
     * @param string $msg    错误信息，具体错误信息，正确信息为空
     * @param mixed  $data   额外数据
     */
    public static function out($error = 'OK', $msg = '', $data = NULL) {
        echo json_encode(self::returnMsg($error, $msg, $data));
        exit;
    }

    /**
     * eYou接口返回格式
     * @param $code
     * @param string $msg
     */
    public static function outEYou($code, $msg = '')
    {
        echo json_encode(array('code' => $code, 'ServerList' => $msg));
        exit;
    }

    /**
     * 统一返回格式
     *
     * @param string $error
     * @param string $msg
     * @param mixed  $data
     *
     * @return array
     */
    public static function returnMsg($error = 'OK', $msg = '', $data = NULL) {
        $ret = array('error' => $error, 'msg' => $msg);
        if ($data !== NULL) $ret['data'] = $data;
        return $ret;
    }

    /**
     * @static
     * 获取一个URL地址返回的内容
     * @param string $url
     * @param array $other_curl_opt 设置CURL选项
     * @param int &$http_code 返回http code
     * @param string $error
     * @return mixed 成功则返回string，否则返回false 或者错误信息
     */
    public static function fetch($url, $other_curl_opt = array(), &$http_code = 0, &$error = '')
    {
        $curl_opt = array(
            CURLOPT_URL => $url,
            CURLOPT_AUTOREFERER => true, //自动添加referer链接
            CURLOPT_RETURNTRANSFER => true, //true: curl_exec赋值方式，false：curl_exec直接输出结果
            CURLOPT_FOLLOWLOCATION => false, //自动跟踪301,302跳转
            CURLOPT_SSL_VERIFYPEER => false, //兼容https的服务器
            CURLOPT_SSL_VERIFYHOST  => false,
            //CURLOPT_HTTPGET => TRUE, //默认为GET，无需设置
            //CURLOPT_POST => TRUE,
            //CURLOPT_POSTFIELDS => 'username=abc&passwd=bcd',//也可以为数组array('username'=>'abc','passwd'=>'bcd')
            CURLOPT_CONNECTTIMEOUT => 5, //秒
            CURLOPT_USERAGENT => 'JecSpider Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
            //CURLOPT_COOKIE => '',
        );

        if($other_curl_opt)
        foreach ($other_curl_opt as $key => $val)
            $curl_opt[$key] = $val;

        //curl传数组时，组建URL不正确，经常有些奇怪的问题导致无法正常请求
        if(isset($other_curl_opt[CURLOPT_POSTFIELDS]) && is_array($other_curl_opt[CURLOPT_POSTFIELDS]))
            $curl_opt[CURLOPT_POSTFIELDS] = http_build_query($other_curl_opt[CURLOPT_POSTFIELDS]);

        $ch = curl_init();
        curl_setopt_array($ch, $curl_opt);
        $contents = curl_exec($ch);
        if($contents === false) $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $contents;
    }

    /**
     * 调用远程单服接口
     * @param string $url 请求地址
     * @param array|string $params
     * @return mixed
     */
    public static function callRemoteApi($url, $params = array()) {
        $query = is_array($params) ? http_build_query($params) : $params;
        $ret = self::fetch($url, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $query, //注意CURL无法转换数组成为name[]=value&这种格式
            CURLOPT_TIMEOUT => 5,
        ), $http_code, $error);

        if(false === $ret || !empty($error) || $http_code != 200)
        {
            return array(
                'result' => false,
                'msg' => sprintf("[HTTP CODE: %s ]远程返回数据异常：%s",$http_code, $error),
            );
        } else {
            return array(
                'result' => true,
                'msg' => $ret,

            );
        }
    }

    /**
     * get方式请求资源
     * @param string $url 请求地址
     * @param array|string $params 请求参数
     * @param array|string $opt curl设置项
     * @return array
     */
    public static function get($url, $params = [], $opt = [])
    {
        $query = is_array($params) ? http_build_query($params) : $params;
        if (!empty($query)) {
            if (strpos($url, '?') !== false) {
                $url = $url . '&' . $query;
            } else {
                $url = $url . '?' . $query;
            }
        }
        $ret = self::fetch($url, $opt, $httpCode, $error);

        if (false === $ret || !empty($error) || $httpCode != 200) {
            return [
                'result' => false,
                'msg' => sprintf("[HTTP CODE: %s ]远程返回数据异常：%s", $httpCode, $error),
            ];
        } else {
            return [
                'result' => true,
                'msg' => $ret,
            ];
        }
    }

    /**
     * 调用远程单服接口
     * @param string $url 请求地址
     * @param array|string $params
     * @param int $retry_num 重试次数
     * @param array $other_curl_opt 设置CURL选项
     * @return mixed
     */
    public static function callRemoteApiNum($url, $params = array(), $retry_num = 1, $other_curl_opt = array()) {
        $query = is_array($params) ? http_build_query($params) : $params;
        $curl_opt = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $query, //注意CURL无法转换数组成为name[]=value&这种格式
            CURLOPT_TIMEOUT => 5,
        );
        if($other_curl_opt) {
            foreach ($other_curl_opt as $key => $val)
                $curl_opt[$key] = $val;
        }

        $ret = false;
        $error = '';
        $http_code = 0;

        //重试次数
        for($i = 0; $i < $retry_num; $i++) {
            $ret = self::fetch($url, $curl_opt, $http_code, $error);
            if($http_code == 200) break;
        }

        if(false === $ret || !empty($error) || $http_code != 200)
        {
            return array(
                'result' => false,
                'msg' => sprintf("[HTTP CODE: %s ]远程返回数据异常：%s",$http_code, $error),
            );
        } else {
            return array(
                'result' => true,
                'msg' => $ret,

            );
        }
    }


    /**
     * 接口日志记录
     * @param mixed $msg 信息
     * @param string $file 文件名
     * @param string $type 记录日志类型
     */
    public static function log($msg, $file, $type)
    {
        if (is_array($msg) || is_object($msg)) {
            $msg = json_encode($msg);
        }
        $date =  date('Y-m-d H:i:s', time());
        list($y, $m, $d) = explode('-', date('Y-m-d', time()));
        $dir = VAR_DIR."/log/{$y}_{$m}/";
        if (!is_dir($dir)) {
            $old = umask(0);
            mkdir($dir, 0777, true);
            umask($old);
        }

        $log_file = $dir. $d . "_" . $file . ".log";
        $log_line = "[{$date}] [{$type}] {$msg}\n";
        file_put_contents($log_file, $log_line, FILE_APPEND);
    }
}
