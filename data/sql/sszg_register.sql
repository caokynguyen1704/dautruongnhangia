-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: sszg_register
-- ------------------------------------------------------
-- Server version	5.6.50-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acc_charge`
--

DROP TABLE IF EXISTS `acc_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acc_charge` (
  `rid` int(11) NOT NULL COMMENT '角色id',
  `srv_id` char(30) NOT NULL COMMENT '服务器id',
  `account` varchar(64) NOT NULL COMMENT '账号',
  `channel_reg` varchar(32) NOT NULL DEFAULT '0' COMMENT '注册渠道',
  `acc_group` varchar(32) NOT NULL DEFAULT '0' COMMENT '账号分组',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '天',
  `money` int(11) NOT NULL DEFAULT '0' COMMENT '充值额度',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `new_rid` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `new_srv_id` char(30) NOT NULL DEFAULT '' COMMENT '服务器id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色充值';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acc_charge`
--

LOCK TABLES `acc_charge` WRITE;
/*!40000 ALTER TABLE `acc_charge` DISABLE KEYS */;
/*!40000 ALTER TABLE `acc_charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_account`
--

DROP TABLE IF EXISTS `game_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_account` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `username` char(64) NOT NULL COMMENT '账号',
  `password` char(64) NOT NULL COMMENT '密码',
  `acc_idfa` char(64) NOT NULL COMMENT '设备号',
  `reg_ip` char(15) NOT NULL,
  `fenghao` int(15) NOT NULL DEFAULT '0' COMMENT '封号',
  `fzb_mix` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_account`
--

LOCK TABLES `game_account` WRITE;
/*!40000 ALTER TABLE `game_account` DISABLE KEYS */;
INSERT INTO `game_account` VALUES (1,'zgy520','123456','869394027399598','183.229.208.116',0,0);
/*!40000 ALTER TABLE `game_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_servers`
--

DROP TABLE IF EXISTS `game_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_servers` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `platform` char(20) NOT NULL COMMENT '平台标志',
  `zone_id` int(10) unsigned NOT NULL COMMENT '区号',
  `zone_name` varchar(50) NOT NULL COMMENT '游戏区名称',
  `host` varchar(64) NOT NULL COMMENT '域名',
  `ip` char(64) NOT NULL COMMENT 'IP地址',
  `open_time` int(10) unsigned NOT NULL COMMENT '开服时间',
  `port` mediumint(7) unsigned NOT NULL COMMENT '游戏服务端开放端口',
  `srv_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0正常|1注销|2主合服|3被合服',
  `is_first` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首服 1是 0否',
  `recomed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐 1是 0否',
  `isnew` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新服 1是 0否',
  `is_maintain` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否维护 1是 0否',
  `main_platform` varchar(128) DEFAULT NULL COMMENT '主合服平台',
  `main_zone_id` varchar(128) DEFAULT NULL COMMENT '主服务器ID',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认 1是 0否',
  PRIMARY KEY (`id`),
  UNIQUE KEY `srv_id` (`platform`,`zone_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21229 DEFAULT CHARSET=utf8 COMMENT='服务器列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_servers`
--

LOCK TABLES `game_servers` WRITE;
/*!40000 ALTER TABLE `game_servers` DISABLE KEYS */;
INSERT INTO `game_servers` VALUES (21228,'symlf',3225,'月之降临','s3225-symlf-sszg.shiyuegame.com','192.168.1.147',1586500800,45085,0,0,0,0,0,'symlf','3225',0);
/*!40000 ALTER TABLE `game_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_servers_copy`
--

DROP TABLE IF EXISTS `game_servers_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_servers_copy` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `platform` char(20) NOT NULL COMMENT '平台标志',
  `zone_id` int(10) unsigned NOT NULL COMMENT '区号',
  `zone_name` varchar(50) NOT NULL COMMENT '游戏区名称',
  `host` varchar(64) NOT NULL COMMENT '域名',
  `ip` char(64) NOT NULL COMMENT 'IP地址',
  `open_time` int(10) unsigned NOT NULL COMMENT '开服时间',
  `port` mediumint(7) unsigned NOT NULL COMMENT '游戏服务端开放端口',
  `srv_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0正常|1注销|2主合服|3被合服',
  `is_first` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首服 1是 0否',
  `recomed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐 1是 0否',
  `isnew` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新服 1是 0否',
  `is_maintain` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否维护 1是 0否',
  `main_platform` varchar(128) DEFAULT NULL COMMENT '主合服平台',
  `main_zone_id` varchar(128) DEFAULT NULL COMMENT '主服务器ID',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认 1是 0否',
  PRIMARY KEY (`id`),
  UNIQUE KEY `srv_id` (`platform`,`zone_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='服务器列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_servers_copy`
--

LOCK TABLES `game_servers_copy` WRITE;
/*!40000 ALTER TABLE `game_servers_copy` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_servers_copy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_pre_obt_login_day`
--

DROP TABLE IF EXISTS `log_pre_obt_login_day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_pre_obt_login_day` (
  `id` int(11) NOT NULL,
  `account` char(100) NOT NULL COMMENT '账号',
  `rid` int(10) NOT NULL COMMENT '角色rid',
  `srv_id` varchar(30) NOT NULL COMMENT '服务器id',
  `name` char(50) NOT NULL COMMENT '角色昵称',
  `log_day` int(10) NOT NULL COMMENT '登录天数',
  `log_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后记录时间',
  KEY `account` (`account`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='删档测试登录天数';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_pre_obt_login_day`
--

LOCK TABLES `log_pre_obt_login_day` WRITE;
/*!40000 ALTER TABLE `log_pre_obt_login_day` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_pre_obt_login_day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_charge`
--

DROP TABLE IF EXISTS `mod_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_charge` (
  `id` int(11) NOT NULL,
  `sn` varchar(100) NOT NULL DEFAULT '' COMMENT '充值流水号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '态状(0=玩家未处理，1=玩家已处理)',
  `category` tinyint(1) NOT NULL DEFAULT '1' COMMENT '充值类型，1：普通充值、2：自充值、3：测试充值',
  `currency_type` char(20) NOT NULL COMMENT '货币类型',
  `gold` int(10) unsigned NOT NULL COMMENT '充值元宝',
  `money` decimal(10,2) NOT NULL COMMENT '充值金额',
  `package_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `package_name` varchar(64) NOT NULL DEFAULT '' COMMENT '商品名称',
  `pay_channel` varchar(64) NOT NULL DEFAULT '' COMMENT '支付渠道',
  `account` varchar(100) NOT NULL COMMENT '充值帐号',
  `rid` int(11) NOT NULL COMMENT '角色rid',
  `srv_id` varchar(20) NOT NULL COMMENT '角色srv_id',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `name` char(50) NOT NULL COMMENT '角色昵称',
  `lev` smallint(6) NOT NULL DEFAULT '1' COMMENT '角色等级',
  `reg_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `time_deal` int(11) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '充值时间',
  UNIQUE KEY `sn` (`sn`),
  KEY `account` (`account`),
  KEY `rid` (`rid`,`srv_id`),
  KEY `ctime` (`ctime`),
  KEY `channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_charge`
--

LOCK TABLES `mod_charge` WRITE;
/*!40000 ALTER TABLE `mod_charge` DISABLE KEYS */;
/*!40000 ALTER TABLE `mod_charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `srv_id` char(30) NOT NULL COMMENT '服务器id',
  `reg_channel` char(20) NOT NULL COMMENT '注册渠道',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `account` char(100) NOT NULL COMMENT '帐号',
  `name` char(50) NOT NULL COMMENT '角色昵称',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别',
  `career` smallint(6) NOT NULL,
  `lev` smallint(6) NOT NULL DEFAULT '1' COMMENT '等级',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logout_time` int(11) NOT NULL DEFAULT '0' COMMENT '登出时间',
  `login_ip` char(15) NOT NULL COMMENT '登录ip',
  `is_online` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在线',
  `power` int(11) NOT NULL DEFAULT '0' COMMENT '战斗力',
  `max_power` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大战力',
  `arena_power` int(11) NOT NULL DEFAULT '0' COMMENT '竞技场战斗力',
  `arena_max_power` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '竞技场最大战力',
  `identity` char(20) NOT NULL DEFAULT '' COMMENT '身证份号码',
  `banned_time` int(11) NOT NULL DEFAULT '0' COMMENT '禁言超时时间 0默认为不禁言',
  `interdict_time` int(11) NOT NULL DEFAULT '0' COMMENT '封号超时时间 0默认为不封号',
  `face_id` int(11) NOT NULL DEFAULT '0' COMMENT '头像id',
  `capacity` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0玩家 1GM 2新手指导员',
  `vip_lev` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `gold_acc` int(11) NOT NULL DEFAULT '0' COMMENT '累计充值',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '当前钻石',
  `data_lock` int(4) NOT NULL DEFAULT '0' COMMENT '角色数据被锁住的时间',
  `reg_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` char(15) NOT NULL COMMENT '注册ip',
  `reg_idfa` varchar(64) NOT NULL DEFAULT '' COMMENT '注册设备号',
  `reg_device_id` varchar(64) NOT NULL DEFAULT '' COMMENT '注册设备ID',
  `reg_os_name` varchar(12) NOT NULL DEFAULT '' COMMENT '注册系统',
  `reg_app_name` varchar(32) NOT NULL DEFAULT '' COMMENT '注册应用名',
  `reg_package_name` varchar(64) NOT NULL DEFAULT '' COMMENT '注册包名',
  `reg_package_version` varchar(12) NOT NULL DEFAULT '' COMMENT '注册包版本',
  `device_id` varchar(64) NOT NULL COMMENT '设备id',
  `device_type` varchar(12) NOT NULL DEFAULT '' COMMENT '设备类型',
  `getui_cid` varchar(50) NOT NULL COMMENT '个推cid',
  `idfa` varchar(64) NOT NULL DEFAULT '' COMMENT '设备号',
  `os_name` varchar(12) NOT NULL DEFAULT '' COMMENT '当前系统',
  `app_name` varchar(32) NOT NULL DEFAULT '' COMMENT '当前应用名',
  `package_name` varchar(64) NOT NULL DEFAULT '' COMMENT '当前包名',
  `package_version` varchar(12) NOT NULL DEFAULT '' COMMENT '当前包版本',
  `os_ver` varchar(48) NOT NULL DEFAULT '' COMMENT '当前系统版本',
  `carrier_name` varchar(16) NOT NULL DEFAULT '' COMMENT '运营商',
  `net_type` varchar(6) NOT NULL DEFAULT '' COMMENT '网络环境',
  `resolution` varchar(12) NOT NULL DEFAULT '' COMMENT '分辨率',
  `account2` varchar(32) NOT NULL DEFAULT '' COMMENT '备份账号',
  `acc_group` varchar(12) NOT NULL DEFAULT '2' COMMENT '账号分组',
  PRIMARY KEY (`rid`,`srv_id`),
  KEY `account` (`account`),
  KEY `srv_id` (`srv_id`),
  KEY `login_ip` (`login_ip`),
  KEY `power` (`power`),
  KEY `power_2` (`power`),
  KEY `channel` (`channel`),
  KEY `acc_group` (`account`,`acc_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_info`
--

DROP TABLE IF EXISTS `role_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_info` (
  `account` varchar(128) NOT NULL COMMENT '账号',
  `lev` int(10) unsigned NOT NULL COMMENT '等级',
  `vip_lev` int(10) unsigned NOT NULL COMMENT 'VIP等级',
  `money` decimal(10,2) NOT NULL COMMENT '充值金额',
  `platform` varchar(128) DEFAULT NULL COMMENT '平台',
  `rid` int(11) DEFAULT NULL COMMENT '角色ID',
  `srv_id` varchar(128) DEFAULT NULL COMMENT '服务器ID',
  `ctime` int(11) DEFAULT '0' COMMENT '领取时间',
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='封测玩家信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_info`
--

LOCK TABLES `role_info` WRITE;
/*!40000 ALTER TABLE `role_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'sszg_register'
--

--
-- Dumping routines for database 'sszg_register'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-26  0:30:20
