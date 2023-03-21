<?php
return [
    // 项目根目录
    'project_root' => '/data/sszg_code/code',

    // 游戏配置数据目录
    'game_config' => '/data/sszg_code/code/docs/trunk/xml_gy',
    'game_core_config' => '/data/sszg_code/code/docs/trunk/xml_gy/core',
    'game_core_s_config' => '/data/sszg_code/code/docs/trunk/xml_gy/core_s',
    // 客户端模块目录
    'client_mod' => '/data/sszg_code/code/client/src/mod',

    // 服务器信息
    'servers' => [
        'main' => [
            'project_root' => '/data/sszg_srv/gy2',
            'host' => 'zsyz.dev',
            'ip' => '127.0.0.1',
            'ssh_port' => 2020,
            'ssh_user' => 'root',
            'note' => '主服务器'
        ],
    ],

    // 游戏节点信息(个人服务器)
    'gamenodes_private' => [
        [
            'name' => '本机',
            'ip' => '127.0.0.1',
            'platform' => 'local',
            'zone_id' => 1,
            'port' => 9001,
        ]
    ],

    // 游戏节点信息(公共服务器)
    'gamenodes' => [
        'zsyz_dev_1' => [
            'name' => '开发服1区',
            // 所在服务器(对应于App::cfg()->servers中的key)
            'server' => 'main',
            // 根目录
            'root' => '/data/zone/sszg_dev_1',
            // 对外域名
            'host' => 'zsyz.dev',
            // 游戏节点服务端口
            'port' => 9001,
            // 平台标识
            'platform' => 'dev',
            // 区号
            'zone_id' => 1,
            // 对应的erlang节点
            'erl' => [
                'nodename' => 'zsyz_dev_1@zsyz.dev',
                'cookie' => 'k35bz75vc881x',
            ],
            // 游戏节点通信密钥
            'key' => '3wo582cbbv1ervr37cbgu1',
        ],
    ],

    // 数据库配置
    'database' => [
        // 默认数据库
        'default' => [
            'driver' => 'mysql',
            'encode' => 'utf8',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => 'mx52.cn',
            'dbname' => 'mysql',
        ],
    ],

    // web api接口相关配置
    'web_api' => [
        'key' => '73STfjGTGuMdeQJg3jSwUGu9yXhI5ask',
        // ticket有效时长，单位:秒
        'ticket_lifetime' => 60,
    ],

    // 默认输出的header设置
    'header' => [
        // P3P隐私策略设置，解决IE中iframe跨域访问cookie/session的问题
        'P3P' => 'CP=CAO PSA OUR',
    ],

    // 这里设置需要通过ini_set()修改的参数
    // 注意:有些选项无法通过ini_set设置，具体请查询:
    // http://php.net/manual/zh/ini.list.php
    'ini_set' => [
        // 脚本的最大执行时间，单位:秒
        // 同时需要修改nginx的配置: fastcgi_read_timeout [时间]; 
        'max_execution_time' => 1800,
        'memory_limit' => '1024M',
        'date' => [
            // 时区
            'timezone' => 'Asia/Shanghai',
        ],
        'session' => [
            'auto_start' => true,
            // session保存存储路径
            // 建议使用内存虚拟的目录，以保证在大访问量时的效率
            'save_path' => ROOT.'/var/sess',
            // 防止cookie被js获取
            'cookie_httponly' => 1,
            // 设置session文件的失效时间，默认为6小时
            'gc_maxlifetime' => 21600,
            // session文件清除机率，默认为20%
            'gc_probability' => 20,
        ],
    ],

    // 用户信息
    'users' => [
        'yeahoo' => [
            'actived' => true,
            'group' => 'root',
            'password' => '111111',
        ],
        'admin' => [
            'actived' => true,
            'group' => 'root',
            'password' => '111111',
        ],
        'whjing' => [
            'actived' => true,
            'group' => 'root',
            'password' => '111111',
        ],
    ]
];
