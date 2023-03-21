<?php
/**
 * User: xiaoqing Email: liuxiaoqing437@gmail.com
 * Date: 2016/2/27
 * Time: 11:49
 * 同步服务器列表
 */

//初始化当前环境
require realpath(__DIR__ . '/../') . '/www/_init.php';


$remote_sql_file = '/tmp/sync_register_severs.txt';
//导出txt文件
$local_file = VAR_DIR . "/servers.txt";

//生成远程txt scp到本地 导入数据库
$export_cmd = "mysql -uroot -p`cat /data/save/mysql_root` fswy_oms -e \"SELECT z.id, z.platform,z.zone_id,z.zone_name,z.host,m.ip,z.open_time,z.port,z.srv_status, z.is_first,z.recomed,z.isnew,z.is_maintain
FROM oms_zone z LEFT JOIN oms_node n ON z.platform = n.platform AND z.zone_id = n.zone_id
LEFT JOIN oms_machine m ON n.machine = m.id WHERE n.type='zone' AND z.approved = 1 AND z.installed = 1 into outfile '{$remote_sql_file}'\"  && echo 'ok'";
$ret = Cline::ssh('115.159.86.159', '2020', $export_cmd);
if($ret == 'ok') {

    Cline::scp_from('115.159.86.159', '2020', $remote_sql_file, $local_file);
    if(is_file($local_file)) {
        //导入
        $import_cdm = "mysql -uroot -p`cat /data/save/mysql_root` dldl_register -e \"load data local infile '{$local_file}' replace into table game_servers\" && echo 'ok'";
        Cline::cmd($import_cdm);
        //删除远程临时文件
        Cline::ssh('115.159.86.159', '2020', "rm -f {$remote_sql_file}");
        //无论失败成功都删除
        Cline::cmd("rm -f {$local_file}");
    }
}


