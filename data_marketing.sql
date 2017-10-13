/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.199
Source Server Version : 50505
Source Host           : 192.168.1.199:3306
Source Database       : data_marketing

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-10-13 10:48:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mk_admin
-- ----------------------------
DROP TABLE IF EXISTS `mk_admin`;
CREATE TABLE `mk_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '账户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` smallint(4) NOT NULL DEFAULT '0' COMMENT '密码随机数',
  `realname` varchar(255) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(11) NOT NULL COMMENT '联系方式',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否冻结:1正常,2冻结',
  `position_id` int(10) NOT NULL DEFAULT '0' COMMENT '对应职位id',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '0' COMMENT '最后登陆ip',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后一次登陆时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `frozen_time` int(10) NOT NULL DEFAULT '0' COMMENT '冻结时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_admin
-- ----------------------------
INSERT INTO `mk_admin` VALUES ('1', 'admin', 'f9b77d488ca8463fa2f8bdb44863159d', '1234', '管理员1', '15221210101', '1', '1', '127.0.0.1', '1506342389', '0', '1503457396', '0');

-- ----------------------------
-- Table structure for mk_customer
-- ----------------------------
DROP TABLE IF EXISTS `mk_customer`;
CREATE TABLE `mk_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '账户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` smallint(4) NOT NULL DEFAULT '0' COMMENT '密码随机数',
  `realname` varchar(255) NOT NULL COMMENT '客户名',
  `contact_name` varchar(255) DEFAULT NULL COMMENT '联系人姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '联系方式',
  `qq` varchar(255) DEFAULT NULL,
  `wangwang` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '负责人id',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '账号状态:1正常,2已冻结',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '0' COMMENT '最后登陆ip',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后一次登陆时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='客户表';

-- ----------------------------
-- Records of mk_customer
-- ----------------------------

-- ----------------------------
-- Table structure for mk_finance
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance`;
CREATE TABLE `mk_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `plan_id` int(11) NOT NULL COMMENT '计划id',
  `date` int(10) NOT NULL DEFAULT '0' COMMENT '数据日期',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收款金额',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of mk_finance
-- ----------------------------

-- ----------------------------
-- Table structure for mk_operation
-- ----------------------------
DROP TABLE IF EXISTS `mk_operation`;
CREATE TABLE `mk_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) NOT NULL DEFAULT '0' COMMENT '职位id',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务id',
  PRIMARY KEY (`id`),
  KEY `group_id` (`position_id`) USING BTREE,
  KEY `task_id` (`task_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6487 DEFAULT CHARSET=utf8 COMMENT='权限职位对应表';

