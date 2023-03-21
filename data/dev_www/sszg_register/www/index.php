<?php
/**
 *  入口文件
 */

//初始化当前环境
require '_init.php';

/**
 * 映射到对应的接口文件
 * index.php/server/list/?a=1&b=3     => server模块list方法
 */
if (!isset($_SERVER['PATH_INFO'])) Api::out(-10, '500:内部错误');
$pathInfo = explode('/', trim(str_replace('index.php', '', $_SERVER['PATH_INFO']), '/'));
if (count($pathInfo) != 2) Api::out(-20, '500:内部错误');

$className = ucfirst($pathInfo[0]);
$funcName  = $pathInfo[1];
if (!$className || !$funcName || preg_match('#[^\w]#', $className) || preg_match('#[^\w]#', $funcName))
    Api::out(-12, '非法的模块调用');
$classFile = MOD_PATH.'/'.$className.'.php';
if(!is_file($classFile)) Api::out(-40, '404:找不到文件');

$mod = new $className();
$ret = $mod->$funcName();
Api::out(0, $ret);

