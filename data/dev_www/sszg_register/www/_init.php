<?php
/**
 *  初始化
 */

define('DEBUG', true);

// 错误报告设置
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}else{
    error_reporting(0);
    ini_set('display_errors', 0);
}

//系统目录结构
define('ROOT',        realpath(__DIR__ . '/../'));
define('BASE_PATH',   ROOT . '/base');
define('MOD_PATH',    ROOT . '/modules');
define('VAR_DIR',     ROOT . '/var');
define('WEB_DIR',     ROOT . '/www');

//基础方法
require ROOT . '/global.php';

// --------------------------------------------------------
// 配置
// --------------------------------------------------------
$GLOBALS['cfg'] = array(
    //秘钥
    'secret_key' => 'tP5^M1r(,<}nc52-<#.q>9m#2',
    // 数据库服务器信息
    'database' => array(
        'type' => 'mysql',
        'host' => 'localhost',
        'user' => 'root',
        'pwd' => 'mx52.cn',
        'port' => 3306,
        'charset' => 'utf8',
        'db_name' => 'dautruongnhangia',
        'socket' => '',
    ),

    //ip限制
    'allow_ips' => array(

    ),
);

date_default_timezone_set("Asia/Shanghai");
//设置自动包含路径
set_include_path(PATH_SEPARATOR . BASE_PATH . PATH_SEPARATOR . MOD_PATH . PATH_SEPARATOR . get_include_path());
//注册类自动加载方法
spl_autoload_register('class_loader');


/**
 * 类自动加载函数
 * @param string $class 类名
 */
function class_loader($class){
    if (class_exists($class) || interface_exists($class)) {
        return;
    }
    include $class.'.php';
}
