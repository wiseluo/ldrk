/*
Navicat MySQL Data Transfer

Source Server         : 47.98.144.82
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : ldrk_jk_kj_com

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2021-12-23 09:27:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for eb_barrier_district
-- ----------------------------
DROP TABLE IF EXISTS `eb_barrier_district`;
CREATE TABLE `eb_barrier_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '区划信息id',
  `district_id` int(11) DEFAULT '0' COMMENT '地域id',
  `district` varchar(255) DEFAULT '' COMMENT '地域名称',
  `province_id` int(11) DEFAULT '0' COMMENT '城市id',
  `province` varchar(64) DEFAULT '' COMMENT '省',
  `city_id` int(11) DEFAULT '0' COMMENT '城市id',
  `city` varchar(64) DEFAULT '' COMMENT '市',
  `county_id` int(11) DEFAULT '0' COMMENT '县id',
  `county` varchar(64) DEFAULT '' COMMENT '县',
  `street_id` int(11) DEFAULT '0' COMMENT '镇街id',
  `street` varchar(150) DEFAULT '' COMMENT '镇街',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
  `delete_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='卡口申报城市配置表';
