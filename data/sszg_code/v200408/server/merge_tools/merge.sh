#!/bin/bash
# ---------------------------------------------------------
# 合服脚本
# @author shawn 
# ---------------------------------------------------------

# 获取脚本文件所在绝对路径(自动跟踪符号链接)
get_real_path(){
    local source="${BASH_SOURCE[0]}"
    while [ -h "$source" ]; do
        local dir="$( cd -P "$( dirname "$source" )" && pwd )"
        source="$( readlink "$source" )"
        [[ $source != /* ]] && source="$dir/$source"
    done
    echo "$( cd -P "$( dirname "$source" )" && pwd )"
}

## 检测是否在cygwin环境中
in_cygwin(){
    os=$(uname)
    [[ "${os:0:3}" == "CYG" ]]; return $?
}

# 启动合服程序
fun_start(){
    cd $ROOT
    if $(in_cygwin); then
        werl -kernel inet_dist_listen_min 40001 -kernel inet_dist_listen_max 40100 +P 204800 +K true -smp enable -pa /data/sszg_code/v200408/server/ebin1 -name merge@jstm.merge -s merge_main start -extra immediacy &
    else
        ERL=erl
        start_file=./start.sh
        CMD="${ERL} -kernel inet_dist_listen_min 40001 -kernel inet_dist_listen_max 40100 +P 204800 +K true -smp enable -pa /data/sszg_code/v200408/server/ebin1 -name merge@jstm.merge -s merge_main start -extra immediacy"
        cat > ${start_file} <<EOF
#!/bin/bash
ulimit -SHn 102400
${CMD}
EOF
        chmod +x ${start_file}
        screen -dmSL merge@jstm.merge -s ${start_file}
        echo "----------------------------------------------------------------------------------------------"
        echo ">> 节点 ${nodename} 正在启动中，接下来进行进入控制台，操作时请注意。(ctrl+a d退出)"
        echo "----------------------------------------------------------------------------------------------"
        screen -r merge@jstm.merge 
    fi
}

# 进入控制台
fun_shell(){
    screen -r merge@jstm.merge 
}

# 清档指定数据库
fun_truncate(){
    dbname=$1
    n=$2
    role_num=$(mysql -uroot -p$(cat /data/save/mysql_root) -D${dbname} -ss -e"select count(*) from role")
    if [ "$role_num" != "" ] && [ "${role_num//[[:space:]]/}" = "${n//[[:space:]]/}" ]; then
        tabs=( $(mysql -uroot -p$(cat /data/save/mysql_root) -D${dbname} -ss -e"show tables") )
        for v in ${tabs[@]}; do
            sql="${sql} truncate table ${v};"
        done
        mysql -uroot -p$(cat /data/save/mysql_root) -D${dbname} -ss -e"${sql}"
    else
        echo -e "参数中必须带有当前节点的角色数量，角色数量为[\e[92m${role_num}\e[0;0m]"
    fi
}

fun_help(){
    echo "start                 执行合服"
}

## 进入点
ROOT=$(get_real_path)
cmd=$1
shift
case $cmd in
    start) fun_start $@;;
    shell) fun_shell $@;;
    truncate) fun_truncate $@;;
    *)
        echo "未知指令，请使用以下有效指令"
        echo "----------------------------------------------------------"
        fun_help
        exit 1
        ;;
esac
exit 0
