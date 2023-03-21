<?php
/*-----------------------------------------------------+
 * 客户端相关工具
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class client{
    private static $instance = null;
    private $mainServer;
    private $gamebuilder;
    private $platform;
    private $logfile = "/var/client.log";
    private $logMake = "/var/client_make.log";
    private $logBuildClientResources = "/var/build_clientresources.log";
    private $logBuildGameResources = "/var/build_gameresources.log";

    private function __construct(){
        $this->logfile = ROOT.$this->logfile;
        $this->logMake = ROOT.$this->logMake;
        $this->logBuildGameResources = ROOT.$this->logBuildGameResources;
        $this->logBuildClientResources = ROOT.$this->logBuildClientResources;
    }

    private function checkPlatform(){
        if(!App::request()->get()->platform){
            throw new ErrorException("缺少必要参数platform");
        }
        $this->platform = App::request()->get()->platform;
    }

    private function checkGamebuilder(){
        $this->gamebuilder = App::cfg()->servers->main;
        if(!$this->gamebuilder){
            throw new ErrorException("找不到编译服务器gamebuilder的配置信息");
        }
    }

    private function checkMainServer(){
        $this->mainServer = App::cfg()->servers->main;
        if(!$this->mainServer){
            throw new ErrorException("找不到主服务器main的配置信息");
        }
    }

    // 当前脚本被载入时会自动调用此方法
    public static function __awake(){
        App::sess()->close();
    }

    private static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 写日志文件
    private static function writeLog($msg){
        error_log($msg, 3, self::getInstance()->logfile);
    }

    // 输出一条信息到控制台
    private static function info($msg){
        $name = App::sess()->username;
        $time = date("y/m/d H:i:s", time());
        self::writeLog(sprintf("[o0m[INFO][%s %s][0;0m %s", $time, $name, $msg));
    }

    private static function rsync(){
        $self = self::getInstance();
        $self->checkMainServer();
        $self->checkGamebuilder();

        self::info("正在同步资源，可能需要比较长的时间，请耐心等待...\n");

        $start = time();
        $cmd = "/usr/bin/sudo /usr/bin/rsync -a --times --delete -e 'ssh -p{$self->gamebuilder->ssh_port}' {$self->gamebuilder->ssh_user}@{$self->gamebuilder->ip}:{$self->gamebuilder->project_root}/resources/release/ ".ROOT."/var/res_cache/";
        $rtn = App::os()->exec($cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("同步资源时发生错误[$rtn]:\n$stderr\n");
            return;
        }

        $cmd = "/usr/bin/sudo /usr/bin/rsync -a --times -e 'ssh -p{$self->mainServer->ssh_port}' ".ROOT."/var/res_cache/ {$self->mainServer->ssh_user}@{$self->mainServer->ip}:{$self->mainServer->project_root}/resources/release/";
        $rtn = App::os()->exec($cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("同步资源时发生错误[$rtn]:\n$stderr\n");
            return;
        }

        self::info("同步资源完成，用时 ".(time() - $start)." 秒...\n");
    }

    // 读取日志
    public static function log(){
        $file = App::request()->get()->file;
        if(!$file){
            throw new ErrorException("缺少必要参数file");
        }

        $self = self::getInstance();

        switch($file){
        // default: throw new ErrorException("参数错误");
        case 'client_make': $file = $self->logMake; break;
        case 'game_resources': $file = $self->logBuildGameResources; break;
        case 'client_resources': $file = $self->logBuildClientResources; break;
	default:$file = ROOT."/var/$file"; break;
        }

        $view = App::view();
        if(file_exists($file)){
            $view->vars->content = Common::formatConsole(file_get_contents($file));
        }
        $view->render('console');
    }

    // 获取控制台内容
    public static function console(){
        $view = App::view();
        $view->vars->content = Common::formatConsole(Util::tail(self::getInstance()->logfile));
        $view->render('console');
    }

    // 显示主界面
    public static function index(){
        $srvs = [];
        $view = App::view();
        $view->vars->console = App::url('/client/console');
        $view->render('client');
    }

    // 生成数值数据
    public static function genData(){
        $files = App::request()->get()->files;
        if(!$files) return;
        self::info("正在生成配置数据数据...\n");
        Common::genData($files, self::getInstance()->logfile);
        self::info("生成配置数据完毕\n");
    }

    // 生成地图数据
    public static function genMap(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在生成地图数据...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh gen_map";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::info("生成地图数据失败[$rtn]:\n$stderr\n");
        }
        self::writeLog($stdout);
    }

    // 同步中心城数据
    public static function genCity(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在同步中心城数据...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli city";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::info("同步中心城数据失败[$rtn]:\n$stderr\n");
        }
        self::writeLog($stdout);
    }

    // 生成协议
    public static function proto(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在打包客户端协议...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh gen_proto";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("打包客户端协议时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);

        self::rsync();
    }

    // 生成APK热更新包
    public static function pack_apk_zip(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在打包客户端[APK]热更新包...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli make android skip && bash {$self->gamebuilder->project_root}/dev.sh cli pack android demo zip skip";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("打包客户端[APK]热更新包发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 生成IPA热更新包
    public static function pack_ipa_zip(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在打包客户端[IOS]热更新包...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli make ios skip && bash {$self->gamebuilder->project_root}/dev.sh cli pack ios demo zip skip";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("打包客户端[IOS]热更新包发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 生成APK
    public static function gen_apk(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        self::info("正在打包客户端[APK]...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh pack apk release";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("打包客户端[APK]发生错误[$rtn]:\n$stderr\n");
            return;
        }else if(file_exists("{$self->gamebuilder->project_root}/client_core/apk/game-release-signed.apk") && strstr($stdout, "build succeeded")){
            copy("{$self->gamebuilder->project_root}/client_core/apk/game-release-signed.apk", ROOT.'/www/apks/sy_game_'. date('YmdHis',time()).'.apk');
        }
        self::writeLog($stdout);
    }

    // 获取所有APK信息
    public static function apklist(){
        $path = ROOT.'/www/apks';
        $files = Common::listfiles($path, 'apk');
        $view = App::view();
        $view->vars->files = $files;
        $view->vars->ftype = 'apk'; 
        $view->render('apklist');
    }

    // 获取所有IPA信息
    public static function ipalist(){
        $path = ROOT.'/www/ipas';
        $files = Common::listfiles($path, 'ipa');
        $view = App::view();
        $view->vars->files = $files;
        $view->vars->ftype = 'ipa'; 
        $view->render('apklist');
    }

    // 编译GameResources
    public static function buildGameResources(){
        $self = self::getInstance();
        $self->checkPlatform();
        $self->checkGamebuilder();
        self::info("正在编译 {$self->platform} 版的GameResources，需要比较长的时间，请耐心等待...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh res build {$self->platform}";
        $start = time();
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译GameResources时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::info("编译 {$self->platform} 版的GameResources完成，用时: ".(time() - $start)." 秒，<a  href='javascript:void(0);' onclick=\"Util.winOpen('".App::getBaseUrl()."/client/log?file=game_resources', 960, 640, 'game_resources', true)\">点击此处可查看编译日志</a>\n");
        Util::writeFile($self->logBuildGameResources, $stdout);

        self::rsync();
    }

    // 编译ClientResources
    public static function buildClientResources(){
        $self = self::getInstance();
        $self->checkPlatform();
        $self->checkGamebuilder();
        self::info("正在编译 {$self->platform} 版的ClientResources，需要比较长的时间，请耐心等待...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli res {$self->platform}";
        $start = time();
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译GameResources时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::info("编译 {$self->platform} 版的ClientResources完成，用时: ".(time() - $start)." 秒，<a  href='javascript:void(0);' onclick=\"Util.winOpen('".App::getBaseUrl()."/client/log?file=client_resources', 960, 640, 'client_resources', true)\">点击此处可查看编译日志</a>\n");
        Util::writeFile($self->logBuildClientResources, $stdout);

        self::rsync();
    }

    // 生成版本信息
    public static function version(){
        $self = self::getInstance();
        $self->checkGamebuilder();

        $self::info("正在生成版本信息...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli version";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("生成版本信息时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);

        self::rsync();
    }

    // 编译客户端
    public static function make(){
        $self = self::getInstance();
        $self->checkPlatform();
        $self->checkGamebuilder();
        $releaseLocal = App::cfg()->client_pack_path;
        if(!$releaseLocal){
            self::writeLog("env.php中的没有client_pack_path的配置信息");
            return;
        }

        $rtn = App::git()->repo('client')->exec('log --format=%H%n%ct -n 1', $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("查询源码库时发生异常[$rtn]:\n$stderr\n");
            return;
        }
        list($hash, $ct) = explode("\n", $stdout);
        $ver = date('vymd_Hi', $ct);
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli make {$self->platform} {$ver} {$hash}";

        self::info("正在编译 {$self->platform} 客户端，需要比较长的时间，请耐心等待...\n");
        $start = time();
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译客户端时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::info("编译 {$self->platform} 客户端完成，用时: ".(time() - $start)." 秒，<a  href='javascript:void(0);' onclick=\"Util.winOpen('".App::getBaseUrl()."/client/log?file=client_make', 960, 640, 'client_make', true)\">点击此处可查看编译日志</a>\n");
        Util::writeFile($self->logMake, $stdout);


        self::info("正在同步客户端，请稍等...\n");

        $start = time();
        $cmd = "/usr/bin/sudo /usr/bin/rsync -a --times --delete-excluded --include '*.zip' --include '*.apk' --include '*.ipa' --exclude '*' -e 'ssh -p{$self->gamebuilder->ssh_port}' {$self->gamebuilder->ssh_user}@{$self->gamebuilder->ip}:{$self->gamebuilder->project_root}/resources/client_packages/ $releaseLocal/";
        $rtn = App::os()->exec($cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("同步客户端时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::info("正在同步客户端完成，用时: ".(time() - $start)." 秒\n");
    }

    // 获取客户端所有模块
    public static function getMods(){
        $path = App::cfg()->client_mod;
        $handle = @opendir($path);
        if(!$handle)throw new ErrorException("无法打开模块目录");
        $mods = ['all', 'launcher', 'core', 'supports'];
        while($file = readdir($handle)){
            if($file == '.' || $file == '..') continue;
            if(!is_dir($path.'/'.$file)) continue;
            $mods[] = $file;
        }
	sort($mods);
        return $mods;
    }

    // 编译模块
    public static function makeMod(){
        $mods = App::request()->get()->mods;
        if(!$mods) return;
        $self = self::getInstance();
        $self->checkGamebuilder();
        $modstr = $mods;
        $mods = explode(',', $mods);
        self::info("正在编译客户端模块[{$modstr}]，可能需要较长时间，请耐心等待....\n");
        $is_err = false;
        foreach($mods as $mod){
            if(!preg_match("/[0-9a-z_]+/i", $mod)) continue;
            $cmd = "bash {$self->gamebuilder->project_root}/dev.sh cli make_mod $mod";
            $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
            $str = preg_replace('/(Error:.*|Warning:.*)/', '<font color="red">[\1]</font>', $stdout);
            self::info("编译客户端模块[{$mod}]结果信息=> \n{$str}\n");
            if(0 != $rtn){
                self::writeLog("编译客户端时发生错误[$rtn]:\n$stderr\n");
                return;
            }
            if (preg_match ( "/.*(Error:|Warning:).*/", $str)) {
                $is_err = true;
            }
        }
        if($is_err){
            App::alert("编译模块有错，请认真检查");
        }
        self::info("编译客户端模块[{$modstr}]完成...\n");
    }

    public static function cmd(){
        // sleep(10);
        $self = self::getInstance();
        $self->checkGamebuilder();
        $cmdstr = App::request()->get()->cmd;
        if(!$cmdstr) return;
        if(!preg_match('/^[^\|\$\(\)`&><]+$/is', $cmdstr)) return App::alert("命令[$cmdstr]中包含有特殊字符, 执行失败");
        self::info("正在执行命令[./dev.sh $cmdstr]，某些命令需要处理比较长的时间...\n");
        $cmd = "bash {$self->gamebuilder->project_root}/dev.sh $cmdstr";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("执行命令失败[$rtn]:\n$stderr\n");
        }else{
            self::writeLog("执行结果：\n$stdout\n$stderr");
        }
        self::info("执行命令[./dev.sh $cmdstr]完毕\n");
    }

    public static function update_conf_tosvn(){
        $self = self::getInstance();
        $self->checkGamebuilder();
        self::info("开始执行命令[./dev.sh cli_update config]\n");
        $cmd = "sudo bash {$self->gamebuilder->project_root}/dev.sh cli_update config 1440";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        $stdout = preg_replace('/(error.*)/', '<font color="red">[\1]</font>', $stdout);
        if(0 != $rtn){
            self::writeLog("执行命令失败[$rtn]:\n$stdout $stderr\n");
	    self::info("执行命令[./dev.sh cli_update config]完毕\n");
        }else{
	    $time = time();
	    $logfile = "update_conf_svn_$time.txt";
	    file_put_contents(ROOT."/var/$logfile", "执行结果：\n$stdout\n$stderr");
	    self::info("执行命令[./dev.sh cli_update config]完毕 <a  href='javascript:void(0);' onclick=\"Util.winOpen('".App::getBaseUrl()."/client/log?file=$logfile', 960, 640, 'update_conf_log', true)\">查看日志</a>\n");
        }
    }

    public static function update_tosvn(){
        $self = self::getInstance();
        $self->checkGamebuilder();
        $type = App::request()->get()->type;
        $time = App::request()->get()->time;
        if(!$type || !$time) return;
        self::info("开始执行命令[./dev.sh cli_update $type $time]\n");
        $cmd = "sudo bash {$self->gamebuilder->project_root}/dev.sh cli_update $type $time";
        $rtn = SSH::exec($self->gamebuilder, $cmd, $stdout, $stderr);
        $stdout = preg_replace('/(error.*)/', '<font color="red">[\1]</font>', $stdout);
        if(0 != $rtn){
            self::writeLog("执行命令失败[$rtn]:\n$stdout $stderr\n");
	    self::info("执行命令[./dev.sh cli_update]完毕\n");
        }else{
            // self::writeLog("执行结果：\n$stdout\n$stderr");
	    $now = time();
	    $logfile = "update_svn_$now.txt";
	    file_put_contents(ROOT."/var/$logfile", "执行结果($type:$time)：\n$stdout\n$stderr");
	    self::info("执行命令[./dev.sh cli_update $type $time]完毕 <a  href='javascript:void(0);' onclick=\"Util.winOpen('".App::getBaseUrl()."/client/log?file=$logfile', 960, 640, 'update_conf_log', true)\">查看日志</a>\n");
        }
    }
    
}
