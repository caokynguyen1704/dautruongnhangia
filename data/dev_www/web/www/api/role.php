<?php
/**
 * 数组转换成lua_table
 */
function luatable_encode($arr){
    $str = "{";
    foreach($arr as $k => $v){
        if(is_int($k)){
            $k = $k + 1;  // lua的下标是从1开始的
            $str .= "[$k] = ";
        }else{
            $str .= "['$k'] = ";
        }
        if(is_int($v)){
            $str .= $v;
        }elseif(is_array($v)){
            $str .= luatable_encode($v);
        }else{
            $str .= "[[$v]]";
        }
        $str .= ",\n";
    }
    $str .= "}";
    return $str;
}

$role_info = array();
$channel=$_REQUEST['chanleId'];
$type=isset($_GET['type']) ? $_GET['type'] : '';
$auth = "asdfasfasfasf";
$server_list = array(
array('srv_id'=>'local_6', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:96','erl_port'=>8006,'srv_name'=>'s6', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>2, 'maintain'=>0),
array('srv_id'=>'local_5', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:95','erl_port'=>8005,'srv_name'=>'s5', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>2, 'maintain'=>0),
array('srv_id'=>'local_4', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:94','erl_port'=>8004,'srv_name'=>'s4', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>1, 'maintain'=>0),
array('srv_id'=>'local_3', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:93','erl_port'=>8003,'srv_name'=>'s3', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>1, 'maintain'=>0),
array('srv_id'=>'local_2', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:92','erl_port'=>8002,'srv_name'=>'s2', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>1, 'maintain'=>0),
array('srv_id'=>'local_1', 'zone_id'=>1, 'platform'=>'local', 'platform_name'=>'local', 'first_zone' => 0, 'ip'=>'20.230.41.48','host'=>'20.230.41.48:91','erl_port'=>8001,'srv_name'=>'s1', 'isnew'=>1, 'begin_time'=>1461923200, 'recomed'=>1, 'group_num'=>1, 'maintain'=>0),

   
);

$def_zone=$server_list[0];
if(isset($_REQUEST['srvid'])){
    $srvid = $_REQUEST['srvid'];
    foreach($server_list as $k=>$v){
	if($v['srv_id'] == $srvid){
            $def_zone = $v;
            break;
	}
    }
}
$data = array('role_info' => $role_info, 'server_list' => $server_list, 'default_zone'=>$def_zone,'auth'=>$auth);
$msg = "info";
if($type == "json"){
   header("Access-Control-Allow-Origin: *"); 
   echo json_encode(array('msg' => $msg, 'data' => $data));
}else{
   echo luatable_encode(array('msg' => $msg, 'data' => $data));
}
