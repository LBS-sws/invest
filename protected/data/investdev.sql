/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : investdev

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2023-01-10 11:11:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inv_queue
-- ----------------------------
DROP TABLE IF EXISTS `inv_queue`;
CREATE TABLE `inv_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rpt_desc` varchar(250) NOT NULL,
  `req_dt` datetime DEFAULT NULL,
  `fin_dt` datetime DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  `rpt_type` varchar(10) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rpt_content` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inv_queue_param
-- ----------------------------
DROP TABLE IF EXISTS `inv_queue_param`;
CREATE TABLE `inv_queue_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `param_field` varchar(50) NOT NULL,
  `param_value` varchar(500) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inv_queue_user
-- ----------------------------
DROP TABLE IF EXISTS `inv_queue_user`;
CREATE TABLE `inv_queue_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inv_treaty
-- ----------------------------
DROP TABLE IF EXISTS `inv_treaty`;
CREATE TABLE `inv_treaty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treaty_code` varchar(255) DEFAULT NULL COMMENT '收購編號',
  `treaty_num` int(11) NOT NULL DEFAULT '0' COMMENT '標的記錄次數',
  `company_name` varchar(255) NOT NULL COMMENT '企業名稱',
  `agent_user` varchar(255) DEFAULT NULL COMMENT '法定代表人',
  `agent_phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `company_date` date DEFAULT NULL COMMENT '企業成立日期',
  `annual_money` float(15,2) DEFAULT NULL COMMENT '年生意額',
  `rate_num` float(10,2) DEFAULT NULL COMMENT '纯利率',
  `account_type` int(2) NOT NULL DEFAULT '1' COMMENT '会计操作是否正规 0:不正規 1：正規',
  `technician_type` int(11) NOT NULL DEFAULT '1' COMMENT '技术团队是否自有 0:無 1：有',
  `sales_source` varchar(255) DEFAULT NULL COMMENT '销售拓客渠道',
  `rate_government` float(10,2) DEFAULT NULL COMMENT '政府项目占比',
  `remark` text COMMENT '备注',
  `end_remark` text COMMENT '跟進結束備註',
  `city_allow` varchar(40) NOT NULL,
  `city` varchar(20) NOT NULL,
  `apply_date` date DEFAULT NULL COMMENT '標的起始時間',
  `apply_lcu` varchar(255) NOT NULL COMMENT '記錄人員',
  `start_date` date DEFAULT NULL COMMENT '記錄開始時間',
  `end_date` date DEFAULT NULL COMMENT '記錄結束時間',
  `state_type` int(11) NOT NULL DEFAULT '0' COMMENT '0：未使用 1：收購進行中  2：已收購 3：收購失敗',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='合约到期提醒表（主表）';

-- ----------------------------
-- Table structure for inv_treaty_info
-- ----------------------------
DROP TABLE IF EXISTS `inv_treaty_info`;
CREATE TABLE `inv_treaty_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `history_code` varchar(255) DEFAULT NULL COMMENT '记录編號',
  `treaty_id` int(11) NOT NULL,
  `history_date` date NOT NULL COMMENT '记录日期',
  `history_matter` text COMMENT '跟进事项',
  `info_state` int(11) NOT NULL DEFAULT '1' COMMENT '1：收購進行中  2：已收購 3：收購失敗',
  `remark` text COMMENT '备注',
  `lcu` varchar(255) DEFAULT NULL,
  `luu` varchar(255) DEFAULT NULL,
  `lcd` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lud` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='合约到期提醒表（副表）';