-- ----------------------------
-- Records of mk_operation
-- ----------------------------
INSERT INTO `mk_operation` VALUES ('5849', '2', '75');
INSERT INTO `mk_operation` VALUES ('6486', '1', '71');
INSERT INTO `mk_operation` VALUES ('6485', '1', '69');
INSERT INTO `mk_operation` VALUES ('6484', '1', '68');
INSERT INTO `mk_operation` VALUES ('6483', '1', '60');
INSERT INTO `mk_operation` VALUES ('6482', '1', '59');
INSERT INTO `mk_operation` VALUES ('5848', '2', '70');
INSERT INTO `mk_operation` VALUES ('5847', '2', '55');
INSERT INTO `mk_operation` VALUES ('5846', '2', '54');
INSERT INTO `mk_operation` VALUES ('5845', '2', '53');
INSERT INTO `mk_operation` VALUES ('4879', '8', '50');
INSERT INTO `mk_operation` VALUES ('4878', '8', '49');
INSERT INTO `mk_operation` VALUES ('4877', '8', '48');
INSERT INTO `mk_operation` VALUES ('6481', '1', '76');
INSERT INTO `mk_operation` VALUES ('6480', '1', '72');
INSERT INTO `mk_operation` VALUES ('6479', '1', '58');
INSERT INTO `mk_operation` VALUES ('6478', '1', '57');
INSERT INTO `mk_operation` VALUES ('6477', '1', '56');
INSERT INTO `mk_operation` VALUES ('6476', '1', '75');
INSERT INTO `mk_operation` VALUES ('6475', '1', '70');
INSERT INTO `mk_operation` VALUES ('6474', '1', '55');
INSERT INTO `mk_operation` VALUES ('6473', '1', '54');
INSERT INTO `mk_operation` VALUES ('6472', '1', '53');
INSERT INTO `mk_operation` VALUES ('6471', '1', '52');
INSERT INTO `mk_operation` VALUES ('6470', '1', '51');
INSERT INTO `mk_operation` VALUES ('6469', '1', '50');
INSERT INTO `mk_operation` VALUES ('6468', '1', '49');
INSERT INTO `mk_operation` VALUES ('6467', '1', '48');
INSERT INTO `mk_operation` VALUES ('6466', '1', '47');
INSERT INTO `mk_operation` VALUES ('6465', '1', '46');
INSERT INTO `mk_operation` VALUES ('6464', '1', '45');
INSERT INTO `mk_operation` VALUES ('6463', '1', '44');
INSERT INTO `mk_operation` VALUES ('6462', '1', '43');
INSERT INTO `mk_operation` VALUES ('6461', '1', '42');
INSERT INTO `mk_operation` VALUES ('6460', '1', '41');
INSERT INTO `mk_operation` VALUES ('6459', '1', '40');
INSERT INTO `mk_operation` VALUES ('6458', '1', '39');
INSERT INTO `mk_operation` VALUES ('6457', '1', '38');
INSERT INTO `mk_operation` VALUES ('6456', '1', '37');
INSERT INTO `mk_operation` VALUES ('6455', '1', '36');
INSERT INTO `mk_operation` VALUES ('6454', '1', '35');
INSERT INTO `mk_operation` VALUES ('6453', '1', '34');
INSERT INTO `mk_operation` VALUES ('6452', '1', '33');
INSERT INTO `mk_operation` VALUES ('6451', '1', '32');
INSERT INTO `mk_operation` VALUES ('6450', '1', '31');
INSERT INTO `mk_operation` VALUES ('6449', '1', '30');
INSERT INTO `mk_operation` VALUES ('6448', '1', '29');
INSERT INTO `mk_operation` VALUES ('6447', '1', '28');
INSERT INTO `mk_operation` VALUES ('6446', '1', '27');
INSERT INTO `mk_operation` VALUES ('6445', '1', '26');
INSERT INTO `mk_operation` VALUES ('6444', '1', '25');
INSERT INTO `mk_operation` VALUES ('6443', '1', '24');
INSERT INTO `mk_operation` VALUES ('6442', '1', '23');
INSERT INTO `mk_operation` VALUES ('6441', '1', '22');
INSERT INTO `mk_operation` VALUES ('6440', '1', '21');
INSERT INTO `mk_operation` VALUES ('6439', '1', '81');
INSERT INTO `mk_operation` VALUES ('6438', '1', '20');
INSERT INTO `mk_operation` VALUES ('6437', '1', '19');
INSERT INTO `mk_operation` VALUES ('6436', '1', '18');
INSERT INTO `mk_operation` VALUES ('5830', '2', '38');
INSERT INTO `mk_operation` VALUES ('5829', '2', '37');
INSERT INTO `mk_operation` VALUES ('5828', '2', '36');
INSERT INTO `mk_operation` VALUES ('5827', '2', '35');
INSERT INTO `mk_operation` VALUES ('5826', '2', '34');
INSERT INTO `mk_operation` VALUES ('5825', '2', '33');
INSERT INTO `mk_operation` VALUES ('5824', '2', '32');
INSERT INTO `mk_operation` VALUES ('5823', '2', '31');
INSERT INTO `mk_operation` VALUES ('5822', '2', '30');
INSERT INTO `mk_operation` VALUES ('5821', '2', '29');
INSERT INTO `mk_operation` VALUES ('5820', '2', '28');
INSERT INTO `mk_operation` VALUES ('5819', '2', '27');
INSERT INTO `mk_operation` VALUES ('5818', '2', '26');
INSERT INTO `mk_operation` VALUES ('5817', '2', '25');
INSERT INTO `mk_operation` VALUES ('5816', '2', '24');
INSERT INTO `mk_operation` VALUES ('5815', '2', '23');
INSERT INTO `mk_operation` VALUES ('5814', '2', '22');
INSERT INTO `mk_operation` VALUES ('5813', '2', '21');
INSERT INTO `mk_operation` VALUES ('5812', '2', '20');
INSERT INTO `mk_operation` VALUES ('5811', '2', '19');
INSERT INTO `mk_operation` VALUES ('5810', '2', '18');
INSERT INTO `mk_operation` VALUES ('5809', '2', '17');
INSERT INTO `mk_operation` VALUES ('5808', '2', '16');
INSERT INTO `mk_operation` VALUES ('5807', '2', '15');
INSERT INTO `mk_operation` VALUES ('5806', '2', '14');
INSERT INTO `mk_operation` VALUES ('5805', '2', '13');
INSERT INTO `mk_operation` VALUES ('5804', '2', '12');
INSERT INTO `mk_operation` VALUES ('5803', '2', '11');
INSERT INTO `mk_operation` VALUES ('5802', '2', '10');
INSERT INTO `mk_operation` VALUES ('5801', '2', '9');
INSERT INTO `mk_operation` VALUES ('5800', '2', '8');
INSERT INTO `mk_operation` VALUES ('5799', '2', '7');
INSERT INTO `mk_operation` VALUES ('5798', '2', '6');
INSERT INTO `mk_operation` VALUES ('5797', '2', '5');
INSERT INTO `mk_operation` VALUES ('5796', '2', '74');
INSERT INTO `mk_operation` VALUES ('5795', '2', '73');
INSERT INTO `mk_operation` VALUES ('5794', '2', '67');
INSERT INTO `mk_operation` VALUES ('5793', '2', '66');
INSERT INTO `mk_operation` VALUES ('5792', '2', '65');
INSERT INTO `mk_operation` VALUES ('5791', '2', '64');
INSERT INTO `mk_operation` VALUES ('5790', '2', '4');
INSERT INTO `mk_operation` VALUES ('5907', '3', '52');
INSERT INTO `mk_operation` VALUES ('5906', '3', '51');
INSERT INTO `mk_operation` VALUES ('5905', '3', '50');
INSERT INTO `mk_operation` VALUES ('5904', '3', '49');
INSERT INTO `mk_operation` VALUES ('5903', '3', '48');
INSERT INTO `mk_operation` VALUES ('5902', '3', '47');
INSERT INTO `mk_operation` VALUES ('5901', '3', '46');
INSERT INTO `mk_operation` VALUES ('5900', '3', '45');
INSERT INTO `mk_operation` VALUES ('5899', '3', '44');
INSERT INTO `mk_operation` VALUES ('5898', '3', '43');
INSERT INTO `mk_operation` VALUES ('5897', '3', '42');
INSERT INTO `mk_operation` VALUES ('5896', '3', '41');
INSERT INTO `mk_operation` VALUES ('5895', '3', '40');
INSERT INTO `mk_operation` VALUES ('5894', '3', '39');
INSERT INTO `mk_operation` VALUES ('5893', '3', '38');
INSERT INTO `mk_operation` VALUES ('5892', '3', '37');
INSERT INTO `mk_operation` VALUES ('6435', '1', '80');
INSERT INTO `mk_operation` VALUES ('6434', '1', '17');
INSERT INTO `mk_operation` VALUES ('6433', '1', '16');
INSERT INTO `mk_operation` VALUES ('6432', '1', '15');
INSERT INTO `mk_operation` VALUES ('6431', '1', '79');
INSERT INTO `mk_operation` VALUES ('6430', '1', '14');
INSERT INTO `mk_operation` VALUES ('5789', '2', '3');
INSERT INTO `mk_operation` VALUES ('5788', '2', '2');
INSERT INTO `mk_operation` VALUES ('5787', '2', '1');
INSERT INTO `mk_operation` VALUES ('5869', '3', '14');
INSERT INTO `mk_operation` VALUES ('5868', '3', '13');
INSERT INTO `mk_operation` VALUES ('5867', '3', '12');
INSERT INTO `mk_operation` VALUES ('5866', '3', '11');
INSERT INTO `mk_operation` VALUES ('5865', '3', '10');
INSERT INTO `mk_operation` VALUES ('5864', '3', '9');
INSERT INTO `mk_operation` VALUES ('5863', '3', '8');
INSERT INTO `mk_operation` VALUES ('5862', '3', '7');
INSERT INTO `mk_operation` VALUES ('5861', '3', '6');
INSERT INTO `mk_operation` VALUES ('5860', '3', '5');
INSERT INTO `mk_operation` VALUES ('5859', '3', '74');
INSERT INTO `mk_operation` VALUES ('5858', '3', '73');
INSERT INTO `mk_operation` VALUES ('5857', '3', '67');
INSERT INTO `mk_operation` VALUES ('5856', '3', '66');
INSERT INTO `mk_operation` VALUES ('5855', '3', '65');
INSERT INTO `mk_operation` VALUES ('5854', '3', '64');
INSERT INTO `mk_operation` VALUES ('5853', '3', '4');
INSERT INTO `mk_operation` VALUES ('5852', '3', '3');
INSERT INTO `mk_operation` VALUES ('5851', '3', '2');
INSERT INTO `mk_operation` VALUES ('5850', '3', '1');
INSERT INTO `mk_operation` VALUES ('5891', '3', '36');
INSERT INTO `mk_operation` VALUES ('5890', '3', '35');
INSERT INTO `mk_operation` VALUES ('5889', '3', '34');
INSERT INTO `mk_operation` VALUES ('5888', '3', '33');
INSERT INTO `mk_operation` VALUES ('5887', '3', '32');
INSERT INTO `mk_operation` VALUES ('5886', '3', '31');
INSERT INTO `mk_operation` VALUES ('5885', '3', '30');
INSERT INTO `mk_operation` VALUES ('5884', '3', '29');
INSERT INTO `mk_operation` VALUES ('5883', '3', '28');
INSERT INTO `mk_operation` VALUES ('5882', '3', '27');
INSERT INTO `mk_operation` VALUES ('5881', '3', '26');
INSERT INTO `mk_operation` VALUES ('5880', '3', '25');
INSERT INTO `mk_operation` VALUES ('5879', '3', '24');
INSERT INTO `mk_operation` VALUES ('6192', '4', '75');
INSERT INTO `mk_operation` VALUES ('6191', '4', '70');
INSERT INTO `mk_operation` VALUES ('6190', '4', '55');
INSERT INTO `mk_operation` VALUES ('6189', '4', '54');
INSERT INTO `mk_operation` VALUES ('6188', '4', '53');
INSERT INTO `mk_operation` VALUES ('6187', '4', '52');
INSERT INTO `mk_operation` VALUES ('6186', '4', '51');
INSERT INTO `mk_operation` VALUES ('6185', '4', '50');
INSERT INTO `mk_operation` VALUES ('6184', '4', '49');
INSERT INTO `mk_operation` VALUES ('6183', '4', '48');
INSERT INTO `mk_operation` VALUES ('6182', '4', '47');
INSERT INTO `mk_operation` VALUES ('6181', '4', '46');
INSERT INTO `mk_operation` VALUES ('6180', '4', '45');
INSERT INTO `mk_operation` VALUES ('6179', '4', '44');
INSERT INTO `mk_operation` VALUES ('6178', '4', '43');
INSERT INTO `mk_operation` VALUES ('6177', '4', '42');
INSERT INTO `mk_operation` VALUES ('6176', '4', '41');
INSERT INTO `mk_operation` VALUES ('6175', '4', '40');
INSERT INTO `mk_operation` VALUES ('6174', '4', '39');
INSERT INTO `mk_operation` VALUES ('6173', '4', '38');
INSERT INTO `mk_operation` VALUES ('6172', '4', '37');
INSERT INTO `mk_operation` VALUES ('6171', '4', '36');
INSERT INTO `mk_operation` VALUES ('6170', '4', '35');
INSERT INTO `mk_operation` VALUES ('6169', '4', '34');
INSERT INTO `mk_operation` VALUES ('6168', '4', '33');
INSERT INTO `mk_operation` VALUES ('6167', '4', '32');
INSERT INTO `mk_operation` VALUES ('6166', '4', '31');
INSERT INTO `mk_operation` VALUES ('6165', '4', '30');
INSERT INTO `mk_operation` VALUES ('6164', '4', '29');
INSERT INTO `mk_operation` VALUES ('6163', '4', '28');
INSERT INTO `mk_operation` VALUES ('6162', '4', '27');
INSERT INTO `mk_operation` VALUES ('6161', '4', '26');
INSERT INTO `mk_operation` VALUES ('6160', '4', '25');
INSERT INTO `mk_operation` VALUES ('6159', '4', '24');
INSERT INTO `mk_operation` VALUES ('6158', '4', '23');
INSERT INTO `mk_operation` VALUES ('6157', '4', '22');
INSERT INTO `mk_operation` VALUES ('4870', '6', '41');
INSERT INTO `mk_operation` VALUES ('4869', '6', '40');
INSERT INTO `mk_operation` VALUES ('4868', '6', '39');
INSERT INTO `mk_operation` VALUES ('4867', '6', '38');
INSERT INTO `mk_operation` VALUES ('4866', '6', '37');
INSERT INTO `mk_operation` VALUES ('4865', '6', '36');
INSERT INTO `mk_operation` VALUES ('4864', '6', '35');
INSERT INTO `mk_operation` VALUES ('4839', '5', '74');
INSERT INTO `mk_operation` VALUES ('4838', '5', '73');
INSERT INTO `mk_operation` VALUES ('4837', '5', '67');
INSERT INTO `mk_operation` VALUES ('4836', '5', '66');
INSERT INTO `mk_operation` VALUES ('4863', '6', '34');
INSERT INTO `mk_operation` VALUES ('4862', '6', '33');
INSERT INTO `mk_operation` VALUES ('4861', '6', '32');
INSERT INTO `mk_operation` VALUES ('4860', '6', '31');
INSERT INTO `mk_operation` VALUES ('4859', '6', '30');
INSERT INTO `mk_operation` VALUES ('4858', '6', '29');
INSERT INTO `mk_operation` VALUES ('4857', '6', '28');
INSERT INTO `mk_operation` VALUES ('4856', '6', '27');
INSERT INTO `mk_operation` VALUES ('4855', '6', '26');
INSERT INTO `mk_operation` VALUES ('4854', '6', '25');
INSERT INTO `mk_operation` VALUES ('4853', '6', '24');
INSERT INTO `mk_operation` VALUES ('4852', '6', '23');
INSERT INTO `mk_operation` VALUES ('4851', '6', '22');
INSERT INTO `mk_operation` VALUES ('4850', '6', '21');
INSERT INTO `mk_operation` VALUES ('4849', '6', '74');
INSERT INTO `mk_operation` VALUES ('4848', '6', '73');
INSERT INTO `mk_operation` VALUES ('4847', '6', '67');
INSERT INTO `mk_operation` VALUES ('4846', '6', '66');
INSERT INTO `mk_operation` VALUES ('4845', '6', '65');
INSERT INTO `mk_operation` VALUES ('4844', '6', '64');
INSERT INTO `mk_operation` VALUES ('4843', '6', '4');
INSERT INTO `mk_operation` VALUES ('4842', '6', '3');
INSERT INTO `mk_operation` VALUES ('4841', '6', '2');
INSERT INTO `mk_operation` VALUES ('4840', '6', '1');
INSERT INTO `mk_operation` VALUES ('6429', '1', '13');
INSERT INTO `mk_operation` VALUES ('6428', '1', '12');
INSERT INTO `mk_operation` VALUES ('6427', '1', '78');
INSERT INTO `mk_operation` VALUES ('6426', '1', '11');
INSERT INTO `mk_operation` VALUES ('6425', '1', '10');
INSERT INTO `mk_operation` VALUES ('6424', '1', '9');
INSERT INTO `mk_operation` VALUES ('6423', '1', '77');
INSERT INTO `mk_operation` VALUES ('6422', '1', '8');
INSERT INTO `mk_operation` VALUES ('6156', '4', '21');
INSERT INTO `mk_operation` VALUES ('6155', '4', '20');
INSERT INTO `mk_operation` VALUES ('6154', '4', '19');
INSERT INTO `mk_operation` VALUES ('4829', '7', '3');
INSERT INTO `mk_operation` VALUES ('4828', '7', '2');
INSERT INTO `mk_operation` VALUES ('4827', '7', '1');
INSERT INTO `mk_operation` VALUES ('4876', '8', '47');
INSERT INTO `mk_operation` VALUES ('4875', '8', '46');
INSERT INTO `mk_operation` VALUES ('4874', '8', '45');
INSERT INTO `mk_operation` VALUES ('4873', '8', '44');
INSERT INTO `mk_operation` VALUES ('4872', '8', '43');
INSERT INTO `mk_operation` VALUES ('4871', '8', '42');
INSERT INTO `mk_operation` VALUES ('6421', '1', '7');
INSERT INTO `mk_operation` VALUES ('6420', '1', '6');
INSERT INTO `mk_operation` VALUES ('6419', '1', '5');
INSERT INTO `mk_operation` VALUES ('6418', '1', '74');
INSERT INTO `mk_operation` VALUES ('6417', '1', '73');
INSERT INTO `mk_operation` VALUES ('6416', '1', '67');
INSERT INTO `mk_operation` VALUES ('6415', '1', '66');
INSERT INTO `mk_operation` VALUES ('6414', '1', '65');
INSERT INTO `mk_operation` VALUES ('5878', '3', '23');
INSERT INTO `mk_operation` VALUES ('5877', '3', '22');
INSERT INTO `mk_operation` VALUES ('5876', '3', '21');
INSERT INTO `mk_operation` VALUES ('5875', '3', '20');
INSERT INTO `mk_operation` VALUES ('5874', '3', '19');
INSERT INTO `mk_operation` VALUES ('5873', '3', '18');
INSERT INTO `mk_operation` VALUES ('5872', '3', '17');
INSERT INTO `mk_operation` VALUES ('5871', '3', '16');
INSERT INTO `mk_operation` VALUES ('5870', '3', '15');
INSERT INTO `mk_operation` VALUES ('6153', '4', '18');
INSERT INTO `mk_operation` VALUES ('6152', '4', '17');
INSERT INTO `mk_operation` VALUES ('6151', '4', '16');
INSERT INTO `mk_operation` VALUES ('5844', '2', '52');
INSERT INTO `mk_operation` VALUES ('5843', '2', '51');
INSERT INTO `mk_operation` VALUES ('5842', '2', '50');
INSERT INTO `mk_operation` VALUES ('5841', '2', '49');
INSERT INTO `mk_operation` VALUES ('5840', '2', '48');
INSERT INTO `mk_operation` VALUES ('5839', '2', '47');
INSERT INTO `mk_operation` VALUES ('5838', '2', '46');
INSERT INTO `mk_operation` VALUES ('5837', '2', '45');
INSERT INTO `mk_operation` VALUES ('5836', '2', '44');
INSERT INTO `mk_operation` VALUES ('5835', '2', '43');
INSERT INTO `mk_operation` VALUES ('5834', '2', '42');
INSERT INTO `mk_operation` VALUES ('5833', '2', '41');
INSERT INTO `mk_operation` VALUES ('5832', '2', '40');
INSERT INTO `mk_operation` VALUES ('5831', '2', '39');
INSERT INTO `mk_operation` VALUES ('6150', '4', '15');
INSERT INTO `mk_operation` VALUES ('6149', '4', '14');
INSERT INTO `mk_operation` VALUES ('6148', '4', '13');
INSERT INTO `mk_operation` VALUES ('6147', '4', '12');
INSERT INTO `mk_operation` VALUES ('6146', '4', '78');
INSERT INTO `mk_operation` VALUES ('6145', '4', '11');
INSERT INTO `mk_operation` VALUES ('6144', '4', '10');
INSERT INTO `mk_operation` VALUES ('6143', '4', '9');
INSERT INTO `mk_operation` VALUES ('6142', '4', '8');
INSERT INTO `mk_operation` VALUES ('5567', '19', '3');
INSERT INTO `mk_operation` VALUES ('5566', '19', '2');
INSERT INTO `mk_operation` VALUES ('5565', '19', '1');
INSERT INTO `mk_operation` VALUES ('4835', '5', '65');
INSERT INTO `mk_operation` VALUES ('4834', '5', '64');
INSERT INTO `mk_operation` VALUES ('4833', '5', '4');
INSERT INTO `mk_operation` VALUES ('4832', '5', '3');
INSERT INTO `mk_operation` VALUES ('4831', '5', '2');
INSERT INTO `mk_operation` VALUES ('4830', '5', '1');
INSERT INTO `mk_operation` VALUES ('5908', '3', '53');
INSERT INTO `mk_operation` VALUES ('5909', '3', '54');
INSERT INTO `mk_operation` VALUES ('5910', '3', '55');
INSERT INTO `mk_operation` VALUES ('5911', '3', '70');
INSERT INTO `mk_operation` VALUES ('5912', '3', '75');
INSERT INTO `mk_operation` VALUES ('6141', '4', '7');
INSERT INTO `mk_operation` VALUES ('6140', '4', '6');
INSERT INTO `mk_operation` VALUES ('6413', '1', '64');
INSERT INTO `mk_operation` VALUES ('6412', '1', '4');
INSERT INTO `mk_operation` VALUES ('6411', '1', '3');
INSERT INTO `mk_operation` VALUES ('6410', '1', '2');
INSERT INTO `mk_operation` VALUES ('6409', '1', '1');
INSERT INTO `mk_operation` VALUES ('6408', '1', '0');
INSERT INTO `mk_operation` VALUES ('6139', '4', '5');
INSERT INTO `mk_operation` VALUES ('6138', '4', '74');
INSERT INTO `mk_operation` VALUES ('6137', '4', '73');
INSERT INTO `mk_operation` VALUES ('6136', '4', '67');
INSERT INTO `mk_operation` VALUES ('6135', '4', '66');
INSERT INTO `mk_operation` VALUES ('6134', '4', '65');
INSERT INTO `mk_operation` VALUES ('6133', '4', '64');
INSERT INTO `mk_operation` VALUES ('6132', '4', '4');
INSERT INTO `mk_operation` VALUES ('6131', '4', '3');
INSERT INTO `mk_operation` VALUES ('6130', '4', '2');
INSERT INTO `mk_operation` VALUES ('6129', '4', '1');

