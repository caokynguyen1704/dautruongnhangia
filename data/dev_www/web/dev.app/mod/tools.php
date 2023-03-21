<?php
/*-----------------------------------------------------+
 * 用户登录处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class tools{
    private static $instance = null;
    private $logfile;

    private function __construct(){
        $this->logfile = ROOT.'/var/server.log';
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

    // 文件上传
    public static function upload_file(){
	if (isset($_FILES['myFile'])) 
	{
	    $names = $_FILES["myFile"]['name'];
	    $arr   = explode('.', $names);
	    $name  = $arr[0]; //图片名称
	    $date  = date('Y-m-d H:i:s'); //上传日期
	    $fp    = fopen($_FILES['myFile']['tmp_name'], 'rb');
	    $type  = $_FILES['myFile']['type'];
	    $filename = $_FILES['myFile']['name'];
	    $tmpname = $_FILES['myFile']['tmp_name'];
	    $filetype = App::request()->get()->type;
	    $save_file = ROOT."/var/upload_file/cfg/".$filename;
	    if($filetype == "hrl"){
	        $save_file = ROOT."/var/upload_file/inc/".$filename;
	    }else if($filetype == "tpl"){
	        $save_file = ROOT."/var/upload_file/tpl/".$filename;
            }
	    //将文件传到服务器根目录的 upload 文件夹中
	    if(move_uploaded_file($tmpname, $save_file)){
            	self::info("文件上传成功...\n");
		exit("upload_file_success:$filename");
	    }else{
            	self::info("文件上传失败...$filename\n");
		exit("upload_file_fail:$filename");
	    }
	}else{
            self::info("上传文件失败...\n");
	    exit("upload_file_fail");
	}
    }
    
    // 生成配置模块
    public static function web_gen_data_cfg(){
        $file = App::request()->get()->mod;
	if(!preg_match("/[0-9a-z_]+/i", $file)) exit("error_mod");
        $root = App::cfg()->project_root;
	$srv = App::cfg()->servers->main;
	$cmd = "bash {$root}/dev.sh temp_gen_data_cfg $file";
	$rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
	if(0 != $rtn){
	    exit("生成数据文件 $file 失败[$rtn]: $stderr");
	}
	self::info("生成模板文件[$file]...$stdout\n");
        $filepath = ROOT.'/var/upload_file/cfg/'.$file.".erl";
	self::download($filepath, $file);
    }
    
    // 生成配置xml
    public static function web_gen_xml(){
        $file = App::request()->get()->mod;
	if(!preg_match("/[0-9a-z_]+/i", $file)) exit("error_mod");
        $root = App::cfg()->project_root;
	$srv = App::cfg()->servers->main;
	$cmd = "bash {$root}/dev.sh temp_gen_xml $file";
	$rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
	if(0 != $rtn){
	    exit("生成数据文件 $file 失败[$rtn]: $stderr");
	}
        $filepath = ROOT.'/var/upload_file/temp_xml/'.$file.".xml";
	self::download($filepath, $file);
    }
    
    // 生成配置数据
    public static function web_gen_data(){
        $file = App::request()->get()->mod;
	if(!preg_match("/[0-9a-z_]+/i", $file)) exit("error_mod");
        $root = App::cfg()->project_root;
	$srv = App::cfg()->servers->main;
	$cmd = "bash {$root}/dev.sh temp_gen_data $file";
	$rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
	if(0 != $rtn){
	    exit("生成数据文件 $file 失败[$rtn]: $stderr");
	}
	exit("ret:$stdout");
    }

    // 下载beam
    public static function web_get_beam(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/server_dbin/'.$mod.'.beam';
	self::download($file, $mod);
    }

    // 下载luac
    public static function web_get_luac(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/client_config_luac/'.$mod.'.luac';
	self::download($file, $mod);
    }

    // 下载dev beam
    public static function web_get_dev_beam(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/dev_server_dbin/'.$mod.'.beam';
	self::download($file, $mod);
    }

    // 下载dev luac
    public static function web_get_dev_luac(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/dev_client_config_luac/'.$mod.'.luac';
	self::download($file, $mod);
    }

    // 生成配置数据demo
    public static function web_gen_data_demo(){
        $file = App::request()->get()->mod;
	if(!preg_match("/[0-9a-z_]+/i", $file)) exit("error_mod");
        $root = App::cfg()->project_root;
	$srv = App::cfg()->servers->main;
	$cmd = "bash {$root}/dev.sh temp_gen_data_demo $file";
	$rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
	if(0 != $rtn){
	    exit("生成数据文件demo $file 失败[$rtn]: $stderr");
	}
	exit("ret:$stdout");
    }

    // 下载demo erl
    public static function web_get_demo_erl(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/server_dbin/'.$mod.'.erl';
	self::download($file, $mod);
    }

    // 下载demo lua
    public static function web_get_demo_lua(){
        $mod = App::request()->get()->mod;
        $file = ROOT.'/var/upload_file/client_config_luac/'.$mod.'.lua';
	self::download($file, $mod);
    }

    // 下载
    public static function download($filepath, $filename){
        if(!file_exists($filepath)){
            echo("file_not_exists: $filepath $filename");
            return;
        }
        $p = pathinfo($filepath);
        ob_start();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=$filename.{$p['extension']}");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        ob_flush();
        set_time_limit(0);
        $fp = fopen($filepath, "r"); 
        while(!feof($fp)) {
            echo fread($fp, 4096);                  
            ob_flush();
        } 
        ob_end_clean();
        fclose($fp);
    }


}
