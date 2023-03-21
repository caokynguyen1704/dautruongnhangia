#/bin/bash
# ---------------------------------------------------------
#  维护工具
# @author whjing2012@gmail.com
# ---------------------------------------------------------

# dstat  		系统信息统计工具 
# iostat 		IO统计工具
# netstat -an | grep 3306  端口连接情况查看
# netstat -lnp  系统当前启动端口
# vnstat -i xxx -l   流量查看xxx网卡用ifconfig查看
# vmstat        系统整体负载
# iotop  		IO命令 类似top
# iftop         查看流量 类似top 按各个客户端流量排序
# strace 		进程追踪
# pstack        查看活动进程的堆栈
# du -sh card   查看指定目录大小
# df -h         硬盘情况
# ss -ant | awk '{++s[$1]} END {for(k in s) print k,s[k]}' 查看当前商品使用情况
# lsof -p Pid  查看指定进程打开的文件
# lsof -i:3306 查看打开指定端口的进程
# lsof -i      显示所有打开的端口

#----------------------------------------------
# 数据库管理
# gdb -p$(cat /data0/mysql/3306/mysql.pid) -ex "set max_connections=5000" -batch 修改数据库最大连接数
# mysqladmin -uroot -p$(cat /data/save/mysql_root) status   数据库状态
# mysql -uroot -p$(cat /data/save/mysql_root) -e "show processlist"
#----------------------------------------------

#--------------------------------------------
# 批量查找定位
# 查找/data目录下所有以fsgj_开头的目录，如果目录fsgj_xxx中存在screenlog.0文件即取出后10行内容，内容中如果存在error错误信息即显示出来(作用：批量查找存在指定信息的节点问题)
# cd /data && ls |grep fsgj_ |awk '{"test -f "$0"/screenlog.0 && tail -n 10 "$0"/screenlog.0 |grep error || echo """ | getline ret; if(ret != ""){print $0; print ret;}}'
# 
 
#--------------------------------------------

## 命令行帮助
function fun_help(){
    echo "proc_doing Pid          查看进程在干啥"
    echo "strace_stat Pid 	      进程追踪统计"
    echo "pstack Pid              查看活动进程堆栈"
    echo "dump_strace Pid         导出活动进程追踪结果到文件夹dump_strace_dir_Pid Ctrl+C结束"
    echo "strace Pid              实时追踪进程"
}

## 查看程序在干啥
function fun_proc_doing(){
    amples=1
    sleeptime=0
    check_pid $1
    echo "开始追踪进程[$pid]，按Ctrl+C结束"
    for x in $(seq 1 $nsamples)
    do
       gdb -ex "set pagination 0" -ex "thread apply all bt" -batch -p $pid
       sleep $sleeptime
    done | \
    awk '
  BEGIN { s = ""; }
  /^Thread/ { print s; s = ""; }
  /^\#/ { if (s != "" ) { s = s "," $4} else { s = $4 } }
  END { print s }' | \
  sort | uniq -c | sort -r -n -k 1,1
}

## 程度追踪统计
function fun_strace_stat(){
    check_pid $1
    echo "开始追踪进程[$pid]，按Ctrl+C结束"
    strace -f -c -p $pid
}

# 查看活动进程堆栈
function fun_pstack(){
    check_pid $1
    pstack $pid
}

# 导出进程执行信息到文件
function fun_dump_strace(){
    check_pid $1
    mkdir -p dump_strace_dir_$pid
    cd dump_strace_dir_$pid
    echo "开始追踪进程[$pid]，按Ctrl+C结束"
    strace -f -F -ff -o dump_strace_file -s 1024 -p $pid    
    echo "数据已导出到文件夹[dump_strace_dir_$pid]/dump_strace_file.*中"
}

# 实时追踪进程
function fun_strace(){
    check_pid $1
    echo "开始追踪进程[$pid]，按Ctrl+C结束"
    strace -f -F -ff -s 1024 -p $pid
}

# 检查PID
function check_pid(){
    if [ $(expr match $1 "[0-9]*$") -gt 0 ]; then
	    pid=$1
    else
	    pid=$(pidof $1)
    fi
    if [ "$pid" = "" ]; then
	echo "请输入进程号:PID, 可用top查看"
	exit 1
    fi
}

## 执行入口
cmd=$1
shift
case $cmd in
    proc_doing) fun_proc_doing $@;;
    strace_stat) fun_strace_stat $@;;
    pstack) fun_pstack $@;;
    dump_strace) fun_dump_strace $@;;
    strace) fun_strace $@;;
    *)
        echo "未知指令，请使用以下有效指令"
        echo "----------------------------------------------------------"
        fun_help
        exit 1
        ;;
esac
exit 0
