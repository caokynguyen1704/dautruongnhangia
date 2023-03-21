<?php
error_reporting(0);
$PZ = array(
	'DB_HOST'=>'127.0.0.1',// 服务器地址
	'DB_USER'=>'root',// 用户名
	'DB_PWD'=>'mx52.cn',// 密码
	'DB_PORT'=>'3306'
);

$d_gmrz = 'mx52.cn';//gm认证码
$d_name = '快乐币';//货币名称
$d_rmb = '1000'; //新人送的快乐币
$d_flwp = array(array(3, 1, 10000000),array(22, 1, 200000000),array(5, 1, 8000000));
// 新角色福利领取给与的物品.
$d_daywp = array(array(22, 1, 50000000),array(39052, 1, 50),array(10001, 1, 20000),array(3, 1, 100000));
// 角色每日福利领取给与的物品.

if($qu == 1){
    $d_zone = 1;
    $d_plat = 'local';
    $d_ssdb = 'sszg_local_1';                      //数据库名称
    $d_keys = '87591ec04aebdda12e1d960db30fde1as1';  // 服务器key
    $d_port = 8001;

}elseif($qu == 2){
    $d_zone = 2;
    $d_plat = 'local';
    $d_ssdb = 'sszg_local_2';
    $d_keys = '87591ec04aebdda12e1d960db30fde1as2';  // 服务器key
    $d_port = 8002;
	
}elseif($qu == 3){
    $d_zone = 3;
    $d_plat = 'local';
    $d_ssdb = 'sszg_local_3';
    $d_keys = '87591ec04aebdda12e1d960db30fde1as3';  // 服务器key
    $d_port = 8003;
		
}else{
    die('区服错误');
}

function Order_id(){
    static $ORDERSN=array();
    $ors=date('ymd').substr(time(),-5).substr(microtime(),2,5);
    if (isset($ORDERSN[$ors])){
        $ORDERSN[$ors]++;
    }else{
        $ORDERSN[$ors]=1;
    }
    return $ors.str_pad($ORDERSN[$ors],2,'0',STR_PAD_LEFT);
}


function erls($module, $method, $format = '', $args = array(),$d_plat,$d_keys,$d_port) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket) return array('success' => false, 'message' => 'create_conn_failed');
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>2, "usec"=>0));
    socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>2, "usec"=>0));
    $conn = socket_connect($socket, '127.0.0.1', $d_port);
    if (!$conn) return array('success' => false, 'message' => 'connect_failed');
    socket_write($socket, "web_conn---------------");
    $time = time();
    if (trim($format) === '') { 
        $data = array($d_plat, $time, md5($d_plat.$time.$d_keys), $format, $module, $method);
    } else {
        $data = array($d_plat, $time, md5($d_plat.$time.$d_keys), $format, $module, $method, $args);
    }
    $data = json_encode($data);
    socket_write($socket, pack('n', strlen($data)));
    socket_write($socket, $data);
    $recvData = '';
    while ($r = socket_recv($socket, $bufs, 1024, 0)) {
        $recvData .= $bufs;
    }
    socket_close($socket);
    if (json_last_error() !== JSON_ERROR_NONE) return array('success' => false, 'message' => '返回数据不是json格式');
    return json_decode($recvData, true);
}
?>