-- ----------------------------
-- Table structure for mk_order
-- ----------------------------
DROP TABLE IF EXISTS `mk_order`;
CREATE TABLE `mk_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `plan_id` int(11) NOT NULL COMMENT '计划id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '资源类型:1pc广告2移动广告3外呼4数据5其他',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单名称',
  `target_cpm` int(11) NOT NULL COMMENT '目标cpm',
  `explain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '说明',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否删除1是0否',
  `del_time` int(10) NOT NULL DEFAULT '0' COMMENT '订单删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of mk_order
-- ----------------------------

-- ----------------------------
-- Table structure for mk_order_data
-- ----------------------------
DROP TABLE IF EXISTS `mk_order_data`;
CREATE TABLE `mk_order_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `plan_id` int(11) NOT NULL DEFAULT '0' COMMENT '计划id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `date` int(10) NOT NULL DEFAULT '0' COMMENT '数据日期',
  `pv` int(11) NOT NULL DEFAULT '0' COMMENT '展现量',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of mk_order_data
-- ----------------------------

-- ----------------------------
-- Table structure for mk_plan
-- ----------------------------
DROP TABLE IF EXISTS `mk_plan`;
CREATE TABLE `mk_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '计划名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '计划金额,单位元',
  `business` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '市场商务',
  `start_date` int(10) NOT NULL DEFAULT '0' COMMENT '投放周期开始日期',
  `end_date` int(10) NOT NULL DEFAULT '0' COMMENT '投放周期结束日期',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1待开始，2进行中，3已完成，4已结束',
  `explain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '说明',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否删除1是0否',
  `del_time` int(10) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL COMMENT '0',
  `collection_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '收款状态:1待收款，2部分收款，3完全收款',
  `is_complete` tinyint(2) NOT NULL DEFAULT '0' COMMENT '数据是否完整(是否显示到前后台) 1显示，0不显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of mk_plan
