-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 05 月 27 日 04:24
-- 服务器版本: 5.5.15
-- PHP 版本: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mg_local_1`
--

-- --------------------------------------------------------

--
-- 表的结构 `base_item`
--

CREATE TABLE IF NOT EXISTS `base_item` (
  `bid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品bid',
  `item_name` varchar(64) NOT NULL DEFAULT '' COMMENT '物品名字',
  `quality` tinyint(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品品质',
  `item_type` smallint(10) NOT NULL DEFAULT '0' COMMENT '物品类型',
  `item_type_name` varchar(64) NOT NULL DEFAULT '' COMMENT '物品类型文字',
  `overlap` tinyint(10) unsigned NOT NULL DEFAULT '1' COMMENT '物品堆叠数',
  `description` mediumtext COMMENT '物品描述',
  PRIMARY KEY (`bid`),
  KEY `key_name` (`item_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品基础信息表';

-- --------------------------------------------------------

--
-- 表的结构 `base_mission`
--

CREATE TABLE IF NOT EXISTS `base_mission` (
  `bid` int(11) NOT NULL COMMENT '任务bid',
  `name` char(25) NOT NULL COMMENT '任务名字',
  `lev` smallint(6) NOT NULL DEFAULT '0' COMMENT '任务可接等级',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务基础数据表';

-- --------------------------------------------------------

--
-- 表的结构 `log_cli_error`
--

CREATE TABLE IF NOT EXISTS `log_cli_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '角色rid',
  `srv_id` varchar(30) NOT NULL COMMENT '服务器id',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `msg` text NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户端错误日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_error`
--

CREATE TABLE IF NOT EXISTS `log_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_gm`
--

CREATE TABLE IF NOT EXISTS `log_gm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL COMMENT 'GM操作类型文字 ：封停，禁言',
  `gm_name` varchar(50) NOT NULL COMMENT 'GM 名字',
  `rid` int(11) NOT NULL COMMENT '被操作角色ID',
  `srv_id` varchar(32) NOT NULL COMMENT '被操作服务器标志',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `name` varchar(50) NOT NULL COMMENT '被操作角色名称',
  `lev` int(11) DEFAULT NULL COMMENT '被操作角色等级',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '银两',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '元宝',
  `logout_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间戳',
  `reason` varchar(200) NOT NULL DEFAULT '' COMMENT '原因',
  `past_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间戳',
  `handle_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间戳',
  PRIMARY KEY (`id`),
  KEY `handle_time` (`handle_time`),
  KEY `srv_id` (`srv_id`),
  KEY `rid` (`rid`,`srv_id`),
  KEY `gm_name` (`gm_name`),
  KEY `channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='GM操作记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_mail`
--

CREATE TABLE IF NOT EXISTS `log_mail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mail_id` int(10) NOT NULL DEFAULT '0' COMMENT '邮件id',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群发id',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: 发件 1：收件',
  `from_name` char(30) DEFAULT NULL COMMENT '发件功能名字',
  `to_rid` int(10) DEFAULT '0' COMMENT '收件人id',
  `to_srv_id` char(30) DEFAULT NULL COMMENT '收件人服务器id',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `gold` int(10) NOT NULL COMMENT '元宝',
  `coin` int(10) NOT NULL COMMENT '铜钱',
  `subject` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `assets` text NOT NULL COMMENT '资产',
  `items` text NOT NULL COMMENT '物品名称',
  `ctime` int(10) DEFAULT '0' COMMENT '时间',
  `remark` text NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `from_name` (`from_name`),
  KEY `to_rid` (`to_rid`,`to_srv_id`),
  KEY `mail_id` (`mail_id`),
  KEY `ctime` (`ctime`),
  KEY `gold` (`gold`),
  KEY `group_id` (`group_id`),
  KEY `channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mod_black_ip`
--

CREATE TABLE IF NOT EXISTS `mod_black_ip` (
  `ip` char(15) NOT NULL COMMENT '被封ip',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '当前时间',
  `admin_name` char(15) NOT NULL COMMENT '管理员',
  `memo` char(30) NOT NULL COMMENT '原因',
  PRIMARY KEY (`ip`),
  KEY `ctime` (`ctime`),
  KEY `admin_name` (`admin_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='被封ip表';

-- --------------------------------------------------------

--
-- 表的结构 `mod_white_ip`
--

CREATE TABLE IF NOT EXISTS `mod_white_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标志',
  `ip` char(20) NOT NULL COMMENT 'ip',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:禁用 1:启用)',
  `reason` varchar(100) NOT NULL COMMENT '原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单ip表';

-- --------------------------------------------------------

--
-- 表的结构 `mod_white_acc`
--

CREATE TABLE IF NOT EXISTS `mod_white_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标志',
  `account` char(100) NOT NULL COMMENT '账号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:禁用 1:启用)',
  `reason` varchar(100) NOT NULL COMMENT '原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单账号表';

-- --------------------------------------------------------

--
-- 表的结构 `mod_charge`
--

CREATE TABLE IF NOT EXISTS `mod_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(100) NOT NULL DEFAULT '' COMMENT '充值流水号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '态状(0=玩家未处理，1=玩家已处理)',
  `category` tinyint(1) NOT NULL DEFAULT '1' COMMENT '充值类型，1：普通充值、2：自充值、3：测试充值',
  `currency_type` char(20) NOT NULL COMMENT '货币类型',
  `gold` int(10) unsigned NOT NULL COMMENT '充值元宝',
  `money` decimal(10,2) NOT NULL COMMENT '充值金额',
  `package_id` int(11) NOT NULL DEFAULT 0 COMMENT '商品ID',
  `package_name` varchar(64) NOT NULL DEFAULT '' COMMENT '商品名称',
  `pay_channel` varchar(64) NOT NULL DEFAULT '' COMMENT '支付渠道',
  `account` varchar(100) NOT NULL COMMENT '充值帐号',
  `rid` int(11) NOT NULL COMMENT '角色rid',
  `srv_id` varchar(20) NOT NULL COMMENT '角色srv_id',
  `channel` char(20) NOT NULL COMMENT '渠道',
  `name` char(50) NOT NULL COMMENT '角色昵称',
  `lev` smallint(6) NOT NULL DEFAULT '1' COMMENT '角色等级',
  `reg_time`  int(11) NOT NULL DEFAULT 0 COMMENT '注册时间',
  `time_deal` int(11) NOT NULL DEFAULT 0 COMMENT '处理时间',
  `ctime` int(10) unsigned NOT NULL COMMENT '充值时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `account` (`account`),
  KEY `rid` (`rid`,`srv_id`),
  KEY `ctime` (`ctime`),
  KEY `channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- 表的结构 `mod_comment`
--

CREATE TABLE IF NOT EXISTS `mod_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '评论员rid',
  `srv_id` varchar(30) NOT NULL,
  `len` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '语音时长',
  `msg` varchar(200) NOT NULL DEFAULT '' COMMENT '评论内容',
  `hero_bid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '评论的英雄bid',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `zan` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数量',
  PRIMARY KEY (`id`),
  KEY `role_id` (`rid`,`srv_id`),
  KEY `ctime` (`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- 表的结构 `mod_keyword_ad`
--

CREATE TABLE IF NOT EXISTS `mod_keyword_ad` (
  `content` varchar(15) NOT NULL COMMENT '关键字',
  PRIMARY KEY (`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='元宝哥屏蔽关键字词库';

-- --------------------------------------------------------

--
-- 表的结构 `role`
--
CREATE TABLE IF NOT EXISTS `role` (
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
  `gold_acc`  int(11) NOT NULL DEFAULT 0 COMMENT '累计充值',
  `gold`  int(11) NOT NULL DEFAULT 0 COMMENT '当前钻石',
  `data_lock` int(4) NOT NULL DEFAULT '0' COMMENT '角色数据被锁住的时间',
  `reg_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` char(15) NOT NULL COMMENT '注册ip',
  `reg_idfa`  varchar(64) NOT NULL DEFAULT '' COMMENT '注册设备号',
  `reg_device_id`  varchar(64) NOT NULL DEFAULT '' COMMENT '注册设备ID',
  `reg_os_name`  varchar(12) NOT NULL DEFAULT '' COMMENT '注册系统',
  `reg_app_name`  varchar(32) NOT NULL DEFAULT '' COMMENT '注册应用名',
  `reg_package_name`  varchar(64) NOT NULL DEFAULT '' COMMENT '注册包名',
  `reg_package_version`  varchar(12) NOT NULL DEFAULT '' COMMENT '注册包版本',
  `device_id` varchar(64) NOT NULL COMMENT '设备id',
  `device_type` varchar(12) NOT NULL DEFAULT '' COMMENT '设备类型',
  `getui_cid` varchar(50) NOT NULL COMMENT '个推cid',
  `idfa`  varchar(64) NOT NULL DEFAULT '' COMMENT '设备号',
  `os_name`  varchar(12) NOT NULL DEFAULT '' COMMENT '当前系统',
  `app_name`  varchar(32) NOT NULL DEFAULT '' COMMENT '当前应用名',
  `package_name`  varchar(64) NOT NULL DEFAULT '' COMMENT '当前包名',
  `package_version`  varchar(12) NOT NULL DEFAULT '' COMMENT '当前包版本',
  `os_ver`  varchar(24) NOT NULL DEFAULT '' COMMENT '当前系统版本',
  `carrier_name`  varchar(16) NOT NULL DEFAULT '' COMMENT '运营商',
  `net_type`  varchar(6) NOT NULL DEFAULT '' COMMENT '网络环境',
  `resolution`  varchar(12) NOT NULL DEFAULT '' COMMENT '分辨率',
  PRIMARY KEY (`rid`,`srv_id`),
  UNIQUE KEY `name` (`name`),
  KEY `account` (`account`),
  KEY `srv_id` (`srv_id`),
  KEY `login_ip` (`login_ip`),
  KEY `power` (`power`),
  KEY `power_2` (`power`),
  KEY `channel` (`channel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='角色数据' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `role_assets`
--

CREATE TABLE IF NOT EXISTS `role_assets` (
  `rid` int(11) NOT NULL,
  `srv_id` char(30) NOT NULL COMMENT '服务器标识',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '铜钱',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '元宝',
  `coupon` int(11) NOT NULL DEFAULT '0' COMMENT '礼券',
  `gold_acc` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '累计充值元宝',
  PRIMARY KEY (`rid`,`srv_id`),
  KEY `srv_id` (`srv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `role_ext`
--

CREATE TABLE IF NOT EXISTS `role_ext` (
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `srv_id` char(30) NOT NULL COMMENT '服务器标识',
  `data` mediumblob NOT NULL COMMENT '序列化后的角色数据',
  `guild_bag` blob NOT NULL COMMENT '帮会背包',
  PRIMARY KEY (`rid`,`srv_id`),
  KEY `srv_id` (`srv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色数据';

-- --------------------------------------------------------

--
-- 表的结构 `say_mon_cfg`
--

CREATE TABLE IF NOT EXISTS `say_mon_cfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(10) NOT NULL COMMENT '类型',
  `reg_exr` varchar(255) DEFAULT NULL COMMENT '关键词',
  PRIMARY KEY (`id`),
  UNIQUE KEY `word_key` (`type`,`reg_exr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='聊天监控关键词' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mod_old_name`
--

CREATE TABLE IF NOT EXISTS `mod_old_name` (
  `name` varchar(32) NOT NULL COMMENT '名称',
  `rid` int(11) NOT NULL COMMENT '角色ID ',
  `srv_id` varchar(64) NOT NULL COMMENT '服务器ID ',
  `ctime` int(11) NOT NULL COMMENT '时间',
  UNIQUE KEY `name` (`name`,`srv_id`),
  KEY `rid` (`rid`,`srv_id`),
  KEY `name1` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mod_notice`
--

CREATE TABLE IF NOT EXISTS `mod_notice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `position` tinyint(1) NOT NULL COMMENT '位置:1-顶部公告;2-左下角公告',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序;播放顺序',
  `is_play` tinyint(1) NOT NULL COMMENT '是否播放;1-播放',
  `st` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间戳',
  `et` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间戳',
  `time_interval` smallint(3) NOT NULL COMMENT '间隔时间,单位:秒',
  `content` text NOT NULL COMMENT '公告内容',
  `extra` varchar(50) NOT NULL DEFAULT '' COMMENT '公告内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统公告' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- 表的结构 `log_feedback_topic`
--

CREATE TABLE IF NOT EXISTS `log_feedback_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `srv_id` varchar(30) NOT NULL,
  `role_name` char(50) NOT NULL COMMENT '角色昵称',
  `type` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL COMMENT '来源IP',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '反馈时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未回复，1已回复。注意：每当玩家追问时，这里要更新为0',
  `reply_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'GM在后台最后回复时间',
  `submit_time` int(10) NOT NULL COMMENT '发生时间',
  `contact_type` varchar(60) NOT NULL DEFAULT '' COMMENT '联系方式',
  `contact` varchar(1024) NOT NULL DEFAULT '' COMMENT '联系内容',
  `account` char(100) NOT NULL COMMENT '玩家帐号',
  `work_id` varchar(50) NOT NULL COMMENT '工单id，关联客服工单',
  PRIMARY KEY (`id`),
  KEY `utime` (`ts`),
  KEY `rid` (`rid`,`srv_id`),
  KEY `work_id` (`work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家反馈主题表' AUTO_INCREMENT=1 ;

--
-- 表的结构 `mod_holiday_total`
--

CREATE TABLE IF NOT EXISTS `mod_holiday_total` (
  `id` int(11) NOT NULL COMMENT 'ID(特意不自增)mod_holiday_son表的total_id对应过来',
  `name` varchar(64) NOT NULL COMMENT '总活动名称',
  `icon_id` int(11) NOT NULL COMMENT '总活动图标(只能用数字)',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '活动状态[1:开启;其他-关闭]',
  `title` varchar(128) DEFAULT NULL COMMENT '总活动标题',
  `cli_word_type` varchar(256) DEFAULT NULL COMMENT '文字类型(客户端用)',
  `open_day` int(11) NOT NULL DEFAULT '0' COMMENT '开服多少天后起作用',
  `merge_day` int(11) NOT NULL DEFAULT '0' COMMENT '合服多少天后起作用',
  `is_show` int(11) NOT NULL DEFAULT '0' COMMENT '是否打开活动界面',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台总活动';

--
-- 表的结构 `mod_holiday_son`
--

CREATE TABLE IF NOT EXISTS `mod_holiday_son` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '活动bid;',
  `total_id` int(11) NOT NULL COMMENT '总活动ID',
  `camp_id` int(11) NOT NULL COMMENT '活动条件ID',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `title` varchar(128) NOT NULL COMMENT '子活动标题',
  `title2` varchar(128) NOT NULL COMMENT '子活动标题2',
  `ico` varchar(256) NOT NULL COMMENT '子活动图标(图)',
  `type_ico` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型图标',
  `top_banner` varchar(256) NOT NULL COMMENT '子活动顶部横幅(图)',
  `rule_str` text NOT NULL COMMENT '子活动规则描述(字)',
  `time_str` varchar(256) NOT NULL COMMENT '活动时间(字)',
  `bottom_alert` varchar(256) NOT NULL COMMENT '子活动底部提示(字)',
  `sort_val` int(11) NOT NULL DEFAULT '0' COMMENT '显示排序字段',
  `reward_title` varchar(500) NOT NULL COMMENT '奖励表头',
  `reward` text NOT NULL COMMENT '奖励',
  `item_effect` text NOT NULL COMMENT '物品特效',
  `cli_reward` text NOT NULL COMMENT '客户端奖励',
  `mail_subject` varchar(256) NOT NULL COMMENT '信件标题',
  `mail_content` text NOT NULL COMMENT '信件内容',
  `panel_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '子活动面板类型',
  `start_open_day` smallint(6) NOT NULL DEFAULT '0' COMMENT '开服第X天开活动',
  `process_show_reward` smallint(6) NOT NULL DEFAULT '0' COMMENT '领取奖励过滤是否显示',
  PRIMARY KEY (`id`),
  UNIQUE KEY `camp_id` (`camp_id`),
  KEY `total_id` (`total_id`),
  KEY `title` (`title`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台子活动(分服)' AUTO_INCREMENT=1 ;

--
-- 问题答卷总表
--

CREATE TABLE IF NOT EXISTS `mod_questionnaire` (
  `quest_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quest_title` varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT '问卷标题',
  `memo` varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `quest_status` tinyint(3) unsigned NOT NULL COMMENT '问卷状态 1：开启 2：关闭 3：已过期',
  `guide` tinyint(3) unsigned NOT NULL COMMENT '引导页展示 0：否 1：是',
  `welcome` tinyint(3) unsigned NOT NULL COMMENT '欢迎页展示 0：否 1：是',
  `mail_title` varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮件标题',
  `mail_content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '邮件内容',
  `reward_content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '奖励内容',
  `lv` int(10) unsigned NOT NULL COMMENT '等级',
  `recharge_min` int(10) unsigned NOT NULL COMMENT '充值范围左',
  `recharge_max` int(10) unsigned NOT NULL COMMENT '充值范围右',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`quest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问卷总表';

-- ----------------------------
-- Table structure for gm_questionnaire_son
-- ----------------------------

CREATE TABLE IF NOT EXISTS `mod_questionnaire_son` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quest_id` int(10) unsigned NOT NULL COMMENT '问卷ID',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '题目索引',
  `topic_type` tinyint(3) unsigned NOT NULL COMMENT '题目类型',
  `specific_type` tinyint(3) unsigned NOT NULL COMMENT '具体类型',
  `must` tinyint(3) unsigned NOT NULL COMMENT '必填 0：否 1：是',
  `options` text COLLATE utf8_unicode_ci NOT NULL COMMENT '选项',
  `title` varchar(120) CHARACTER SET utf8 NOT NULL COMMENT '题目标题',
  `jump` tinyint(3) unsigned NOT NULL COMMENT '跳转状态(0:否 1:是)',
  `topic` tinyint(3) unsigned NOT NULL COMMENT '被跳转目标 题目ID',
  `option_list` text COLLATE utf8_unicode_ci NOT NULL COMMENT '目标选项',
  PRIMARY KEY (`id`),
  KEY `quest_id` (`quest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问卷子表';

CREATE TABLE IF NOT EXISTS `sys_notice_board` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `summary` text COLLATE utf8_unicode_ci NOT NULL COMMENT '概要',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `sort` int(10) unsigned NOT NULL COMMENT '排序',
  `lev` int(10) unsigned NOT NULL COMMENT '等级',
  `items` varchar(1024) NOT NULL DEFAULT '' COMMENT '奖励内容',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='通知信息';

CREATE TABLE IF NOT EXISTS `mod_combat_replay` (
  `replay_id` int(11) NOT NULL COMMENT '录像ID',
  `combat_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建日期',
  `combat_plays` mediumblob NOT NULL COMMENT '战斗播报',
  PRIMARY KEY (`replay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='战斗录像';

CREATE TABLE IF NOT EXISTS `mod_vip_exclusive` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` varchar(48) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态(0:关 1:开)',
  `money` int(11) NOT NULL COMMENT '要求充值金额',
  `channel_names` varchar(48) NOT NULL DEFAULT '' COMMENT '渠道标识，‘用，分割’，为空为所有渠道',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `contact` TEXT NOT NULL COMMENT '联系方式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='VIP专属礼包';

-- -------------------- v170905 end -----------------

-- 2017.9.9 公告／通知增加渠道处理
ALTER TABLE `mod_notice` ADD COLUMN `channel` varchar(1024) NOT NULL default '' COMMENT '渠道信息' AFTER `extra`;
ALTER TABLE `sys_notice_board` ADD COLUMN `channel` varchar(1024) NOT NULL default '' COMMENT '渠道信息' AFTER `end_time`;

-- -------------------- v170915 end -----------------

-- 2017.9.25 活动配置
ALTER TABLE `mod_holiday_son` ADD COLUMN `status` int(5) NOT NULL default '0' COMMENT '状态(0:关1:开)' AFTER `process_show_reward`;
ALTER TABLE `mod_holiday_son` ADD COLUMN `open_day_min` int(10) NOT NULL default '0' COMMENT '开服后多少天才开启' AFTER `status`;
ALTER TABLE `mod_holiday_son` ADD COLUMN `open_day_max` int(10) NOT NULL default '0' COMMENT '开服后多少天才关闭' AFTER `open_day_min`;

-- -------------------- v171012 end -----------------
-- -------------------- v171019 end -----------------
-- -------------------- v171109 end -----------------

--
-- 表的结构 `mod_keywords`
-- 2017.11.16

CREATE TABLE IF NOT EXISTS `mod_keywords` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `content` varchar(128) NOT NULL COMMENT '关键词',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '类型(1:替换 2:禁言 3:封号)',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态(0:关闭 1:开启)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关键词表';

-- 录像表增加索引
ALTER table mod_combat_replay add index create_time (create_time);
ALTER table mod_combat_replay add index combat_type (combat_type);

-- -------------------- v171128 end -------------------------

--  2017.12.4
CREATE TABLE IF NOT EXISTS `mod_self_charge` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态(0:关闭 1:开启)',
  `account` varchar(100) NOT NULL COMMENT '充值帐号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自充值表';

--
-- 表的结构 `mod_black_ip`
--
DROP TABLE mod_black_ip;
CREATE TABLE IF NOT EXISTS `mod_black_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标志',
  `ip` char(20) NOT NULL COMMENT 'ip',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:禁用 1:启用)',
  `reason` varchar(100) NOT NULL COMMENT '原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='黑名单ip表';

-- -------------------- v181031 end -------------------------

--
-- 表的结构 `role_auth_adult`
--
CREATE TABLE `role_auth_adult` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标志',
    `platform` varchar(30) NOT NULL COMMENT 'ID编号',
    `channel_reg` int(11) NOT NULL DEFAULT '0' COMMENT '渠道编号',
    `can_charge` tinyint(4) NOT NULL COMMENT '未认证是否能充值(0:否 1:是)',
    `is_nonage` tinyint(4) NOT NULL COMMENT '未成年是否限制充值(0:否 1:是)',
    `charge_limit` int(11) NOT NULL COMMENT '未成年充值额度',
    `is_wallow` tinyint(4) NOT NULL COMMENT '是否开启防沉迷(0:否 1:是)',
    `down_sec` int(11) NOT NULL COMMENT '沉迷时间(秒)',
    `is_show_phone` tinyint(4) NOT NULL COMMENT '是否显示未到账联系方式(0:否 1:是)',
    `is_mail` tinyint(4) NOT NULL COMMENT '充值发送邮件(0:否 1:是)',
    `show_info` varchar(50) NOT NULL COMMENT '联系信息(0:否 1:是)',
    `is_mid_exp` tinyint(4) NOT NULL COMMENT '是否收益减半(0:否 1:是)',
    `is_lev_60` tinyint(4) NOT NULL COMMENT '只针对60级以下(0:否 1:是)',
    `is_need_check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否开启标签页',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='防沉迷设置';

-- 录像表增加索引
ALTER TABLE `mod_questionnaire` drop `lv`;
ALTER TABLE `mod_questionnaire` ADD COLUMN `min_lv` int(10) NOT NULL default '0' COMMENT '最小等级' AFTER `reward_content`;
ALTER TABLE `mod_questionnaire` ADD COLUMN `max_lv` int(10) NOT NULL default '999' COMMENT '最大等级' AFTER `min_lv`;

-- 字段更新 2019.1.23
ALTER TABLE `role` MODIFY COLUMN `os_ver` varchar(48) NOT NULL DEFAULT '' COMMENT  '当前系统版本';
-- 增加字段
ALTER TABLE `role` ADD COLUMN `account2` varchar(32) NOT NULL DEFAULT '' COMMENT '备份账号' AFTER `resolution`;

-- 增加角色分组 2019.3.20
ALTER TABLE `role` ADD COLUMN `acc_group` varchar(12) NOT NULL DEFAULT '2' COMMENT '账号分组' AFTER `account2`;
ALTER TABLE `role` ADD INDEX acc_group ( `account`, `acc_group` );
-- 新增字段
ALTER TABLE `role` ADD COLUMN `game_vip`     int(10)  NOT NULL DEFAULT '0' AFTER `acc_group`;
ALTER TABLE `role` ADD COLUMN `game_time_0`  int(10)  NOT NULL DEFAULT '0' AFTER `game_vip`;
ALTER TABLE `role` ADD COLUMN `game_time_1`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_0`;
ALTER TABLE `role` ADD COLUMN `game_time_2`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_1`;
ALTER TABLE `role` ADD COLUMN `game_time_3`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_2`;
ALTER TABLE `role` ADD COLUMN `game_time_4`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_3`;
ALTER TABLE `role` ADD COLUMN `game_time_5`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_4`;
ALTER TABLE `role` ADD COLUMN `game_time_6`  int(10)  NOT NULL DEFAULT '0' AFTER `game_time_5`;
ALTER TABLE `role` ADD COLUMN `game_fzb`     int(10)  NOT NULL DEFAULT '0' AFTER `game_time_6`;
ALTER TABLE `role` ADD COLUMN `vip`     int(10)  NOT NULL DEFAULT '0' AFTER `game_fzb`;
-- update role set acc_group = '1' where acc_group = '2' and (reg_channel like '16_%' or reg_channel like '47_%');

-- -------------------- v190409_end -------------------------

--
-- 表的结构 `mod_black_device`
--

CREATE TABLE IF NOT EXISTS `mod_black_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标志',
  `device` char(64) NOT NULL COMMENT '设备',
  `type` int(8) NOT NULL DEFAULT '0' COMMENT '类型(1:限制1个创号 2:限制2个创号)',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:禁用 1:启用)',
  `reason` varchar(100) NOT NULL COMMENT '原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `device` (`device`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备黑名单表';
