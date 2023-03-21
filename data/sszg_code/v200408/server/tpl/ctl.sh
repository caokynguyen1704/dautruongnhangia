#/bin/bash
# ---------------------------------------------------------
# 节点管理工具
# @author yeahoo2000@gmail.com
# ---------------------------------------------------------

DEBUG=1 # 开发模式设置(0:关闭 1:开启)

# 初始化
init(){
    cfg_init
    cfg_check

    # 节点工作目录
    ROOT=${cfg['root']}
    # 代码库所处目录，例: /data/game.dev
    CODE_PATH=${cfg['code_path']}/${cfg['ver']}

    # nginx虚拟主机配置存放目录
    if [ -d "/data/conf/nginx/vhost" ]; then
        VHOST_PATH="/data/conf/nginx/vhost"
    else
        VHOST_PATH="/usr/local/webserver/nginx/conf/vhost"
    fi
    if [ "${cfg['combine']}" = "" ]; then
        cfg['combine']='<<"{{platform}}_{{zone_id}}">>'
    fi
    # VHOST_PATH="/etc/nginx/sites-enabled"
    if [ "${cfg['combine']}" = "" ]; then
        cfg['combine']='<<"{{platform}}_{{zone_id}}">>'
    fi

    # erl程序所在路径
    ERL=erl
    if [ -f /opt/erlang/otp19.2/lib/erlang/bin/erl ]; then
        ERL=/opt/erlang/otp19.2/lib/erlang/bin/erl
    elif [ -f /usr/local/erlang19/bin/erl ]; then
        ERL=/usr/local/erlang19/bin/erl
    fi
    # erl节点间连接端口范围
    ERL_PORT_MIN=40100
    # erl节点间连接端口范围
    ERL_PORT_MAX=44000

    # 进入节点根目录
    cd ${ROOT} || exit 1
}

