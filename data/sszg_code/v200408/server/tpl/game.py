#!/usr/bin/env python
#coding:utf8
#-----------------------------------------------------
# 翻译打包实现
#
# @author whjing2012@gmail.com
#-----------------------------------------------------
import zipfile 
import os
import sys
import time
from time import clock as now  
from datetime import datetime
from shutil import copy
import codecs
import re
# 数据库乱码处理
reload(sys)  
sys.setdefaultencoding('utf8') 
import MySQLdb

# OMS数据库配置
db_host='106.14.241.91'
db_port=3306
db_name="oms"
db_user='xge'
db_pass="HrsmjzIhKWff1Iae"
game_code="sszg"
# 产品ID
product_id="11"
print("====", sys.path[0])

# 获取文件内容
def get_file_content(filepath):
    filestr=""
    if os.path.isfile(filepath):
        f = open(filepath, "r")
        filestr = f.read().decode('utf-8')
        f.close()
    return filestr
 
def gen_ctl(platform, zone_id):
    conn=MySQLdb.connect(host=db_host,user=db_user,passwd=db_pass,db=db_name,port=db_port,charset="utf8")
    cur=conn.cursor()
    zone_sql="select a.name product_name, b.name platform, c.*, d.*, e.name machine, e.code_path, e.ip, e.ip_internal, e.ssh_port, e.ssh_user,f.value center_node from products a, platforms b, zones c, nodes d, machines e, zones_events f where a.product_id = "+product_id+" and b.product_id = "+product_id+" and c.product_id = "+product_id+" and c.product_id = "+product_id+" and d.product_id = "+product_id+" and e.product_id = "+product_id+"  and a.product_id = b.product_id  and b.platform_id = c.platform_id  and b.platform_id = d.platform_id and c.zone_id = d.zone_id and d.machine_id = e.machine_id and b.name='"+platform+"' and c.zone_id="+zone_id+" and d.zone_increment_id=f.zone_increment_id and f.key='center_node'"
    # print(zone_sql)
    cur.execute(zone_sql)
    vals=cur.fetchone()
    names=cur.description
    if not vals:
        print("error: zone_info not found", platform, zone_id)
        exit()
    # print "=============", names
    # print "=============", vals
    text=get_file_content(sys.path[0]+"/ctl.sh")
    for i in range(len(names)):
        text = text.replace("{{"+names[i][0]+"}}", str(vals[i]).replace("\\", ""))
    fout = codecs.open("/tmp/"+game_code+"_"+platform+"_"+zone_id+"_ctl.sh", "w", "utf-8")
    fout.write(text)
    fout.close()
    cur.close()
    conn.close()
    print("gen_ctl_success===", platform, zone_id)

if len(sys.argv) < 3:
    exit("error: please input platform and zone_id")
gen_ctl(sys.argv[1], sys.argv[2])


