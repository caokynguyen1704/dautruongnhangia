<?php
/**----------------------------------------------------+
 * 数据比对 
 * @author whjing2011@gmail.com
 +-----------------------------------------------------*/

class DiffData{
    private static $instance = null;

    private function __construct(){
        $this->logfile = ROOT."/var/diffdata.log";
    }

   // 当前脚本转入时自动调用此方法 
    public static function __awake(){
        App::sess()->close(); // 这里必须关闭session 不然无法并发 用户体验不好
    }

    private static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 显示主界面
    public static function index(){
        $path = App::cfg()->project_root;
        $cmd = "cd $path && sudo ./dev.sh git diff server_core_data --name-status origin/master data";
        $rtn = SSH::exec(App::cfg()->servers->main, $cmd, $stdout, $stderr);
        $view = App::view();
        $view->vars->files = self::datafiles($path."/server/dbin", "beam", "erl", $stdout);
        $view->vars->difftype = "differl";
        $view->render('diffdata');
    }

    // 显示客户端文件列表
    public static function cli_index(){
        $path = App::cfg()->project_root;
        $cmd = "cd $path && sudo ./dev.sh git diff client_config_core --name-status origin/master src/config";
        $rtn = SSH::exec(App::cfg()->servers->main, $cmd, $stdout, $stderr);
        $view = App::view();
        $view->vars->files = self::datafiles($path."/client/src/config", "luac", "lua", $stdout);
        $view->vars->difftype = "difflua";
        $view->render('diffdata');
    }

    // 比对服务端文件 
    public static function differl(){
        $path = App::cfg()->project_root;
        $file = App::request()->get()->file;
        $filepath = "data/$file.erl";
        $cmd = "cd $path && sudo ./dev.sh git diff server_core_data origin/master $filepath";
        $rtn = SSH::exec(App::cfg()->servers->main, $cmd, $stdout, $stderr);
        $str = preg_replace('/(\n-[^-].+)/i', '<font color="red">${1}</font>', $stdout);
        $str = preg_replace('/(\n\+[^+].+)/i', '<font color="green">${1}</font>', $str);
        echo str_replace("\n", "<br />", $str);
        exit();
    }

    // 比对客户端文件 
    public static function difflua(){
        $path = App::cfg()->project_root;
        $file = App::request()->get()->file;
        $filepath = "src/config/$file.lua";
        $cmd = "cd $path && sudo ./dev.sh git diff client_config_core origin/master $filepath";
        $rtn = SSH::exec(App::cfg()->servers->main, $cmd, $stdout, $stderr);
        $str = preg_replace('/(\n-[^-].+)/i', '<font color="red">${1}</font>', $stdout);
        $str = preg_replace('/(\n\+[^+].+)/i', '<font color="green">${1}</font>', $str);
        echo str_replace("\n", "<br />", $str);
        exit();
    }

    /**
     * 获取数据文件列表
     */
    public static function datafiles($path, $ftype, $showtype, $filelist){
        $handle = @opendir($path);
        if(!$handle) throw new ErrorException("无法打开数据文件目录");
        $files = [];
        $flag = false;
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !preg_match('/^[0-9a-zA-Z\_]+.'.$ftype.'$/',$file)) continue;
            $p = pathinfo($file);
            if(!isset($p['extension']) || $p['extension'] != $ftype) continue;
            if(! strpos($filelist, "/".$p['filename'].".".$showtype)) continue;

            $s = stat($path.'/'.$file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['type'] = $showtype;
            $f['mtime'] = date('Y/m/d H:i:s', $s['mtime']);
            $f['size'] = sprintf("%.2f", $s['size'] / 1024);
            $files[$p['basename']] = $f;
        }
        ksort($files);
        return $files;
    }
}