# 配置信息检查
cfg_check(){
    if [ -e "${cfg['root']}" ]; then
        return
    else if [ "${DEBUG}" != "1" ]; then
            echo ">> [错误]脚本未进行正确配置，如果希望以开发模式工作请将DEBUG设置为'1'，并填写关键配置"
            exit 1
        fi
    fi
    # 获取当前脚本文件所在路径，作为根目录
    SOURCE="${BASH_SOURCE[0]}"
    while [ -h "$SOURCE" ]; do
        DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
        SOURCE="$(readlink "$SOURCE")"
        [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE"
    done
    root="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

    # 调试模式下的默认配置，如有必要请手动修改
    # cygwin下需使用dos格式才能正常，如:d:/game.dev/zone/mxfx_local_1
    cfg['root']="${root}"
    # cygwin下需使用dos格式才能正常，如:d:/game.dev
    cfg['code_path']="/data/game.dev"
    cfg['nodename']="s1@local.dev"
    cfg['platform']="local"
    cfg['zone_id']="0"
    cfg['type']="zone"
    cfg['host']="local.dev"
    cfg['port']="8000"
    cfg['cookie']="n2su1x83b16,2uw2"
    cfg['srv_key']="a2bi2Zc89n129BaE3N0"
    cfg['other_key']="a2bi2Zc89n129BaE3N0"
    cfg['game_name']="mxfx"
    cfg['zone_name']="时空神域开发服"
    cfg['lang']="zh_CN"
    cfg['fcm']="0"
    cfg['db_host']="127.0.0.1"
    cfg['db_port']="3306"
    cfg['db_name']="mxfx_local_1"
    cfg['db_user']="root"
    cfg['db_pass']="mx52.cn"
    cfg['db_conn_min']="10"
    cfg['db_conn_max']="30"
    cfg['ip']="127.0.0.1"
    cfg['open_time']="1384853886"
    cfg['website']="http://sksy.cc"

    # 替换空变量
    for k in ${!cfg[@]}; do
        cfg[$k]=${cfg[$k]/\{\{*\}\}/}
    done
}

# 配置信息初始化
cfg_init(){
    # 节点根目录
    cfg['root']="{{root}}"
    # 代码存放路径，每一个版本一个目录保存到此路径下
    cfg['code_path']="{{code_path}}"
    # 平台标识
    cfg['platform']="{{platform}}"
    # 区号
    cfg['zone_id']="{{zone_id}}"
    # 节点类型
    cfg['type']="{{type}}"
    # magic cookie
    cfg['cookie']="{{cookie}}"
    # 主域名(这里不要带http://)
    cfg['host']="{{host}}"
    # 合服域名(这里不要带http://)
    cfg['merge_host']="{{merge_host}}"
    # 监听端口
    cfg['port']="{{port}}"

    # 服务器密钥
    cfg['srv_key']="{{srv_key}}"

    # 机器所有者
    cfg['owner']="{{owner}}"
    # 是否禁用
    cfg['disabled']="{{disabled}}"
    # 机器所在地域
    cfg['location']="{{location}}"
    # 外网IP(一般为电信)
    cfg['ip']="{{ip}}"
    # 外网IP1(一般为网通)
    cfg['ip1']="{{ip1}}"
    # 外网IP2(其它)
    cfg['ip2']="{{ip2}}"
    # 内网IP
    cfg['ip_internal']="{{ip_internal}}"
    # ssh用户名
    cfg['ssh_user']="{{ssh_user}}"
    # ssh访问端口
    cfg['ssh_port']="{{ssh_port}}"

    # 节点名称
    cfg['nodename']="{{nodename}}"
    # 中央服节点名称
    cfg['center_node']="{{center_node}}"
    # 所在物理机器编号
    cfg['machine']="{{machine}}"
    # 该类型节点的扩展信息
    cfg['ext']="{{ext}}"
    # 数据库地址(如果该节点不使用数据库则留空)
    cfg['db_host']="{{db_host}}"
    # 数据库端口
    cfg['db_port']="{{db_port}}"
    # 数据库名称
    cfg['db_name']="{{db_name}}"
    # 数据库用户名
    cfg['db_user']="{{db_user}}"
    # 数据库密码
    cfg['db_pass']="{{db_pass}}"
    # 数据库最小连接数
    cfg['db_conn_min']="{{db_conn_min}}"
    # 数据库最大连接数
    cfg['db_conn_max']="{{db_conn_max}}"

    # 当前版本(此版本号用于标识当前版本，由安装工具自动填写)
    cfg['ver']="{{ver}}"

    # 游戏代号
    cfg['game_name']="{{game_name}}"
    # 游戏区名称
    cfg['zone_name']="{{zone_name}}"
    # 语言版本
    cfg['lang']="{{lang}}"
    # 时区
    cfg['timezone']="{{timezone}}"
    # 合服信息
    cfg['combine']='{{combine}}'
    # 开服时间
    cfg['open_time']="{{open_time}}"
    # 合服时间
    cfg['merge_time']="{{merge_time}}"

    # ssl
    cfg['ssl']=""
    # 组ID 
    cfg['group_id']="{{group_id}}"
    # 是否主服
    cfg['is_main']="{{is_main}}"
    # 是否发送注册码
    cfg['need_register']="{{need_register}}"
    # 是否维护中
    cfg['is_maintain']="{{is_maintain}}"
    # 日志组别(1:正式 2:海外 其它:测试)
    cfg['flume_host_group']="{{flume_host_group}}"
}

# 启动节点
fun_start(){
    if [ ! -e dets ]; then
        echo ">> [错误]当前目录下未安装节点，请先执行安装操作"
        exit 1
    fi
    if [ $(screen -ls | grep "${cfg['nodename']}" | wc -l ) -le 0 ]; then  #停服更新时对日志做搬移处理
        if [ $(cat screenlog.0 | wc -l ) -gt 200000 ]; then 
            echo ">> screenlog.0日志备份清理处理"
            rm -f screenlog.1
            mv screenlog.0 screenlog.1
        fi
    fi
    if $(in_cygwin); then
        werl -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} +P 204800 +K true -smp enable -hidden  -config sys.config -pa ${CODE_PATH}/server/ebin -name ${cfg['nodename']} -s main start -extra ${cfg['type']} &
    else
        start_file=${ROOT}/start.sh
        CMD="${ERL} -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} +P 204800 +K true -smp enable -hidden -config sys.config -pa ${CODE_PATH}/server/ebin -name ${cfg['nodename']} -s main start -extra ${cfg['type']}"
        cat > ${start_file} <<EOF
#!/bin/bash
cd ${ROOT}
ulimit -SHn 102400
${CMD}
EOF
        chmod +x ${start_file}
        screen -dmSL ${cfg['nodename']} -s ${start_file}
        echo ">> 节点 ${cfg['nodename']} 正在启动中，如果想观察启动过程请使用以下命令进行启动:"
        echo ">> ./ctl.sh start && ./ctl.sh shell"
    fi
}

