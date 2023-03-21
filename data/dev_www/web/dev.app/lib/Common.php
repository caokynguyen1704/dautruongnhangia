<?php
/*-----------------------------------------------------+
 * 公共函数库
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class Common{

    // 格式化控制台内容
    public static function formatConsole($text){
        return str_replace(
            ["\n", '[0;0m', '[o0m', '[92m', '[91m', '[95m', '[34m'],
            ['<br/>', '</span>', '<span class="o">', '<span class="g">', '<span class="r">', '<span style="color:#f69;">', '<span style="color:#33c;">'],
            $text
        );
    }

    /**
     * 获取所有指定类型文件
     */
    public static function listfiles($path, $ftype){
        $handle = @opendir($path);
        $files = [];
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !preg_match('/^[0-9a-zA-Z\_-]+.'.$ftype.'$/',$file)) continue;
            $p = pathinfo($file);
            if(!isset($p['extension']) || $p['extension'] != $ftype) continue;

            $s = stat($path.'/'.$file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['mtime'] = date('Y/m/d H:i:s', $s['mtime']);
            $f['size'] = sprintf("%.2f", $s['size'] / 1024/1024);
            $files[$p['basename']] = $f;
        }
        krsort($files);
        return $files;
    }

    /**
     * 获取数据文件列表
     */
    public static function datafiles(){
        $path = App::cfg()->game_config;
        $handle = @opendir($path);
        if(!$handle) throw new ErrorException("无法打开数据文件目录");
        $files = [];
        $lastdo = self::get_datafiles_last_gen();
        $flag = false;
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !preg_match('/^[0-9a-zA-Z\_]+.xml$/',$file)) continue;
            $p = pathinfo($file);
            if(!isset($p['extension']) || $p['extension'] != 'xml') continue;

            $s = stat($path.'/'.$file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['mtime'] = date('Y/m/d H:i:s', $s['mtime']);
            $f['size'] = sprintf("%.2f", $s['size'] / 1024);
            $f['update_flag'] = "";
            if(isset($lastdo[$f['name']])){
                $f['gen_time'] = date('Y/m/d H:i:s', $lastdo[$f['name']]);
                if($s['mtime']>$lastdo[$f['name']]) $f['update_flag'] = "<font color='red'>*</font>";
            }else{
                $f['gen_time'] = date('Y/m/d H:i:s', 0);
                $lastdo[$f['name']] = time();
                $flag = true;
            }
            $files[$p['basename']] = $f;
        }
        if ($flag) self::save_datafiles_last_gen($lastdo);
        $core_files = Common::core_datafiles(App::cfg()->game_core_config);
        $core_s_files = Common::core_datafiles(App::cfg()->game_core_s_config);
        $files = array_merge($files, $core_files, $core_s_files);
        ksort($files);
        return $files;
    }

    public static function core_datafiles($path){
        $handle = @opendir($path);
        if(!$handle) return [];
        $files = [];
        $lastdo = self::get_datafiles_last_gen();
        $flag = false;
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !preg_match('/^[0-9a-zA-Z\_]+.xml$/',$file)) continue;
            $p = pathinfo($file);
            if(!isset($p['extension']) || $p['extension'] != 'xml') continue;

            $s = stat($path.'/'.$file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['mtime'] = date('Y/m/d H:i:s', $s['mtime']);
            $f['size'] = sprintf("%.2f", $s['size'] / 1024);
            $f['update_flag'] = "";
            if(isset($lastdo[$f['name']])){
                $f['gen_time'] = date('Y/m/d H:i:s', $lastdo[$f['name']]);
                if($s['mtime']>$lastdo[$f['name']]) $f['update_flag'] = "<font color='red'>*</font>";
            }else{
                $f['gen_time'] = date('Y/m/d H:i:s', 0);
                $lastdo[$f['name']] = time();
                $flag = true;
            }
            $files[$p['basename']] = $f;
        }
        if ($flag) self::save_datafiles_last_gen($lastdo);
        ksort($files);
        return $files;
    }

    public static function find_datafiles($path, &$files, &$lastdo, &$flag){
	if(is_array($path)){
	   
	   return $files;
	}
        $handle = @opendir($path);
        if(!$handle) return $files;
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !preg_match('/^[0-9a-zA-Z\_]+.xml$/',$file)) continue;
            $p = pathinfo($file);
            if(!isset($p['extension']) || $p['extension'] != 'xml') continue;

            $s = stat($path.'/'.$file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['mtime'] = date('Y/m/d H:i:s', $s['mtime']);
            $f['size'] = sprintf("%.2f", $s['size'] / 1024);
            $f['update_flag'] = "";
            if(isset($lastdo[$f['name']])){
                $f['gen_time'] = date('Y/m/d H:i:s', $lastdo[$f['name']]);
                if($s['mtime']>$lastdo[$f['name']]) $f['update_flag'] = "<font color='red'>*</font>";
            }else{
                $f['gen_time'] = date('Y/m/d H:i:s', 0);
                $lastdo[$f['name']] = time();
                $flag = true;
            }
            $files[$p['basename']] = $f;
        }
        return $files;
    }

    /**
     * 获取数据文件列表
     */
    public static function datafiles_new(){
        $path = App::cfg()->game_config;
        $handle = @opendir($path);
        if(!$handle) throw new ErrorException("无法打开数据文件目录");
        $files = [];
        while(false !== ($file = readdir($handle))){
            if($file == '.' || $file == '..' || !is_dir($path.'/'.$file)) continue;
            $p = pathinfo($file);
            $f = [];
            $f['name'] = $p['filename'];
            $f['update_flag'] = "";
            $f['mtime'] = "";
            $f['size'] = "0";
            $f['gen_time'] = date('Y/m/d H:i:s', 0);
            $files[$p['basename']] = $f;
        }
        ksort($files);
        return $files;
    }

    /*
     * 获取文件最后修改时间
     */
    public static function get_datafiles_last_gen($file = "/var/file_last_gen.php"){
        if(file_exists(ROOT.$file)) return include ROOT.$file;
        return array();
    }

    /*
     * 保存文件最后修改时间
     */
    public static function save_datafiles_last_gen($arr, $file = "/var/file_last_gen.php"){
        file_put_contents(ROOT.$file, "<?php \n\treturn ".var_export($arr, true).";");
    }

    // 生成配置数据
    public static function genData($files, $logfile){
        $srv = App::cfg()->servers->main;
        if(!$srv) throw new ErrorException("配置文件中没有主服务器的信息");

        $root = App::cfg()->project_root;
        $lastdo = self::get_datafiles_last_gen();
        $is_err = false;
        $mod = App::request()->get()->select_make_filemod;
        foreach($files as $file){
            # 安全性过滤
            if(!preg_match("/[0-9a-z_]+/i", $file)) continue;
            $cmd = "bash {$root}/dev.sh gen_data $file";
            $rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
            if(0 != $rtn){
                error_log("生成数据文件 $file 失败[$rtn]: $stderr\n", 3, $logfile);
            }
            $lastdo[$file] = time();
            $str = preg_replace('/(error.*|\>警告.*|terminating.*)/', '<font color="red">[\1]</font>', $stdout);
            error_log($str, 3, $logfile);
            if (preg_match ( "/.*out\/.*\.(dat|erl).*/", $str)) {
                $is_err = true;
            }else if (preg_match ( "/.*(警告|错误|error|terminating).*/", $str)) {
                $is_err = true;
            }else{
            }
        }
        if($is_err){
            App::alert("配置数据有错，请认真检查");
        }
        self::save_datafiles_last_gen($lastdo);
        return $is_err;
    }

    // 生成配置数据
    public static function genData_new($files, $logfile){
        $srv = App::cfg()->servers->main;
        if(!$srv) throw new ErrorException("配置文件中没有主服务器的信息");
        $root = App::cfg()->project_root;
        $path = App::cfg()->game_config;
        foreach($files as $file){
            $cmd = "bash {$root}/dev.sh gen_data $file";
            $rtn = SSH::exec($srv, $cmd, $stdout, $stderr);
            if(0 != $rtn){
                error_log("<p>执行命令失败[$rtn]:\n$stderr</p>", 3, $logfile);
            }else{
                error_log("<p>执行命令结果：\n$stdout\n$stderr</p>", 3, $logfile);
            }
            error_log("<p>生成数据文件结束$file</p>", 3, $logfile);
        }
    }
}
