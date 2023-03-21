<?php
/*-----------------------------------------------------+
 * 默认公共配置
 +-----------------------------------------------------*/
//系统目录结构
define('ZONE_ROOT',             getenv('ROOT'));
define('VAR_DIR',               ZONE_ROOT.'/var');
define('WEB_DIR',               ZONE_ROOT.'/www');
define('LIB_DIR',               "{{code_path}}/{{ver}}/web");

$GLOBALS['cfg'] = array(
    'version' => '{{ver}}', //版本
    'platform' => '{{platform}}',
    'game_name' => '{{game_name}}',
    'server_id' => '{{platform}}_{{zone_id}}', //服务器ID    
    'server_name' => '{{zone_name}}',
    'server_host' => '{{ip}}', //服务器地址
    'server_port' => {{port}}, //端口 8800
    'gateway_num' => 1, //网关数
    'server_url' => 'http://{{host}}', //web服务器url地址

    'server_key' => '{{srv_key}}',  //服务器使用的加密串
    'open_datetime' => '{{open_time}}',   // 开服时间
    
    'pay_allow_ips' => array(
        {{pay_allow_ips}}
    ),	//允许的充值数据来源的ip, 为空则没有限制
    //ERLANG服务器相关设定
    'erl' => array(
        'cookie' => '{{cookie}}',
        'node' => '{{nodename}}', //其它线路会自动获取
    ),
    //时区
    'timezone' => 'Asia/Shanghai',
    //ticket生命，单位：秒
    'ticket_lifetime' => 300,
    //会话失效时间
    'session_timeout' => 7200,

    //支付相关配置
    'pay' => array(
        'key' => '{{pay_key}}', //充值Key
        'url' => '{{pay_link}}', //充值地址
    ),
    //数据库服务器信息
    'database' => array(
        'driver' => 'mysql',
        'encode' => 'utf8',
        'host' => '{{db_host}}',
        'user' => '{{db_user}}',
        'pass' => '{{db_pass}}',
        'dbname' => '{{db_name}}'
    ),
    //用于验证的一些正则表达式
    're' => array(
        //角色名规则
        'name' => '/^[a-z0-9_]{3,20}$/'
    ),
);
