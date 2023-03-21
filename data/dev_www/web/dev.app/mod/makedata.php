<?php
/**----------------------------------------------------+
 * 数据更新工具
 * @author whjing2012@gmail.com
 +-----------------------------------------------------*/
class MakeData{
    private static $instance = null;

    private function __construct(){
        $this->logfile = ROOT.'/var/makedata.log';
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

    // 显示主界面
    public static function index(){
        $datafiles = Common::datafiles();
        $view = App::view();
        $view->vars->files = $datafiles;
        $view->render('makedata');
    }

    /**
     * 生成热更数据
     */
    public static function gen_file(){
        $file = App::request()->get()->file;
        $root = App::cfg()->project_root;
        $cmd = "bash {$root}/dev.sh gen_data $file";
        $rtn = App::os()->exec($cmd, $stdout, $stderr);
        echo("<pre>");
        if(0 != $rtn){
            echo("<p>执行命令失败[$rtn]:\n$stderr</p>");
        }else{
            echo("<p>执行命令结果：\n$stdout\n$stderr</p>");
        }
        echo("<p>生成数据文件结束$file</p>");
        echo("</pre>");
        // App::request()->get()->name = "mg_dev_1";
        // require("server.php");
        // Server::make_cfg();
        // Server::hotswap();
    }

}
