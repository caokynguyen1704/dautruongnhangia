<?php

/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2015/12/24
 * Time: 16:00
 * 服务器模块
 */
class Server extends App
{
	
	
    /**
     * 设置服务器信息（添加、更新）
     */
    public function getviplevel()
    {
				
		
		$rid = Api::getVar('rid');		
		$srv_id = Api::getVar('srvid');
		$account = Api::getVar('account');
        $reg_channel = Api::getVar('reg_channel');
		
//		$where = "rid = $rid " ;
        $where = "account = '$account' and srv_id  = '$srv_id' ";
		$ret = $this->db->getAll(
		"select  money
        from vip_charge where {$where} ");
		
//		from vip_charge where {$where}  ");
		
//      Api::out(0, $ret);
		Api::outlua(0, $ret);
//        return $ret;

/*		
     if (
       
        $rid = Api::getVar('rid');		
		$srv_id = Api::getVar('srv_id');
		$account = Api::getVar('account');
        $reg_channel = Api::getVar('reg_channel');
		$data =array(
		'rid' => $rid,
		'srv_id' => $srv_id,
		'account' => $account,
		'money' => 5000,
        );
		
		$ret = $this->db->insert('vip_charge', $data)
		
		
        $sign = Api::getVar('sign');
        $ts = Api::getInt('ts');
        if ($sign != md5($GLOBALS['cfg']['secret_key'] . $ts)) Api::out(1, 'sign error');

        $fields = array(
            'id' => 'int',
            'platform' => 'var',
            'zone_id' => 'int',
            'zone_name' => 'var',
            'host' => 'var',
            'ip' => 'var',
            'open_time' => 'int',
            'port' => 'int',
            'srv_status' => 'int',
            'is_first' => 'int',
            'recomed' => 'int',
            'isnew' => 'int',
        );
        $gs = Api::getMap('gs', $fields);

        $ret = $this->db->replace('game_servers', $gs);

        if ($ret) {
            Api::out(0, 'success');
        } else {
            Api::out(1, 'fail');
        }
*/		
    }		
    /**
     * 获取服务器列表
     */
    public function lists()
    {
        $ret = $this->list_servers();
        Api::out(0, $ret);
    }

    /**
     * 获取服务器列表
     */
    public function lists2()
    {
        $ret = $this->list_servers();
        Api::outlua(0, $ret);
    }

    /*
     * 获取服务器列表
     */
    public function list_servers()
    {
        $platform = Api::getVar('platform');
        $channel_id = Api::getVar('chanleId');
        $account = Api::getVar('account'); //角色账号
        $last_srv_id = Api::getVar('srvid');
		//$last_srv_id = 'dev_1';
        $startIdx = Api::getInt('start');
        $num = Api::getInt('num');
        if ($channel_id == '') Api::out(1, '渠道号不能为空');

        //对应平台标志（该账户能登陆的平台标志）
        $pfs = $this->getPlatformByChannelId($channel_id);
        if (!$pfs) Api::out(1, '找不到分组');
        $where = 'srv_status!=1';
        $where .= " and platform in('" . implode("','", $pfs) . "')";
        $pf_st = $this->getPlatformSt($channel_id);

        if ($pf_st > 0) $where .= " and open_time >= {$pf_st}";

        //获取角色信息
        $acc_group = $this->acc_group($channel_id);
        $roles = Role::getRoles($account, $acc_group);

        $offset_zoneid = 0;
        // $list_sign = "";
        $list_sign = "".time();
        if($platform == "xinma" || $platform == "en" || $platform == "kokr"){
        }elseif($channel_id == "syios_djsdmm"){
            $offset_zoneid = 525;
            $group_roles_3 = Role::getRoles($account, "3");
            $roles = array_merge(
                $roles
                ,$group_roles_3
            );
            // $in_smzhs_account = array("240594", "3923348", "4013042", "3846442", "3893516", "4271818", "3524720", "3948904", "3900327");
            // if(in_array($account, $in_smzhs_account)){
            if($this->check_min_srv($group_roles_3, $offset_zoneid)){
                $offset_zoneid = 378;
            }
            $where .= " and zone_id > {$offset_zoneid}";
        }elseif(preg_match('/^syios_.*/',$channel_id)){
            $roles = array_merge(
                $roles
                ,Role::getRoles($account, "6")
            );
            $offset_zoneid = 378;
            if($channel_id == "syios_gmzhs"){ // 元气召唤
                $offset_zoneid = 760;
            }elseif($channel_id == "syios_zsry"){ // 众神荣耀
                $offset_zoneid = 764;
            }elseif($channel_id == "syios_fzyz"){ // 放置勇者
                $offset_zoneid = 1374;
            }elseif($channel_id == "syios_supersszg"){ // 闪烁之光 企业签
                $offset_zoneid = 1374;
            }
            if($account == "5153511"){ // 特殊玩家
                $offset_zoneid = 378;
            }
            if($offset_zoneid > 378 && $this->check_min_srv($roles, $offset_zoneid)){
                $offset_zoneid = 378;
            }
            $where .= " and zone_id > {$offset_zoneid}";
        }elseif($channel_id == "46_1"){
            $offset_zoneid = 552;
            $where .= " and zone_id > {$offset_zoneid}";
        }

        if ($num == 1 && !empty($last_srv_id) && isset($roles[$last_srv_id]) ){
            list($pf, $zone) = explode('_', $last_srv_id);
            $where .= " and platform = '{$pf}' and zone_id={$zone}";
        }

        $ret = [];
        $midnight = strtotime('midnight'); //当天凌晨
        if (!$last_srv_id || $last_srv_id == '') { // 客户端没有指定最后服务器ID
            $last_srv_id = $this->getLastLoginSrv($roles);
        }

        $default_flag = -1; //默认服标记
        $todayDefaultSet = []; //当天oms手动设置默认标记
        $midnight = strtotime('midnight'); //当天凌晨
        $todayEnd = $midnight + 86400;
        $new_flag = -1; //新服标记
        $recomed_flag = -1; //推荐服标记
        $last_login_flag = -1; //最后登陆标记
        $white_ip_flag = $this->isWhiteIp() || $this->isWhiteAccount($account);
        //服务器信息
        $server_list = $this->db->getAll("select platform, zone_id, zone_name name, host, ip, port, open_time, is_default,
        is_first as first_zone, srv_status, recomed, isnew, is_maintain as maintain, main_platform, main_zone_id 
        from game_servers where {$where} order by open_time desc");
        $tmp_arr = array();
        if (!$white_ip_flag) {
            foreach ($server_list as $k => $srv) {
                if ($srv['open_time'] > time() + 3600 * 12) { //不在白名单里，且不未开服直接过滤掉
                    if (count($server_list) > 1) {
                        unset($server_list[$k]);
                        continue;// array_splice($server_list, $k, 1); //如果服务器列表长度大于1会处理
                    }
                }
                if($srv['platform'] == "verifyios" && $srv['zone_id'] != 1){
                    unset($server_list[$k]);
                    continue;
                }
                $tmp_arr[] = $srv;
            }
        } else {
            $tmp_arr = $server_list;
        }
        $ret['server_list'] = $tmp_arr;
        $has_role_srv_num = 0; // 当前账号存在角色的服务器数量
        $charge_msg = "";
        // if($platform == 'symix' || $platform == "symix2"){
        //     $charge_msg = Gold::accountCharge($this->db, $account, $acc_group, $channel_id);
        // }
        $group_srv_max = 100; // 多少个服一组
        //先过进行白名单过滤
        foreach ($ret['server_list'] as $k => &$v) {
            if ($white_ip_flag && $v['open_time'] > time()) { //在白名单，把未开服的改成已经开服
                $v['open_time'] = time() - 3600;
            }

            if ($recomed_flag < 1 && $v['open_time'] <= time()) { //最新的2个服为推荐服,2个之外可以通过oms后台编辑
                $v['recomed'] = 1; //是否推荐，1：推荐
                $recomed_flag++;
            }
            //最新的2个服为新服,2个之外可以通过oms后台编辑
            if ($new_flag < 0 && $v['open_time'] <= time()) {
                $v['isnew'] = 1;
                $new_flag++;
            }
            $v['maintain'] = (int)$v['maintain'];
            if ($white_ip_flag) {
                $v['maintain'] = 0;
            }

            $v['begin_time'] = $v['open_time'];
            // if($account == "1348358" || $platform == 'release2' || $channel_id == '45_1'){
            // }else{
            //     $v['ip'] = $v['host'];
            // }
            //角色信息
            $srv_id = $v['platform'] . '_' . $v['zone_id'];
            $v['roles'] = isset($roles[$srv_id]) ? [$roles[$srv_id]] : [];
            if (isset($roles[$srv_id])){
                $has_role_srv_num++;
            }

            if($offset_zoneid > 0 && $v['platform'] == 'symlf'){
                $zone_id = (int)$v['zone_id'] - $offset_zoneid;
                $v['zone_id'] = $zone_id.'';
                $v['srv_id'] = $srv_id;

                $group_id = floor(($zone_id - 1) / $group_srv_max) + 1;
                $v['group_id'] = $group_id;
                $v['group_num'] = $zone_id - ($group_id - 1) * $group_srv_max;
            }else{
                $zone_id = (int)$v['zone_id'];
                $v['srv_id'] = $srv_id;

                $group_id = floor(($zone_id - 1) / $group_srv_max) + 1;
                $v['group_id'] = $group_id;
                $v['group_num'] = $zone_id - ($group_id - 1) * $group_srv_max;
            }

            //最后登陆服标记
            if ($srv_id == $last_srv_id) $last_login_flag = $k;
            //默认服标记
            // if ($srv_id == "manling_19") $default_flag = $k;
            if ($default_flag < 0 && $v['open_time'] <= time()) $default_flag = $k;
            //当天OMS设置多个默认服标记
            if ($v['is_default'] == 1 && $v['open_time'] >= $midnight && $v['open_time'] < $todayEnd) $todayDefaultSet[] = $k;
            unset($v['open_time'], $v['srv_status'], $v['is_default']);
        }

        //默认服
        switch (true) {
            case !empty($todayDefaultSet): //当天手动设置默认标记
                $srv_num = count($todayDefaultSet);
                if ($srv_num > 1) { //多个默认服的情况下,随机分配一个默认服
                    $key = mt_rand(0, $srv_num - 1);
                    $ret['default_zone'] = $ret['server_list'][$todayDefaultSet[$key]];
                } else {
                    $ret['default_zone'] = $ret['server_list'][$todayDefaultSet[0]];
                }
                $ret['default_zone']['recomed'] = 1;
                $ret['default_zone']['isnew'] = 1;
                break;
            // case !empty($roles) && $last_login_flag >= 0:
            case $last_login_flag >= 0:
                $ret['default_zone'] = $ret['server_list'][$last_login_flag];
                break;
            case $default_flag >= 0:
                $ret['default_zone'] = $ret['server_list'][$default_flag];
                break;
            case !empty($ret['server_list']):
                $ret['default_zone'] = end($ret['server_list']);
                break;
            default:
                $ret['default_zone'] = array();
                break;
        }
        $ret['len'] = count($ret['server_list']);
        $ret['auto_enter'] = 0; // ($has_role_srv_num==0)?1:0;  // 是否自动进入默认服务器
        $ret['new_account'] = ($has_role_srv_num==0 && empty($roles))?1:0;  // 是否为新账号
        $ret['offset_zoneid'] = $offset_zoneid;  // 服务器id偏移值
        $ret['ip'] = clientIp();
        $ret['charge_msg'] = $charge_msg;
        $ret['sign'] = $list_sign;
        if ($startIdx >= 0 && $num > 0) { // 分页提取
            $ret['start'] = $startIdx;
            $ret['num'] = $num;
            $ret['server_list'] = array_splice($ret['server_list'], $startIdx, $num);
        }
        return $ret;
    }

    /**
     * 删除
     */
    public function del()
    {
        $sign = Api::getVar('sign');
        $ts = Api::getInt('ts');
        if ($sign != md5($GLOBALS['cfg']['secret_key'] . $ts)) Api::out(1, 'sign error');

        //删除
        $platform = Api::getVar('platform');
        $zone_id = Api::getInt('zone_id');
        $ret = $this->db->delete('game_servers', array('platform' => $platform, 'zone_id' => $zone_id));

        if ($ret) {
            Api::out(0, 'success');
        } else {
            Api::out(1, 'fail');
        }
    }

    /**
     * 设置服务器信息（添加、更新）
     */
    public function set()
    {
        $sign = Api::getVar('sign');
        $ts = Api::getInt('ts');
        if ($sign != md5($GLOBALS['cfg']['secret_key'] . $ts)) Api::out(1, 'sign error');

        $fields = array(
            'id' => 'int',
            'platform' => 'var',
            'zone_id' => 'int',
            'zone_name' => 'var',
            'host' => 'var',
            'ip' => 'var',
            'open_time' => 'int',
            'port' => 'int',
            'srv_status' => 'int',
            'is_first' => 'int',
            'recomed' => 'int',
            'isnew' => 'int',
        );
        $gs = Api::getMap('gs', $fields);

        $ret = $this->db->replace('game_servers', $gs);

        if ($ret) {
            Api::out(0, 'success');
        } else {
            Api::out(1, 'fail');
        }
    }

    /**
     * 内部获取所有服务器列表 json返回
     */
    public function all()
    {
        $ret = $this->all_servers();
        Api::out(0, $ret);
    }

    /** 
    *    判断存在角色的服是否有比指定服号小的
    */
    public function check_min_srv($roles, $min)
    {
        if(empty($roles) || !is_array($roles)) return false;
        foreach ($roles as $srv_id=>$role) {
            $arr = explode('_', $srv_id);
            if(isset($arr[1]) && (int)$arr[1] <= $min){
                return true;
            }
        }
        return false;
    }

    /**
    * 获取账号分组
    */
    public function acc_group($channel_id)
    {
        if($channel_id == "release2"){
            return "0";
        }elseif($channel_id == "syios_djsdmm"){
            return "6";
        }elseif($channel_id == "syios_smzhs"){
            return "3";
        }elseif(preg_match('/^syios_.*/',$channel_id)){
            return "3";
        }elseif(preg_match('/^(16_|47_).*/',$channel_id)){
            return "1";
        }elseif(preg_match('/^66_.*/',$channel_id)){
            return "7";
        }elseif(preg_match('/^tanwan.*/',$channel_id)){
            return "50";
        }elseif(preg_match('/^bingniao.*/',$channel_id)){
            return "51";
        }elseif(preg_match('/^6kw.*/',$channel_id)){
            return "52";
        }elseif(preg_match('/^9377.*/',$channel_id)){
            return "53";
        }elseif(preg_match('/^ssgc.*/',$channel_id)){
            return "53";
        }
        return "2";
    }

    /**
     * 内部获取所有服务器列表 luatable返回
     */
    public function all2()
    {
        $ret = $this->all_servers();
        Api::outlua(0, $ret);
    }

    /*
     *  获取所有服务器信息
     */
    public function all_servers()
    {
        $account = Api::getVar('account'); //角色账号
        /*$sign    = Api::getVar('sign');
        if($sign != md5($GLOBALS['cfg']['secret_key'].$account)) Api::out(1, 'sign error');*/
        $channel_id = Api::getVar('chanleId');
        $last_srv_id = Api::getVar('srvid');
//		$last_srv_id = '';
        $startIdx = Api::getInt('start');
        $num = Api::getInt('num');
        if ($channel_id != 'demo') Api::out(1, '渠道号错误');

        $where = 'srv_status!=1';
        $platformstr = "('release', 'sy', 'sygame', 'sygame2')";
        if($channel_id == 'release'){
            $where .= " and platform in $platformstr";
        }else{
            $where .= " and platform not in $platformstr";
        }

        $ret = [];
        //获取角色信息
        $acc_group = $this->acc_group($channel_id);
        $roles = array_merge(
            Role::getRoles($account, "0")
            ,Role::getRoles($account, "1")
            ,Role::getRoles($account, "2")
            ,Role::getRoles($account, "3")
            ,Role::getRoles($account, "6")
        );
        if (!$last_srv_id || $last_srv_id == '') { // 客户端没有指定最后服务器ID
            $last_srv_id = $this->getLastLoginSrv($roles);
        }

        $default_flag = -1; //默认服标记
        $new_flag = -1; //新服标记
        $recomed_flag = -1; //推荐服标记
        $last_login_flag = -1; //最后登陆标记
        //服务器信息
        $ret['server_list'] = $this->db->getAll("select platform,zone_id,zone_name name,host,port,open_time,is_first as first_zone,srv_status,recomed,isnew, is_maintain as maintain, main_platform, main_zone_id
        from game_servers where {$where} order by open_time desc");
        $other_servers = array(
        //    array('platform'=>'release', 'zone_id'=>1, 'name'=>'稳定服', 'host'=>'s1.release.huanxiang.yangsugame.com','port'=>9611,'open_time'=>0)
        ); 
        $ret['server_list'] = array_merge($other_servers, $ret['server_list']);
        $has_role_srv_num = 0; // 当前账号存在角色的服务器数量
        $group_srv_max = 100; // 多少个服一组
        // $ret['server_list'] = array_merge($ret['server_list'], $other_servers);
        //先过进行白名单过滤
        foreach ($ret['server_list'] as $k => &$v) {
            if ($v['open_time'] > time()) { //把未开服的改成已经开服
                $v['open_time'] = time() - 3600;
            }

            if ($recomed_flag < 1) { //最新的2个服为推荐服,2个之外可以通过oms后台编辑
                $v['recomed'] = 1; //是否推荐，1：推荐
                $recomed_flag++;
            }
            //最新的2个服为新服,2个之外可以通过oms后台编辑
            if ($new_flag < 0) {
                $v['isnew'] = 1;
                $new_flag++;
            }
            //是否维护状态
            $v['maintain'] = 0;   // 这里测试用 不设置 为维护
            $v['begin_time'] = $v['open_time'];
            //角色信息
            $srv_id = $v['platform'] . '_' . $v['zone_id'];
            $v['roles'] = isset($roles[$srv_id]) ? [$roles[$srv_id]] : [];
            if (isset($roles[$srv_id])){
                $has_role_srv_num++;
            }

            $zone_id = (int)$v['zone_id'];

            $group_id = floor(($zone_id - 1) / $group_srv_max) + 1;
            $v['group_id'] = $group_id;
            $v['group_num'] = $zone_id - ($group_id - 1) * $group_srv_max;

            //最后登陆服标记
            if ($srv_id == $last_srv_id) $last_login_flag = $k;
            //默认服标记
            // if($default_flag < 0 && $v['open_time'] <= time()) $default_flag = $k;
            if ($srv_id == "release_1") $default_flag = $k;
            if ($srv_id == "release2_1") $default_flag = $k;
            if ($v['recomed'] == '0') unset($v['recomed']);
            if ($v['isnew'] == '0') unset($v['isnew']);
            if ($v['first_zone'] == '0') unset($v['first_zone']);
            if ($v['maintain'] == 0) unset($v['maintain']);
            if (empty($v['roles'])) unset($v['roles']);
            if ($v['platform'] == $v['main_platform'] && $v['main_zone_id'] == $v['zone_id']){
                unset($v['main_platform'], $v['main_zone_id']);
            }
            unset($v['open_time'], $v['srv_status']);
        }

        //默认服
        switch (true) {
            // case !empty($roles) && $last_login_flag >= 0:
            case $last_login_flag >= 0:
                $ret['default_zone'] = $ret['server_list'][$last_login_flag];
                break;
            case $default_flag >= 0:
                $ret['default_zone'] = $ret['server_list'][$default_flag];
                break;
            case !empty($ret['server_list']):
                $ret['default_zone'] = end($ret['server_list']);
                break;
            default:
                $ret['default_zone'] = array();
                break;
        }
        $ret['len'] = count($ret['server_list']);
        $ret['auto_enter'] = 0; // ($has_role_srv_num==0)?1:0;  // 是否自动进入默认服务器
        $ret['new_account'] = 0; // ($has_role_srv_num==0)?1:0;  // 是否为新账号
        $ret['offset_zoneid'] = 0;  // 服务器id偏移值
        $ret['ip'] = clientIp();
        if ($startIdx >= 0 && $num > 0) { // 分页提取
            $ret['start'] = $startIdx;
            $ret['num'] = $num;
            $ret['server_list'] = array_splice($ret['server_list'], $startIdx, $num);
        }
        return $ret;
    }


    /**
     * eYou游戏获取服务器列表
     */
    public function server_list_eyou()
    {
        $game_id = Api::getInt('gameid');
        if(empty($game_id)) Api::outEYou(-1, "无效的游戏编号");

        $pfs = [];
        switch ($game_id) {
            case 1060:
                $pfs[] = 'eyou';
                break;
        }

        if(empty($pfs)) Api::outEYou(-2, '查询异常');

        $where = "platform in('" . implode("','", $pfs) . "')";
        //eYou服务器信息
        $ret = $this->db->getAll("select platform, zone_id, zone_name 
            from game_servers 
            where {$where} order by open_time desc,zone_id desc");

        $data = [];
        foreach ($ret as $k => $v) {
            $data[] = [
                'Sid' =>  $v['zone_id'],
                'Sname' => $v['zone_name']
            ];
        }

        Api::outEYou(1, $data);
    }

    /**
     * sy平台官网获取服务器列表
     */
    public function syServerList()
    {
        //获取参数
        $time = Api::getVar('ts');
        $sign = Api::getVar('sign');
        $platform = Api::getVar('platform');
        $params = [
            'ts' => $time,
            'sign' => $sign,
            'platform' => $platform,

        ];
        //密钥认证
        if($sign != self::createSign($params)) self::outSy(-1, '验证错误');

        $lists = $this->db->getAll("select platform, zone_id, zone_name 
            from game_servers 
            where srv_status in(0, 2, 3) and platform = 'symlf' order by open_time desc,zone_id desc");


        $data = [];
        foreach ($lists as $k => $v) {
            $data[] = [
                'server_id' => $v['platform'] . '_' . $v['zone_id'],
                'server_name' => $v['zone_name'] ,
            ];
        }

        self::outSy(0, '成功', $data);
    }

    /**
     * sy平台官网sign 验证
     * @param $params
     * @return string
     */
    public function createSign($params)
    {
        unset($params['sign']);
        ksort($params);

        $secret = 'ZMIUClkQieIL7tElz7VSjVrT';
        $str_arr = array();
        foreach ($params as $key => $val) {
            $str_arr[] = rawurlencode($key) . "=" . rawurlencode($val);
        }

        $signStr = implode('&', $str_arr);
        return sha1($signStr . $secret);
    }

    /**
     * 获取已经玩过的区服
     */
    public function syRolePlayed()
    {
        $platform = Api::getVar('platform');
        $time  = Api::getInt('ts');
        $uid  = Api::getVar('uid');
        $sign  = Api::getVar('sign');
        $params = [
            'ts' => $time,
            'sign' => $sign,
            'uid' => $uid,
            'platform' => $platform,

        ];
        //密钥认证
        if($sign != self::createSign($params)) self::outSy(-1, '验证错误');

        //获取角色信息
        $account = $uid;
        $roles = array_merge(
            Role::getRoles($account, "1"),
            Role::getRoles($account, "3"),
            Role::getRoles($account, "6")
        );

        $data = [];
        foreach ($roles as $k => $v) {
            $server = $this->db->getRow("select zone_name from game_servers where concat(platform,'_',zone_id)='{$k}' and srv_status in(0, 2, 3) and platform = 'symlf' order by open_time desc,zone_id desc");
            if(empty($server)) continue;
            $data[] = [
                'server_id' => $k,
                'server_name' => $server['zone_name'] ,
            ];
        }

        self::outSy(0, '成功', $data);
    }

    /**
     * 接口信息返回
     * @param $code
     * @param $msg
     * @param array $data
     */
    private function outSy($code, $msg, $data = []) {
        echo json_encode(array('code' => $code, 'message' => $msg, 'data' => $data));
        exit;
    }
}
