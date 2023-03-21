<?php
/**
 * 活动测试服相关接口
* @author Jange
*/

// 根目录配置
define('ROOT', '/data/sszg/dev');
define('ZONE', ROOT. '/zone/sszg_lab_1');
define('WWW', ROOT. '/web/www');
define('WWW_VAR', WWW. '/var');
if (isset($_GET['act'])) {
    $action = $_GET['act'];
    switch($action) {
        // 更改时间
    case 'sync_code':
        $cmd = "/usr/bin/rsync -avH -e 'ssh -p22' --progress root@192.168.1.147:/data/sszg_code/server/ebin ".ROOT."/server/";
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'msg' => '同步代码'
        );
        log_op($res);
        exit(json_encode($res));
    case 'set_date_time':
        log_op_start('更改服务器时间');
        $timeStr = isset($_GET['date_time']) ? $_GET['date_time'] : '';
        if(preg_match('/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $timeStr, $tmatch, PREG_OFFSET_CAPTURE) == 0) {
            $res = array(
                'code' => 0
                ,'msg' => '时间格式不对:'. $timeStr
            );
            log_op($res);
            exit(json_encode($res));
        }
        if(is_node_running()) {
            $res = array(
                'code' => 0
                ,'msg' => '服务器正在启动中，请先关服再改时间'
            );
            log_op($res);
            exit(json_encode($res));
        }
        $cmd = '/usr/bin/date --set="'. $timeStr. '"';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '时间更新为:'. $timeStr
        );
        log_op($res);
        exit(json_encode($res));

        // 更新互联网时间
    case 'up_date_time':
        log_op_start('更新服务器时间');
        if(is_node_running()) {
            $res = array(
                'code' => 0
                ,'msg' => '服务器正在启动中，请先关服再改时间'
            );
            log_op($res);
            exit(json_encode($res));
        }
        # $cmd = '/usr/sbin/ntpdate time.windows.com;hwclock --systohc';
        $cmd = '/usr/sbin/ntpdate ntp6.aliyun.com;hwclock --systohc';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '更新互联网时间:'. date('Y-m-d H:i:s')
            ,'date_time' => date('Y-m-d H:i:s')
        );
        log_op($res);
        exit(json_encode($res));

        // 更改开服时间
    case 'set_open_time':
        log_op_start('更改开服时间');
        $timeStr = isset($_GET['date_time']) ? $_GET['date_time'] : '';
        if(preg_match('/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $timeStr, $tmatch, PREG_OFFSET_CAPTURE) == 0) {
            $res = array(
                'code' => 0
                ,'msg' => '时间格式不对:'. $timeStr
            );
            log_op($res);
            exit(json_encode($res));
        }
        $unixtime = strtotime($timeStr);
        $opRes = set_open_time($unixtime);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '开服时间更新为:'. $unixtime
        );
        log_op($res);
        exit(json_encode($res));

        // 设开服时间为当前
    case 'up_open_time':
        log_op_start('更新开服时间');
        $unixtime = time();
        $opRes = set_open_time($unixtime);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '开服时间更新为:'. $unixtime
            ,'date_time' => date('Y-m-d H:i:s')
        );
        log_op($res);
        exit(json_encode($res));

        // 生成表
    case 'gen_data':
        log_op_start('生成配置表');
        $dataName = isset($_GET['data_files']) ? $_GET['data_files'] : '';
        if(preg_match('/^(\w+_)+data$/', $dataName, $tmatch, PREG_OFFSET_CAPTURE) == 0) {
            $res = array(
                'code' => 0
                ,'msg' => '表名不对:'. $dataName
            );
            log_op($res);
            exit(json_encode($res));
        }
        $cmd = ROOT. "/dev.sh gen_data $dataName";
        // $opRes = $cmd;
        $opRes = sudo($cmd);
        log_gen_data($dataName);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '生成配置表:'. $dataName
        );
        log_op($res);
        exit(json_encode($res));

        // 编译模块
    case 'make_mod':
        log_op_start('编译模块');
        $module = isset($_GET['module']) ? $_GET['module'] : '';
        $cmd = ROOT. "/dev.sh srv_make_mod $module";
        // $opRes = $cmd;
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '编译模块:'. $module
        );
        log_op($res);
        exit(json_encode($res));

        // 启动节点
    case 'start':
        log_op_start('启动服务器');
        // $cmd = ROOT. '/dev.sh srv_start lab 1';
        $cmd = ROOT. '/server/all.sh start';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '启动服务器' 
        );
        log_op($res);
        exit(json_encode($res));

        // 关闭节点
    case 'stop':
        log_op_start('关闭服务器');
        // $cmd = ROOT. '/dev.sh srv_stop lab 1';
        $cmd = ROOT. '/server/all.sh stop';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '关闭服务器' 
        );
        log_op($res);
        exit(json_encode($res));

        // 热更
    case 'hotswap':
        log_op_start('热更服务器');
        // $cmd = ROOT. '/dev.sh srv_exec lab 1 dev u';
        $cmd = ROOT. '/server/all.sh update';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '热更服务器' 
        );
        log_op($res);
        exit(json_encode($res));

        // 更新一下代码
    case 'pull':
        log_op_start('更新代码');
        $cmd = ROOT. '/dev.sh pull';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '更新代码' 
        );
        log_op($res);
        exit(json_encode($res));
        // 编译
    case 'make':
        log_op_start('编译代码');
        $cmd = ROOT. '/dev.sh srv_make';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '编译代码' 
        );
        log_op($res);
        exit(json_encode($res));
        // 清理编译结果
    case 'clean':
        log_op_start('清理编译结果');
        $cmd = ROOT. '/dev.sh srv_clean';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '清理编译结果' 
        );
        log_op($res);
        exit(json_encode($res));
        // 清理日志
    case 'clear_log':
        $cmd = 'rm > '. WWW_VAR. '/op.log';
        $opRes = shell_exec($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '清理日志' 
        );
        log_op($res);
        exit(json_encode($res));

        // 重载商店
    case 'reload_shop':
        log_op_start('重载商店');
        $cmd = ROOT. '/dev.sh srv_exec lab 1 shop_mgr reload';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '重载商店' 
        );
        log_op($res);
        exit(json_encode($res));

        // 重载仙玉市场
    case 'reload_gold':
        log_op_start('重载仙玉市场');
        $cmd = ROOT. '/dev.sh srv_exec lab 1 market_gold reload';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '重载仙玉市场' 
        );
        log_op($res);
        exit(json_encode($res));

        // 重载铜钱市场
    case 'reload_silver':
        log_op_start('重载铜钱市场');
        $cmd = ROOT. '/dev.sh srv_exec lab 1 market_silver reload';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '重载铜钱市场' 
        );
        log_op($res);
        exit(json_encode($res));

        // 重载活动
    case 'reload_camp':
        log_op_start('重载活动');
        $cmd = ROOT. '/dev.sh srv_exec lab 1 campaign_mgr reload';
        $opRes = sudo($cmd);
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '重载活动' 
        );
        log_op($res);
        exit(json_encode($res));
    case 'checkout_data':
        log_op_start('清理数据');
        $cmd = 'rm -f '. ROOT. '/tools/gen_data/ebin/*.beam && cd '. ROOT . '/server/ && git checkout -- .';
        // $opRes = sudo($cmd);
        $opRes = $cmd;
        $res = array(
            'code' => 1
            ,'act' => $action
            ,'opRes' => $opRes
            ,'msg' => '清理数据' 
        );
        log_op($res);
        exit(json_encode($res));
    default:
        break;
    }
}

