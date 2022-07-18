## 11.07
```
ALTER TABLE `eb_declare_temp` 
DROP COLUMN `uuid`,
ADD COLUMN `uuid` varchar(20) NULL DEFAULT '' COMMENT '标识' AFTER `travel_img`;
```


## 12.12

ALTER TABLE `eb_declare` 
ADD COLUMN `hsjc_get` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否获取过省内核酸检测信息' AFTER `send_sms`

UPDATE `eb_declare` SET `hsjc_get` = 1 WHERE `hsjc_time` is not NULL

CREATE TABLE `eb_user_hsjc_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(80) DEFAULT NULL COMMENT '证件号类型',
  `id_card` varchar(80) DEFAULT NULL COMMENT '证件号',
  `real_name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `hsjc_time` datetime DEFAULT NULL COMMENT '核酸检测时间',
  `hsjc_result` varchar(80) DEFAULT NULL COMMENT '核酸检测结果',
  `hsjc_date` date DEFAULT NULL COMMENT '核酸检测日期',
  `create_datetime` datetime DEFAULT NULL COMMENT '创建日期',
  `create_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;


ALTER TABLE `eb_declare` 
ADD COLUMN `jkm_get` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否获取过-健康码' AFTER `hsjc_get`

UPDATE `eb_declare` SET `jkm_get` = 1 WHERE `jkm_time` is not NULL

CREATE TABLE `eb_user_jkm_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(80) DEFAULT NULL COMMENT '证件号类型',
  `id_card` varchar(80) DEFAULT NULL COMMENT '证件号',
  `real_name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `jkm_time` datetime DEFAULT NULL COMMENT '健康码时间',
  `jkm_mzt` varchar(80) DEFAULT NULL COMMENT '健康码状态',
  `jkm_date` date DEFAULT NULL COMMENT '健康码更新日期',
  `create_datetime` datetime DEFAULT NULL COMMENT '创建日期',
  `create_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;


