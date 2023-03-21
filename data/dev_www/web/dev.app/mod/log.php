<?php
/*-----------------------------------------------------+
 * git日志处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class log{
    // 显示主界面
    public static function index(){
        $view = App::view();
        $view->render('log');
    }

    // 查询指定的git仓库日志
    public static function query(){
        $get = App::request()->get();
        $logs = App::git()->repo($get->repo)->log($get->keyword, $get->num);
        foreach($logs as $k => $v){
            $msg = [];
            foreach($v['msg'] as $t => $m){
                foreach($m as $l){
                    $l = str_replace("\n", "<br />", $l);
                    $msg[] = "[$t]$l";
                }
            }
            $logs[$k]['msg'] = implode("<br/>", $msg);
        }
        echo json_encode($logs);
    }
}