// 检查节点是否在运行中
function is_node_running() {
    $cmd = "screen -ls | grep xx_";
    if(sudo($cmd) == "") {
        return false;
    }
    return true;
}

// 更改节点配置
function set_open_time($unixtime) {
    $cfgFile = ZONE. '/env.cfg';
    $tmpFile = WWW_VAR. '/_env.tmp';
    $content = file_get_contents($cfgFile);
    $content = preg_replace('/\{open_datetime.*,\d+\}/', "{open_datetime,$unixtime}", $content);
    file_put_contents($tmpFile, $content);
    $cmd = "/usr/bin/cp -rf $tmpFile  $cfgFile";
    $opRes = sudo($cmd);
    return $opRes;
}

// 操作记录
function log_op($res) {
    $fh = fopen(WWW_VAR. '/op.log', 'a');
    if(!empty($res['opRes'])) {
        fwrite($fh, '<li><i>'. date('Y-m-d H:i:s'). '</i> 操作信息：'. $res['opRes']. '</li>');
    }
    if(!empty($res['msg'])) {
        if($res['code'] == 1) {
            fwrite($fh, '<li><i>'. date('Y-m-d H:i:s'). '</i> '. $res['msg']. ' 操作完成</li>');
        }
        else {
            fwrite($fh, '<li><i>'. date('Y-m-d H:i:s'). '</i> '. $res['msg']. ' <i>操作失败</i></li>');
        }
    }
    fclose($fh);
}

