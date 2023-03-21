<?php
/*-----------------------------------------------------+
 * 用户登录处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class server{
    private static $instance = null;
    private $server;
    private $gamenode;
    private $logfile;

    private function __construct(){
        $this->logfile = ROOT.'/var/server.log';
    }

    private function checkArgs(){
        if(!isset(App::request()->get()->name)){
            throw new ErrorException("缺少必要参数name");
        }
        $name = App::request()->get()->name;

        $this->gamenode = App::cfg()->gamenodes->$name;
        if(!$this->gamenode){
            throw new ErrorException("找不到游戏节点{$name}的配置信息");
        }

        $name = $this->gamenode->server;
        $this->server = App::cfg()->servers->$name;
        if(!$this->server){
            throw new ErrorException("找不到游戏节点对应服务器{$this->gamenode['server']}的配置信息");
        }
    }

    // 当前脚本被载入时会自动调用此方法
    public static function __awake(){
        App::sess()->close(); // 这里必须关闭session，不然无法并发，用户体验不好
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

    // 获取控制台内容
    public static function console(){
        $view = App::view();
        $view->vars->content = Common::formatConsole(Util::tail(self::getInstance()->logfile));
        $view->render('console');
    }

    // 显示主界面
    public static function index(){
	// echo system("locale");
        $nodes = [];
        foreach(APP::cfg()->gamenodes->getArray() as $k => $v){
            $nodes[$k] = $v->name;
        }
        $datafiles = Common::datafiles();
        $update_file_num = 0;
        foreach($datafiles as $file){
            if(strlen($file['update_flag']) > 0) $update_file_num++;
        }
        $view = App::view();
        $view->vars->update_file_num = $update_file_num;
        $view->vars->datafiles = $datafiles;
        $view->vars->form_url = App::url('/server/gen/data');
        $view->vars->console = App::url('/server/console');
        $view->vars->select_gamenode = Form::dropdown('select-gamenode', $nodes);
        $view->render('server');
    }

    // 编译服务器
    public static function make(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在编译服务端源码...\n");
        $root = App::cfg()->project_root;
        $cmd = "bash {$root}/dev.sh srv_make";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译服务端源码时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 编译服务器配置文件
    public static function make_cfg(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在编译服务端源码...\n");
        // $cmd = "bash {$self->gamenode->root}/srv.sh make_mod data && {$self->gamenode->root}/srv.sh make_mod battle";
        $root = App::cfg()->project_root;
        $cmd = "bash {$root}/dev.sh srv_makedata 0";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译服务端源码时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        if (preg_match ( "/.*(undefined.in.record).*/", $stdout)) {
            App::alert("编译有错，热更失败");
        }
        $str = preg_replace('/(\>error.*|.*undefined.in.record.*)/', '<font color="red">[\1]</font>', $stdout);
        self::writeLog($str);
    }

    // 校验AI技能配置文件
    public static function data_check(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在校验数据表配置信息...\n");
        $root = App::cfg()->project_root;
        $cmd = "bash {$root}/dev.sh srv exec {$self->gamenode->platform} {$self->gamenode->zone_id} t check_data";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("编译服务端源码时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 热更新服务器 
    public static function hotswap(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在热更新服务器 {$self->gamenode->name} ...\n");
        $root = App::cfg()->project_root;
        # $cmd = "bash {$root}/dev.sh srv_exec {$self->gamenode->platform} {$self->gamenode->zone_id} dev u";
        $cmd = "bash {$root}/server/all.sh update";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("热更新服务器 {$self->gamenode->name} 时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 更新SVN
    public static function upsvn(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在更新SVN数据 ...\n");

        $root = App::cfg()->project_root;
        $cmd = "bash {$root}/dev.sh pull docs";

        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("更新SVN时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::info("更新SVN数据结束 ...\n");
        self::writeLog($stdout);
    }

    // 生成配置数据
    public static function genData(){
        $files = App::request()->get()->files;
        if(!$files) return;
        self::info("正在生成配置数据[$files]，某些数据需要处理比较长的时间...\n");
        $is_err = Common::genData(explode(',', $files), self::getInstance()->logfile);
        self::info("生成配置数据完毕\n");
        if(!$is_err && App::request()->get()->hotswap == 'true'){
            self::make_cfg();
            self::hotswap();
        }
    }

    // 生成地图数据
    public static function genMap(){
        $self = self::getInstance();
        $self->checkArgs();

        self::info("正在生成地图数据...\n");
        $cmd = "bash {$self->server->project_root}/dev.sh gen_data map_data";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("生成地图数据失败[$rtn]:\n$stderr\n");
        }
        self::writeLog($stdout);
    }

    // 生成战场数据
    public static function genBattle(){
        $self = self::getInstance();
        $self->checkArgs();

        self::info("正在生成战场数据...\n");
        $cmd = "bash {$self->server->project_root}/dev.sh battle all";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("生成战场数据失败[$rtn]:\n$stderr\n");
        }
        self::writeLog($stdout);
    }

    // 启动服务器
    public static function start(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在启动服务器 {$self->gamenode->name} ...\n");
        // $cmd = "bash {$self->server->project_root}/dev.sh srv_start {$self->gamenode->platform} {$self->gamenode->zone_id}";
        $cmd = "bash {$self->server->project_root}/server/all.sh start";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("启动服务器 {$self->gamenode->name} 时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 关闭服务器
    public static function stop(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在关闭服务器 {$self->gamenode->name} ...\n");

        // $cmd = "bash {$self->server->project_root}/dev.sh srv_stop {$self->gamenode->platform} {$self->gamenode->zone_id}";
        $cmd = "bash {$self->server->project_root}/server/all.sh stop";

        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("关闭服务器 {$self->gamenode->name} 时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 服务器状态
    public static function status(){
        $self = self::getInstance();
        $self->checkArgs();
        self::info("正在查询服务器 {$self->gamenode->name} 的状态...\n");

        $cmd = "bash {$self->server->project_root}/dev.sh srv_exec {$self->gamenode->platform} {$self->gamenode->zone_id} info i";

        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("查询服务器 {$self->gamenode->name} 的状态时发生错误[$rtn]:\n$stderr\n");
            return;
        }
        self::writeLog($stdout);
    }

    // 获取游戏节点日志内容
    public static function log(){
        $self = self::getInstance();
        $self->checkArgs();
        $cmd = "tail -n 3000 {$self->gamenode->root}/screenlog.0";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            echo "无法读取服务器 {$self->gamenode->name} 的日志文件，原因[$rtn]:\n$stderr";
            return;
        }

        $view = App::view();
        $view->vars->content = Common::formatConsole($stdout);
        $view->render('console');
    }

    public static function cmd(){
        // sleep(5);
        $self = self::getInstance();
        $self->checkArgs();
        $cmdstr = App::request()->get()->cmd;
        if(!$cmdstr) return;
        if(!preg_match('/^[^\|\$\(\)`&><]+$/is', $cmdstr)) return App::alert("命令[$cmdstr]中包含有特殊字符, 执行失败");
        self::info("正在执行命令[./dev.sh $cmdstr]，某些命令需要处理比较长的时间...\n");
        $cmd = "bash {$self->server->project_root}/dev.sh $cmdstr";
        $rtn = SSH::exec($self->server, $cmd, $stdout, $stderr);
        if(0 != $rtn){
            self::writeLog("执行命令失败[$rtn]:\n$stderr\n");
        }else{
            self::writeLog("执行命令结果：\n$stdout\n$stderr");
        }
        self::info("执行命令[./dev.sh $cmdstr]完毕\n");
    }

    public static function holiday_cfg(){
        $path = App::cfg()->project_root;
        $filename = $path."/tools/gen_data/out/holiday.cfg.php";
        $fileinfo = pathinfo($filename);
        header('Content-type: application/x-'.$fileinfo['extension']);
        header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
        header('Content-Length: '.filesize($filename));
        readfile($filename);
        exit();
    }
}