# 清理session
fun_clean_sess(){
    rm -rf var/sess/*
    echo "-------------------------------------------"
    echo ">> php session清理完成"
}

# 关闭节点
fun_stop(){
    if $(in_cygwin); then
        echo ">> cygwin下不支持此命令"
        exit
    fi
    ${ERL} -noshell -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} -hidden -pa ${CODE_PATH}/server/ebin -name stop_${cfg['nodename']} -setcookie ${cfg['cookie']} -eval "io:setopts([{encoding,unicode}])" -s main stop_from_shell -extra ${cfg['nodename']}
    fun_clean_sess
    echo "-------------------------------------------"
    num=0
    while(( $num<30 )); do
        if [ $(screen -ls | grep "${cfg['nodename']}" | wc -l ) -gt 0 ]; then 
            log=$(tail -n 10 ${ROOT}/screenlog.0)
            if [ $(echo $log | grep "type: temporary" | wc -l ) -gt 0 ] || [ $(echo $log | grep "Application inets exited with reason: stopped" | wc -l ) -gt 0 ] || [ $(echo $log | grep "Application main exited with reason: stopped" | wc -l ) -gt 0 ]; then
                pid=`ps aux | grep "\-name ${cfg['nodename']}"|grep erl|awk '{print $2}'` 
                if [ "$pid" != "" ]; then kill -9 $pid && echo "节点[${cfg['nodename']}]已关闭，直接kill掉进程:$log"; fi
                exit 0
            fi
            sleep 0.5
            let "num++"
        else
            echo ">> 节点 ${cfg['nodename']} 关闭完成"
            exit 0
        fi
    done
    echo -e ">> 节点 ${cfg['nodename']} \e[91m无法关闭节点，节点还在正常运行中...\e[0;0m"
}

# 使用remsh方式进入控制台
fun_remsh(){
    n=remsh_$1_${cfg['nodename']}
    ${ERL} -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} -hidden -pa ${CODE_PATH}/server/ebin -name $n -setcookie ${cfg['cookie']} -remsh ${cfg['nodename']}
}

# 进入该节点的控制台
fun_shell(){
    if $(in_cygwin); then
        echo ">> cygwin下不支持此命令"
        exit
    fi
    screen -r "${cfg['nodename']}"
}

# 刷新用户名 
fun_set_db_user(){
    if [ "${cfg['db_user']}" != "root" ]; then
        echo "更新数据库[${cfg['db_name']}]用户[${cfg['db_user']}]权限"
        sql="GRANT ALL PRIVILEGES ON ${cfg['db_name']}.* TO '${cfg['db_user']}'@'127.0.0.1' IDENTIFIED BY '${cfg['db_pass']}' WITH GRANT OPTION;"
        sql="$sql GRANT ALL PRIVILEGES ON ${cfg['db_name']}.* TO '${cfg['db_user']}'@'localhost' IDENTIFIED BY '${cfg['db_pass']}' WITH GRANT OPTION;"
        sql="$sql FLUSH PRIVILEGES;"
        mysql -uroot -p$(cat /data/save/mysql_root) -e "$sql"
    fi
}

# 安装节点
fun_install(){
    if [ -e dets ]; then
        echo ">> [错误]当前数据目录非空，可能是已经执行过安装过程，请删除后重试"
        exit 1
    fi
    # 处理模板
    tpls=( 'env_zone.cfg' 'vhost.conf' 'main.app' 'env.php' 'default.cfg.php' 'zone.sql' 'elog.config' 'emysql.app' 'lager.app' 'sys.config' 'goldrush.app' 'ranch.app' )
    fun_set_db_user
    fun_ssl
    for v in ${tpls[*]};do
        file="${CODE_PATH}/server/tpl/${v}"
        if [ ! -e $file ]; then
            echo ">> [错误]找不到模板文件: ${file}"
            exit 1
        fi
        # 替换模板变量
        lines=$(<"${file}")
        for k in ${!cfg[@]}; do
            lines=${lines//\{\{$k\}\}/${cfg[$k]}}
        done
        # 替换空变量并生成文件
        echo "${lines}" | sed -e "s/{{.*}}//" > ${v}
    done
    # 安装数据库
    mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -e"create database ${cfg['db_name']}"
    if [ $? -ne 0 ]; then
        echo ">> [错误]创建数据库时发生异常"
        exit 1
    fi
    mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -e"source ${ROOT}/zone.sql"
    if [ -f "${CODE_PATH}/server/tpl/card.sql" ]; then 
        mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -e"source ${CODE_PATH}/server/tpl/card.sql"
    fi
    if [ $? -ne 0 ]; then
        echo ">> [错误]导入数据库表结构时发生异常"
        exit 1
    fi
    # 处理相关文件
    mkdir -p dets var/sess log/pack log_file role_face_photo/${cfg['platform']}_${cfg['zone_id']} ${CODE_PATH}/web/www/role_face_photo
    mv env_zone.cfg env.cfg
    ln -s ${CODE_PATH}/web/www .
    # ln -s ${CODE_PATH}/server/priv .
    fun_face_link
    rm -f zone.sql
    chmod -R 777 ${cfg['root']}/var
    echo ">> 正在打开端口:${cfg['port']}，如果失败可手动操作完成，无需重新安装"
    iptables -A RH-Firewall-1-INPUT -p tcp -m state --state NEW -m tcp --dport ${cfg['port']} -j ACCEPT && service iptables save && service iptables restart && sysctl -p
    rm -f sys_notice.sql
    echo ">> 正在配置web服务器，如果失败可手动操作完成，无需重新安装"
    rm -f ${VHOST_PATH}/${cfg['host']}.conf && ln -s ${cfg['root']}/vhost.conf ${VHOST_PATH}/${cfg['host']}.conf
    /root/nginx_reload
    echo ">> 节点安装完成"
}

# 删除节点
fun_uninstall(){
    fun_check_screen
    role_num=$(mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -ss -e"select count(*) from role")
    if [ $? -ne 0 ]; then
        echo ">> [错误]访问数据库失败，无法获取角色数量"
        exit 1
    fi
    if [ "$role_num" != "" ] && [ "$role_num" = "$1" ]; then
        mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -e"drop database ${cfg['db_name']}"
        if [ $? -ne 0 ]; then
            echo ">> [错误]访问数据库失败，无法删除数据库，如需继续请手动操作"
            exit 1
        fi
        echo ">> 正在关闭端口:${cfg['port']}"
        iptables -D RH-Firewall-1-INPUT -p tcp -m state --state NEW -m tcp --dport ${cfg['port']} -j ACCEPT && service iptables save && service iptables restart && sysctl -p
        rm -rf screenlog.* face replay replay_share dets priv log log_file var www env.cfg env.php default.cfg.php emysql.app main.app vhost.conf start.sh ${VHOST_PATH}/${cfg['host']}.conf ${CODE_PATH}/web/www/face/${cfg['platform']}_${cfg['zone_id']} ${CODE_PATH}/web/www/replay/${cfg['platform']}_${cfg['zone_id']}

        echo ">> 节点${cfg['nodename']}已经删除"
    else
        echo ">> [错误]删除参数中必须带有当前节点的角色数量，角色数量为[${role_num}]"
    fi
}

# 热更新节点
fun_update_cfg(){
    # 处理模板
    tpls=( 'env_zone.cfg' 'vhost.conf' 'elog.config' 'main.app' 'env.php' 'default.cfg.php' )
    fun_set_db_user
    fun_ssl
    for v in ${tpls[*]};do
        file="${CODE_PATH}/server/tpl/${v}"
        # 替换模板变量
        lines=$(<"${file}")
        for k in ${!cfg[@]}; do
            lines=${lines//\{\{$k\}\}/${cfg[$k]}}
        done
        # 替换空变量并生成文件
        echo "${lines}" | sed -e "s/{{.*}}//" > ${v}
    done
    rm -f env.cfg && mv env_zone.cfg env.cfg
    rm -f www && ln -s ${CODE_PATH}/web/www .
    mkdir -p ${cfg['root']}/role_face_photo/${cfg['platform']}_${cfg['zone_id']} ${CODE_PATH}/web/www/role_face_photo
    fun_face_link
    /root/nginx_reload
    if [ $(cat /etc/sysconfig/iptables | grep -e "--dport ${cfg['port']}" | wc -l ) -eq 0 ]; then 
        iptables -A RH-Firewall-1-INPUT -p tcp -m state --state NEW -m tcp --dport ${cfg['port']} -j ACCEPT && service iptables save && service iptables restart && sysctl -p
    fi
    echo ">> 所有配置文件更新完成"
    if [ "$1" != "false" ] && [ $(screen -ls | grep "${cfg['nodename']}" | wc -l ) -gt 0 ]; then 
        fun_exec "sys_env:reload:[]"
    fi
}

# 创建所有全服平台目录
fun_create_combine(){
    dir=${cfg['root']}/$1
    # cfg['combine']='{<<"4399">>,1},{<<"4399">>,2},{<<"4399">>,3},{<<"4399">>,4},{<<"4399">>,5},{<<"4399">>,6},{<<"4399">>,7},{<<"4399">>,8},{<<"4399">>,9}'
    combinestr=${cfg['combine']}
    combinestr=${combinestr//\ /}
    combinestr=${combinestr//\{\<\<\"/}
    combinestr=${combinestr//\"\>\>\,/\_}
    combinestr=${combinestr//\}\,/\ }
    combinestr=( ${combinestr//\}/} )
    for k in ${combinestr[@]}; do 
        # echo ${dir}/${k}
        mkdir -p ${dir}/${k}
    done
}

# 更新face链接
fun_face_link(){
    for dir in $(ls ${cfg['root']}/role_face_photo); do 
        if [ -d "${cfg['root']}/role_face_photo/${dir}" ] && [ "${dir}" != "." ]; then 
            rm -f ${CODE_PATH}/web/www/role_face_photo/${dir}
            ln -s ${cfg['root']}/role_face_photo/${dir} ${CODE_PATH}/web/www/role_face_photo/${dir}
        fi
    done
}

# 热更新节点
fun_hotswap(){
    noshell=""  # shell后台多线程热更要求为noshell
    if [ "$1" != "" ]; then 
        noshell=" -noshell"
    fi
    ${ERL}${noshell} -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} -hidden -pa ${CODE_PATH}/server/ebin -name hotswap_${cfg['nodename']} -setcookie ${cfg['cookie']} -eval "io:setopts([{encoding,unicode}])" -s main hotswap_from_shell -extra ${cfg['nodename']}
    echo ">> 热更新完成"
}

# 执行指定脚本 
fun_exec(){
    # 要求传入格式为 Mod:Fun:Args(中间不带空格符号)
    line=$1
    mod=$(echo $line | cut -d':' -f1)
    fun=$(echo $line | cut -d':' -f2)
    args=$(echo $line | cut -d':' -f3)
    args=${args//\#/:}
    if [[ $(echo $mod | grep "^[a-z0-9_]\{1,50\}$") = "" ]]; then 
        echo ">> [错误]模块名格式不正常：${mod}"
        exit 1
    fi
    if [[ $(echo $fun | grep "^[a-z0-9_]\{1,50\}$") = "" ]]; then 
        echo ">> [错误]方法名格式不正常：${fun}"
        exit 1
    fi
    if [[ $(echo $args | grep "^\[.*\]$") = "" ]]; then 
        echo ">> [错误]方法参数格式不正常：${args}"
        exit 1
    fi
    echo ">> 准备执行方法调用 apply(${mod}, ${fun}, ${args})"
    ${ERL} -noshell -kernel inet_dist_listen_min ${ERL_PORT_MIN} -kernel inet_dist_listen_max ${ERL_PORT_MAX} -hidden -pa ${CODE_PATH}/server/ebin -name exec_${cfg['nodename']} -setcookie ${cfg['cookie']} -eval "io:setopts([{encoding,unicode}]),io:format(\"return:~w~n\", [rpc:call('${cfg['nodename']}', ${mod}, ${fun}, ${args})])" -s c q
}

# 清档操作
fun_clean(){
    fun_check_screen
    role_num=$(mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -ss -e"select count(*) from role")
    if [ $? -ne 0 ]; then
        echo ">> [错误]访问数据库失败，无法获取角色数量"
        exit 1
    fi
    if [ "$role_num" != "" ] && [ "$role_num" = "$1" ]; then
        # 排除列表，注意在排除列表外的所有表将会被清空
        exclude=( "mod_notice" "mod_holiday_son" "mod_holiday_total" "mod_white_ip" "sys_notice_board" "mod_white_acc" )
        tabs=( $(mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -ss -e"show tables") )
        for v in ${tabs[@]}; do
            flag=0
            for v1 in ${exclude[@]}; do
                if [ "$v" = "$v1" ]; then
                    flag=1
                fi
            done
            if [ $flag = 0 ]; then
                sql="${sql} truncate table ${v};"
            fi
        done
        mysql -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -D${cfg['db_name']} -ss -e"${sql}"
        if [ $? -ne 0 ]; then
            echo ">> [错误]访问数据库失败"
            exit 1
        fi
        rm -f ./env.dets
        mv dets/env.dets ./
        rm -f dets/*
        mv ./env.dets dets/
        rm -rf var/sess
        rm -rf log
        rm -rf log_file/*
        rm -rf lager_log/*
        rm -rf combat_replay/*
        rm -f screenlog.*
        rm -rf face/${cfg['platform']}_${cfg['zone_id']}/*
        rm -rf replay/${cfg['platform']}_${cfg['zone_id']}/*
        rm -rf replay_share/${cfg['platform']}_${cfg['zone_id']}/*
        mkdir -p log/pack
        mkdir -p var/sess
        chmod -R 777 var/sess
        echo "" > ./srv_clear_flag.txt
        echo ">> 清档操作完成"
    else
        echo ">> [错误]清档时参数中必须带有当前节点的角色数量，角色数量为[${role_num}]"
    fi
}

# 判断运行节点数据库是否还存在 维护辅助工具
fun_run_db(){
    if [ $(screen -ls | grep "${cfg['nodename']}" | wc -l ) -gt 0 ]; then 
        if [ $(mysql  -h${cfg['db_host']} -P${cfg['db_port']} -u${cfg['db_user']} -p${cfg['db_pass']} -e "show databases like '${cfg['db_name']}'" | wc -l ) -eq 0 ]; then 
            echo "服务器节点[${cfg['nodename']}]还在运行中，数据库[${cfg['db_name']}]不存在了"
            exit 1
        fi
    fi
}

fun_check_screen() {
    if [ $(screen -ls | grep "${cfg['nodename']}" | wc -l ) -gt 0 ]; then 
        echo "服务器节点[${cfg['nodename']}]还在运行中，不能进行该操作"
        exit 1
    fi
}

# 收集并打包日志文件
fun_pack(){
    cd ${ROOT}/log/pack 
    # 如果目录非空则进行打包
    if [ $(ls -al | wc -l) -gt 3 ]; then
        tar zcf ${ROOT}/log/log_${cfg['platform']}_${cfg['zone_id']}_$(date +"%y%m%d%H%M%S").tar.gz *.txt && rm -f *.txt
        exit $? 
    fi
    echo "没有文件可打包"
    exit 1
}

##
fun_ssl(){
    if [ "${cfg['platform']}" = "9388" ]; then
        cfg['ssl']="listen 443 ssl;
    ssl on;
    ssl_certificate_key /usr/local/webserver/nginx/conf/wildcard_20131129.key;
    ssl_certificate /usr/local/webserver/nginx/conf/wildcard.pem;"
    fi
}

## 检测是否在cygwin环境中
in_cygwin(){
    os=$(uname)
    [[ "${os:0:3}" == "CYG" ]]; return $?
}

## 命令行帮助
help(){
    echo "start             启动节点"
    echo "stop              关闭节点(同时会自动清理php session)"
    echo "shell             进入控制台，也可使用screen命令直接进入"
    echo "remsh             以remsh方式进入控制台"
    echo "install           安装节点"
    echo "uninstall         删除节点"
    echo "clean             清档操作"
    echo "clean_sess        清理php session，关闭服务器后需清理，不然玩家刷新后可能进入服务器"
    echo "update_cfg        更新所有配置文件"
    echo "hotswap           热更新节点"
    echo "exec              执行指定命令: Mod:Fun:Args"
    echo "pack              收集并打包日志文件"
    echo "log               查看控制台日志"
    echo "check             判断切点是否还在运行中"
    echo "ls                列出所有的erl节点"
    echo "run_db            检查节点是否在运行状态但数据库已被删除(维护辅助工具)"
}

# ------------------------------------------------------
# 执行入口
# ------------------------------------------------------
declare -A cfg
init
case $1 in
    start) fun_start;;
    stop)  fun_stop;;
    shell) fun_shell;;
    remsh) fun_remsh $2;;
    install) fun_install;;
    uninstall) fun_uninstall $2;;
    clean) fun_clean $2;;
    clean_sess) fun_clean_sess;;
    update_cfg) fun_update_cfg $2;;
    hotswap) fun_hotswap $2;;
    exec) fun_exec "${2}";;
    pack) fun_pack;;
    check) fun_check_screen;;
    log) less ./screenlog.0;;
    ls) screen -ls;;
    nodo) exit 0;;
    create_dir) fun_create_combine;;
    run_db) fun_run_db;;
    *)
        echo "未知指令，请使用以下有效指令"
        echo "----------------------------------------------------------"
        help
        exit 1
        ;;
esac
exit 0