// 操作开始记录
function log_op_start($msg) {
    $timeStr = isset($_GET['local_time']) ? $_GET['local_time'] : '';
    if(preg_match('/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $timeStr, $tmatch, PREG_OFFSET_CAPTURE) != 0) {
        $fh = fopen(WWW_VAR. '/op.log', 'a');
        fwrite($fh, '<li><i>'. date('Y-m-d H:i:s'). '</i><b>['. $_SERVER['REMOTE_ADDR']. ']'. $_GET['local_time']. '</b> 开始执行 '. $msg. '</li>');
        fclose($fh);
    }
}

// 记录最后生成表
function log_gen_data($dataName) {
    file_put_contents(WWW_VAR. '/gen_data.log', $dataName);
}

// 获取最后生成表
function get_last_gen_data() {
    if(file_exists(WWW_VAR. '/gen_data.log')) {
        return file_get_contents(WWW_VAR. '/gen_data.log');
    }
    else {
        return "";
    }
}

// 以管理权限执行
function sudo($cmd) {
    $cmd = 'export LC_CTYPE=en_US.UTF-8; sudo '. $cmd;
    return shell_exec($cmd);
}


// 节点配置解析
$zoneCfg = file_get_contents(ZONE. '/env.cfg');
preg_match('/\{open_datetime.*,(\d+)\}/', $zoneCfg, $openCfg, PREG_OFFSET_CAPTURE);
$openTime = $openCfg[1][0];
$logs = file_get_contents(WWW_VAR. '/op.log');
$logs = str_replace("\n", '<br>', $logs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<title>闪烁之光活动测试服参数</title>
<style type="text/css">
*{margin:0px;padding:0px}
#main{margin:auto; width:932px; height:780px; border:1px solid #ccc;}
h2,h3,p{width:100%; border:1px solid #ccc; margin-top:2px;}
p{text-indent:10px; line-height:25px;}
h2,h3{text-align:center;}
#log{width:100%; height:650px; margin:auto; margin-top:2px; background:#eee; overflow:scroll;}
li i{color:red;}
input[type=text]{width:200px;}
input[name=data_files]{width:250px;}
</style>
</head>
<body>
<div id="main">
    <h2>活动测试服相关参数</h2>
    <p>
        <b>当前系统时间：</b> 
        <input type="text" name="date_time" value="<?php echo date('Y-m-d H:i:s'); ?>" placeholder="<?php echo date('Y-m-d H:i:s'); ?>" /> 
        <input type="button" value="更改" name="set_date_time" />
        <input type="button" value="恢复正常" name="up_date_time" />
    </p>
    <p>
        <b>节点开服时间：</b> 
        <input type="text" name="open_time" value="<?php echo date('Y-m-d H:i:s', $openTime); ?>" placeholder="<?php echo date('Y-m-d H:i:s', $openTime); ?>" /> 
        <input type="button" value="更改" name="set_open_time" />
        <input type="button" value="使用现在" name="up_open_time" />
    </p>
<!--    <p>
        <b>数据表：</b> 
        <input type="text" name="data_files" value="<?php echo get_last_gen_data(); ?>" placeholder="<?php echo get_last_gen_data(); ?>" /> 
        <input type="button" value="生成表" name="gen_data" />
        <input type="button" value="重载商店" name="reload_shop" />
        <input type="button" value="重载仙玉市场" name="reload_gold" />
        <input type="button" value="重载铜钱市场" name="reload_silver" />
        <input type="button" value="重载活动" name="reload_camp" />
    </p>
    <p>
        <b>高端操作：</b> 
        <input type="text" name="module" value="data" placeholder="指定模块如：data" /> 
        <input type="button" value="编译模块" name="make_mod" />
        <input type="button" value="清理数据" name="checkout_data" />
    </p>
-->
    <p>
        <b>服务器操作：</b> 
        <input type="button" value="同步发布服代码" name="sync_code" />
        <input type="button" value="清理日志" name="clear_log" /> 
        <!-- <input type="button" value="清理编译结果" name="clean" /> -->
        <input type="button" value="关服" name="stop" /> 
        <input type="button" value="启动" name="start" />
        <input type="button" value="热更" name="hotswap" />
        <!-- <input type="button" value="编译" name="make" />
        <input type="button" value="拉代码和表" name="pull" /> -->
   </p>
   <!-- <h3>操作日志（暂时不支持IE浏览器）</h3> -->
   <ul id="log">
    <?php echo $logs ?>
   </ul>
</div>
</body>
<script type="text/javascript">
var xhrHost = window.location.host + "/lab";
var isActing = false;
// 初始事件委托
var mdiv = document.getElementById('main');
var logUl = document.getElementById('log');
mdiv.onclick = function(e) {
    if (isActing) {
        return;
    }
    var eTarget = e.target || e.srcElement;
    if (eTarget.type != 'button') {
        return;
    }
    buttonAct(eTarget.name);
}

logUl.scrollTop = logUl.scrollHeight;
var xhr = new XMLHttpRequest();
xhr.onload = handleResponse;
// xhr.onprogress = xhrWaiting;
xhr.onloadstart = xhrWaiting;
xhr.onerror = xhrEnd;
xhr.ontimeout = xhrEnd;
xhr.onabort = xhrEnd;

// 按钮回调
function buttonAct(act) {
    console.log(act);
    switch(act) {
    case 'set_date_time':
        var timeStr = document.getElementsByName('date_time')[0].value;
        if( /^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/.test(timeStr) != true) {
            alert('时间格式不对');
            return;
        }
        console.log('http://' + xhrHost + '/?act=' + act + '&date_time=' + timeStr + '&local_time=' + getTimeStr(), true);
        xhr.open('GET', 'http://' + xhrHost + '/?act=' + act + '&date_time=' + timeStr + '&local_time=' + getTimeStr(), true);
        xhr.send();
        break;
    case 'set_open_time':
        var timeStr = document.getElementsByName('open_time')[0].value;
        if( /^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/.test(timeStr) != true) {
            alert('时间格式不对');
            return;
        }
        xhr.open('GET', 'http://' + xhrHost + '/?act=' + act + '&date_time=' + timeStr + '&local_time=' + getTimeStr(), true);
        xhr.send();
        break;
    case 'gen_data':
        var dataName = document.getElementsByName('data_files')[0].value;
        if( /^(\w+_)+data$/.test(dataName) != true) {
            alert('配置表名不对');
            return;
        }
        xhr.open('GET', 'http://' + xhrHost + '/?act=' + act + '&data_files=' + dataName + '&local_time=' + getTimeStr(), true);
        xhr.send();
        break;
    case 'make_mod':
        var module = document.getElementsByName('module')[0].value;
        console.log('GET', 'http://' + xhrHost + '/?act=' + act + '&module=' + module + '&local_time=' + getTimeStr(), true);
        xhr.open('GET', 'http://' + xhrHost + '/?act=' + act + '&module=' + module + '&local_time=' + getTimeStr(), true);
        xhr.send();
        break;
        // 不带参数部分
    case 'up_date_time':
    case 'up_open_time':
    case 'stop':
    case 'start':
    case 'hotswap':
    case 'pull':
    case 'make':
    case 'clean':
    case 'clear_log':
    case 'sync_code':
    case 'checkout_data':
    case 'reload_camp':
    case 'reload_shop':
    case 'reload_gold':
    case 'reload_silver':
        console.log('GET', 'http://' + xhrHost + '/?act=' + act + '&local_time=' + getTimeStr(), true);
        xhr.open('GET', 'http://' + xhrHost + '/?act=' + act + '&local_time=' + getTimeStr(), true);
        xhr.send();
        break;
    }
}

// 数据回调
function handleResponse(e) {
    // console.log(e);
    isActing = false;
    setBtnColor('buttonface');
    var eTarget = e.target || e.srcElement;
    if (eTarget.status != 200) {
        logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> 数据请求异常 <b>' + eTarget.status + '</b></li>';
        return;
    }
    d = JSON.parse(eTarget.responseText);
    if(d.act == 'clear_log' && d.code == 1) {
        logUl.innerHTML = '';
    }
    if(d.act == 'up_date_time' && d.date_time) {
        document.getElementsByName('date_time')[0].value = d.date_time;
    }
    if(d.act == 'up_open_time' && d.date_time) {
        document.getElementsByName('open_time')[0].value = d.date_time;
    }
    if(d.opRes) {
        logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> 操作信息：' + d.opRes.replace(/\n/g, '<br>') +'</li>';
    }
    if(d.code == 1) {
        logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> ' + d.msg +' 操作完成</li>';
    }
    else {
        logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> ' + d.msg +' <i>操作失败</i></li>';
    }
    logUl.scrollTop = logUl.scrollHeight;

}

// 上锁
function xhrWaiting() {
    isActing = true;
    setBtnColor('#999');
    logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> 开始请求数据...</li>';
    logUl.scrollTop = logUl.scrollHeight;
}

// 解锁
function xhrEnd(e) {
    isActing = false;
    setBtnColor('buttonface');
    logUl.innerHTML += '<li><i>' + getTimeStr() + '</i> 数据请求异常 <b>' + eTarget.status + '</b></li>';
    logUl.scrollTop = logUl.scrollHeight;
}

// 按钮颜色
function setBtnColor(color) {
    var inps = mdiv.getElementsByTagName('input');
    for (var i = 0; i < inps.length; i ++) {
        if(inps[i].type == 'button') {
            inps[i].style.background = color;
        }
    }
}

// 格式化时间
function getTimeStr() {
    var dt = new Date();
    return dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate() + ' ' + dt.getHours() + ':' + dt.getMinutes() + ':' + dt.getSeconds();
}
</script>
</html>
