<?php
/*-----------------------------------------------------+
 * 开发工具web接口
 * 当发现工作异常请查看日志:ROOT/var/error.log
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
define('DEBUG', true);

// 建议ROOT常量由web服务器中的配置文件指定
define('ROOT', str_replace('\\', '/', __DIR__));
define('VAR_DIR', ROOT.'/var');
require ROOT.'/../framework/App.php';

App::register('view', 'View');
App::register('git', 'Git');
App::register('os', 'OS');
App::map('alert', function($msg){
    echo '<script type="text/javascript">alert("' . $msg . '");</script>';
});

// 注册数据库驱动
$cfg = App::cfg()->database->default;
App::register('db', 'DB', [
    "{$cfg->driver}:host={$cfg->host};dbname={$cfg->dbname}",
    $cfg->user,
    $cfg->password
]);

// 设置默认url路由
App::route('/tools/upload_file', ['tools', 'upload_file']);
App::route('/tools/web_gen_data', ['tools', 'web_gen_data']);
App::route('/tools/web_gen_data_cfg', ['tools', 'web_gen_data_cfg']);
App::route('/tools/web_gen_xml', ['tools', 'web_gen_xml']);
App::route('/tools/web_get_beam', ['tools', 'web_get_beam']);
App::route('/tools/web_get_luac', ['tools', 'web_get_luac']);
App::route('/tools/web_gen_data_demo', ['tools', 'web_gen_data_demo']);
App::route('/tools/web_get_demo_erl', ['tools', 'web_get_demo_erl']);
App::route('/tools/web_get_demo_lua', ['tools', 'web_get_demo_lua']);
App::route('/tools/web_get_dev_beam', ['tools', 'web_get_dev_beam']);
App::route('/tools/web_get_dev_luac', ['tools', 'web_get_dev_luac']);
App::route('/captcha', ['user', 'captcha']);
App::route('/login', ['user', 'login']);
App::route('/logout', ['user', 'logout']);
App::route('/info', function(){ phpinfo(); });
App::route('/test', function(){
    echo 'test';
});

// 在未登录的情况下，所有url都转入登录界面
if(empty(App::sess()->username)){
    App::route('*', ['user', 'login']);
}else{
    App::route('/', ['release', 'index']);
    App::route('/release', ['release', 'index']);
    App::route('/release/diff', ['release', 'diff']);

    App::route('/log', ['log', 'index']);
    App::route('/log/query', ['log', 'query']);

    App::route('/server', ['server', 'index']);
    App::route('/server/log', ['server', 'log']);
    App::route('/server/console', ['server', 'console']);
    App::route('/server/make', ['server', 'make']);
    App::route('/server/make_cfg', ['server', 'make_cfg']);
    App::route('/server/hotswap', ['server', 'hotswap']);
    App::route('/server/upsvn', ['server', 'upsvn']);
    App::route('/server/gen/data', ['server', 'genData']);
    App::route('/server/gen/map', ['server', 'genMap']);
    App::route('/server/gen/battle', ['server', 'genBattle']);
    App::route('/server/start', ['server', 'start']);
    App::route('/server/stop', ['server', 'stop']);
    App::route('/server/status', ['server', 'status']);
    App::route('/server/cmd', ['server', 'cmd']);
    App::route('/server/data_check', ['server', 'data_check']);
    App::route('/server/holiday_cfg', ['server', 'holiday_cfg']);

    App::route('/client', ['client', 'index']);
    App::route('/client/log', ['client', 'log']);
    App::route('/client/console', ['client', 'console']);
    App::route('/client/gen/data', ['client', 'genData']);
    App::route('/client/gen/map', ['client', 'genMap']);
    App::route('/client/gen/city', ['client', 'genCity']);
    App::route('/client/gen/proto', ['client', 'proto']);
    App::route('/client/gen/version', ['client', 'version']);
    App::route('/client/build/game_resources', ['client', 'buildGameResources']);
    App::route('/client/build/client_resources', ['client', 'buildClientResources']);
    App::route('/client/build/all_resources', ['client', 'buildAllResources']);
    App::route('/client/make', ['client', 'make']);
    App::route('/client/make_mod', ['client', 'makeMod']);
    App::route('/client/cmd', ['client', 'cmd']);
    App::route('/client/update_conf_tosvn', ['client', 'update_conf_tosvn']);
    App::route('/client/update_tosvn', ['client', 'update_tosvn']);
    App::route('/client/gen_apk', ['client', 'gen_apk']);
    App::route('/client/apklist', ['client', 'apklist']);
    App::route('/client/ipalist', ['client', 'ipalist']);
    App::route('/client/pack_apk_zip', ['client', 'pack_apk_zip']);
    App::route('/client/pack_ipa_zip', ['client', 'pack_ipa_zip']);


    App::route('/makedata', ['makedata', 'index']);
    App::route('/makedata/gen_file', ['makedata', 'gen_file']);

    App::route('/diffdata', ['diffdata', 'index']);
    App::route('/diffdata/clifile', ['diffdata', 'cli_index']);
    App::route('/diffdata/differl', ['diffdata', 'differl']);
    App::route('/diffdata/difflua', ['diffdata', 'difflua']);
}
