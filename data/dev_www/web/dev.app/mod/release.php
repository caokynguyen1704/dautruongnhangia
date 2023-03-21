<?php
/*-----------------------------------------------------+
 * 版本浏览页面处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class release{
    private static $pattern = '/^[a-z]+-v\d{6}_\d{4}(_[0-9]{2}){0,1}-[0-9a-z]{40}-[a-z]+\.((ipa)|(apk)|(zip))$/i';

    // 显示主界面
    public static function index(){
        $view = App::view();

        $tags = [];
        foreach(App::git()->repo('client')->versions() as $k => $v){
            $tags[$k] = $k;
        }

        $view->vars->select_version = Form::dropdown('select-version', $tags, null, "inline dropdown");
        $view->render('release');
    }

    public static function diff(){
        $ver = App::request()->get()->ver;
        if(!$ver) return;
        $info = [];
        self::info('client', $info, $ver);
        self::info('resources', $info, $ver);
        self::info('server', $info, $ver);
        self::info('web', $info, $ver);
        echo json_encode($info);
    }

    // 解析版本信息
    private static function info($repo, &$info, $ver){
        $git = App::git()->repo($repo);

        // 获取当前版本与上一版本的hash
        $curr = null;
        $prev = null;
        $prevReady = false;
        foreach($git->versions() as $v){
            if($prevReady){
                $prev = $v['hash'];
                break;
            }
            if($v['ver'] == $ver){
                $curr = $v['hash'];
                $prevReady = true;
            }
        }
        if(is_null($prev)) return;

        // 汇总日志内容
        if(!isset($info['增加'][$repo])){
            $info['增加'][$repo] = [];
            $info['修复'][$repo] = [];
            $info['优化'][$repo] = [];
            $info['其它'][$repo] = [];
            $info['改动的文件'][$repo] = [];
        }
        $logs = $git->log('', 0, $curr, $prev);
        foreach($logs as $log){
            $info['改动的文件'][$repo] = array_merge($info['改动的文件'][$repo], $log['files']);
            foreach($log['msg'] as $k => $v){
                if(!isset($info[$k][$repo])) continue; // 不关心的标签直接跳过
                foreach($v as $vv){
                    $info[$k][$repo][] = $vv;
                }
            }
        }
    }
}
