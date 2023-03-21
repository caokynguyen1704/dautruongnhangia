/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : dldl_register

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-02-27 16:11:01
*/

SET FOREIGN_KEY_CHECKS=0;

GRANT ALL PRIVILEGES ON sszg_register.* TO 'sszg_reg'@'127.0.0.1' IDENTIFIED BY '111111' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON sszg_register.* TO 'sszg_reg'@'localhost' IDENTIFIED BY '111111' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- ----------------------------
-- Table structure for game_servers
-- ----------------------------
DROP TABLE IF EXISTS `game_servers`;
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
  `is_maintain`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否维护 1是 0否',
  `main_platform` varchar(128) DEFAULT NULL COMMENT '主合服平台',
  `main_zone_id` varchar(128) DEFAULT NULL COMMENT '主服务器ID',
  `is_default`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认 1是 0否',
  PRIMARY KEY (`id`),
  UNIQUE KEY `srv_id` (`platform`,`zone_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='服务器列表';

CREATE TABLE IF NOT EXISTS `role_info` (
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