-- ----------------------------

-- ----------------------------
-- Table structure for mk_position
-- ----------------------------
DROP TABLE IF EXISTS `mk_position`;
CREATE TABLE `mk_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '职位名称',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='职位表';

-- ----------------------------
-- Records of mk_position
-- ----------------------------
INSERT INTO `mk_position` VALUES ('1', '管理员', '1502786668');
INSERT INTO `mk_position` VALUES ('2', '销售总监', '1502786680');
INSERT INTO `mk_position` VALUES ('3', '销售组长', '1502786687');
INSERT INTO `mk_position` VALUES ('4', '销售', '1502786705');
INSERT INTO `mk_position` VALUES ('5', '售后', '1502786715');
INSERT INTO `mk_position` VALUES ('6', '内勤', '1502786723');
INSERT INTO `mk_position` VALUES ('7', '渠道', '1502786736');
INSERT INTO `mk_position` VALUES ('8', '财务', '1502786744');

-- ----------------------------
-- Table structure for mk_task
-- ----------------------------
DROP TABLE IF EXISTS `mk_task`;
CREATE TABLE `mk_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '权限名',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  `controller` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '动作',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_menu` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否是标签，1是0否',
  `level` smallint(1) NOT NULL DEFAULT '0',
  `upper` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of mk_task
-- ----------------------------
INSERT INTO `mk_task` VALUES ('1', '计划管理', '0', 'plan', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('2', '计划管理', '1', 'plan', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('3', '计划管理列表', '2', 'plan', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('4', '创建营销计划', '2', 'plan', 'createPlan', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('5', '订单管理', '0', 'order', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('6', 'pc广告', '5', 'order', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('7', 'PC管理列表', '6', 'order', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('8', '录入数据', '6', 'order', 'inputData', '0', '0', '0', '6');
INSERT INTO `mk_task` VALUES ('9', '移动广告', '5', 'order', 'mobileadvert', '0', '1', '2', '0');
INSERT INTO `mk_task` VALUES ('10', '移动广告列表', '9', 'order', 'mobileadvert', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('11', '录入数据', '9', 'order', 'inputData', '0', '0', '0', '9');
INSERT INTO `mk_task` VALUES ('12', '外呼', '5', 'order', 'outbound', '0', '1', '3', '0');
INSERT INTO `mk_task` VALUES ('13', '外呼列表', '12', 'order', 'outbound', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('14', '录入数据', '12', 'order', 'inputData', '0', '0', '0', '12');
INSERT INTO `mk_task` VALUES ('15', '数据', '5', 'order', 'data', '0', '1', '4', '0');
INSERT INTO `mk_task` VALUES ('16', '数据列表', '15', 'order', 'data', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('17', '录入数据', '15', 'order', 'inputData', '0', '0', '0', '15');
INSERT INTO `mk_task` VALUES ('18', '其它', '5', 'order', 'other', '0', '1', '5', '0');
INSERT INTO `mk_task` VALUES ('19', '其它列表', '18', 'order', 'other', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('20', '录入数据', '18', 'order', 'inputData', '0', '0', '0', '18');
INSERT INTO `mk_task` VALUES ('21', '数据管理', '0', 'data', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('22', 'pc广告', '21', 'data', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('23', 'pc广告列表', '22', 'data', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('24', '修改', '22', 'data', 'editData', '0', '0', '0', '22');
INSERT INTO `mk_task` VALUES ('25', '删除', '22', 'data', 'delData', '0', '0', '0', '22');
INSERT INTO `mk_task` VALUES ('26', '移动广告', '21', 'data', 'mobileadvert', '0', '1', '2', '0');
INSERT INTO `mk_task` VALUES ('27', '移动广告列表', '26', 'data', 'mobileadvert', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('28', '修改', '26', 'data', 'editData', '0', '0', '0', '26');
INSERT INTO `mk_task` VALUES ('29', '删除', '26', 'data', 'delData', '0', '0', '0', '26');
INSERT INTO `mk_task` VALUES ('30', '外呼', '21', 'data', 'outbound', '0', '1', '3', '0');
INSERT INTO `mk_task` VALUES ('31', '外呼列表', '30', 'data', 'outbound', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('32', '修改', '30', 'data', 'editData', '0', '0', '0', '30');
INSERT INTO `mk_task` VALUES ('33', '删除', '30', 'data', 'delData', '0', '0', '0', '30');
INSERT INTO `mk_task` VALUES ('34', '数据', '21', 'data', 'data', '0', '1', '4', '0');
INSERT INTO `mk_task` VALUES ('35', '数据列表', '34', 'data', 'data', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('36', '修改', '34', 'data', 'editData', '0', '0', '0', '34');
INSERT INTO `mk_task` VALUES ('37', '删除', '34', 'data', 'delData', '0', '0', '0', '34');
INSERT INTO `mk_task` VALUES ('38', '其它', '21', 'data', 'other', '0', '1', '5', '0');
INSERT INTO `mk_task` VALUES ('39', '其它列表', '38', 'data', 'other', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('40', '修改', '38', 'data', 'editData', '0', '0', '0', '38');
INSERT INTO `mk_task` VALUES ('41', '删除', '38', 'data', 'delData', '0', '0', '0', '38');
INSERT INTO `mk_task` VALUES ('42', '财务管理', '0', 'finance', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('43', '计划收款总表', '42', 'finance', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('44', '计划收款总表列表', '43', 'finance', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('45', '添加收款', '43', 'finance', 'addReceivables', '0', '0', '0', '43');
INSERT INTO `mk_task` VALUES ('46', '状态修改', '43', 'finance', 'editStatus', '0', '0', '0', '43');
INSERT INTO `mk_task` VALUES ('47', '计划收款明细表', '42', 'finance', 'detail', '0', '1', '2', '0');
INSERT INTO `mk_task` VALUES ('48', '计划收款明细列表', '47', 'finance', 'detail', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('49', '编辑', '47', 'finance', 'editDetail', '0', '0', '0', '47');
INSERT INTO `mk_task` VALUES ('50', '删除', '47', 'finance', 'delRecord', '0', '0', '0', '47');
INSERT INTO `mk_task` VALUES ('51', '客户管理', '0', 'customer', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('52', '用户管理', '51', 'customer', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('53', '用户列表', '52', 'customer', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('54', '编辑', '52', 'customer', 'edit', '0', '0', '0', '52');
INSERT INTO `mk_task` VALUES ('55', '冻结', '52', 'customer', 'frozen', '0', '0', '0', '52');
INSERT INTO `mk_task` VALUES ('56', '权限管理', '0', 'System', '', '0', '1', '0', '0');
INSERT INTO `mk_task` VALUES ('57', '权限管理', '56', 'System', 'index', '0', '1', '1', '0');
INSERT INTO `mk_task` VALUES ('58', '职位权限列表', '57', 'System', 'index', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('59', '用户管理', '56', 'System', 'user', '0', '1', '2', '0');
INSERT INTO `mk_task` VALUES ('60', '用户管理列表', '59', 'System', 'user', '0', '0', '0', '0');
INSERT INTO `mk_task` VALUES ('64', '编辑', '2', 'plan', 'editPlan', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('65', '删除', '2', 'plan', 'delPlan', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('66', '复制', '2', 'plan', 'copy', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('68', '编辑', '59', 'System', 'edit', '0', '0', '0', '59');
INSERT INTO `mk_task` VALUES ('67', '状态', '2', 'plan', 'changeStatus', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('69', '冻结', '59', 'System', 'frozen', '0', '0', '0', '59');
INSERT INTO `mk_task` VALUES ('70', '新建客户', '52', 'customer', 'add', '0', '0', '0', '52');
INSERT INTO `mk_task` VALUES ('71', '添加', '59', 'System', 'adduser', '0', '0', '0', '59');
INSERT INTO `mk_task` VALUES ('72', '权限修改', '57', 'System', 'editJurisdiction', '0', '0', '0', '57');
INSERT INTO `mk_task` VALUES ('73', '验证客户', '2', 'plan', 'checkCustomer', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('74', '分配客户', '2', 'plan', 'assignCustomer', '0', '0', '0', '2');
INSERT INTO `mk_task` VALUES ('75', '验证信息唯一性', '52', 'customer', 'check', '0', '0', '0', '52');
INSERT INTO `mk_task` VALUES ('76', '新权限组职位添加', '57', 'System', 'addOper', '0', '0', '0', '57');
INSERT INTO `mk_task` VALUES ('77', '订单状态', '6', 'order', 'editStatus', '0', '0', '0', '6');
INSERT INTO `mk_task` VALUES ('78', '订单状态', '9', 'order', 'editStatus', '0', '0', '0', '9');
INSERT INTO `mk_task` VALUES ('79', '订单状态', '12', 'order', 'editStatus', '0', '0', '0', '12');
INSERT INTO `mk_task` VALUES ('80', '订单状态', '15', 'order', 'editStatus', '0', '0', '0', '15');
INSERT INTO `mk_task` VALUES ('81', '订单状态', '18', 'order', 'editStatus', '0', '0', '0', '18');
