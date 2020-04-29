# Host: localhost  (Version: 5.5.53)
# Date: 2020-04-01 16:27:06
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cateinfo_shhouse"
#

DROP TABLE IF EXISTS `cateinfo_shhouse`;
CREATE TABLE `cateinfo_shhouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_number` varchar(32) NOT NULL DEFAULT '' COMMENT '唯一编号',
  `pictures` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `village_id` int(11) NOT NULL DEFAULT '0' COMMENT '小区id',
  `dong` tinyint(3) NOT NULL DEFAULT '0' COMMENT '栋',
  `unit` tinyint(3) NOT NULL DEFAULT '0' COMMENT '单元',
  `house_num` varchar(15) NOT NULL DEFAULT '' COMMENT '门牌号',
  `build_area` smallint(6) NOT NULL DEFAULT '0' COMMENT '建筑面积',
  `carpet_area` tinyint(3) NOT NULL DEFAULT '0' COMMENT '室内面积',
  `storey` smallint(6) NOT NULL DEFAULT '0' COMMENT '楼层',
  `house_layout` varchar(15) NOT NULL DEFAULT '' COMMENT '户型',
  `transaction_type` varchar(15) NOT NULL DEFAULT '' COMMENT '交易类型',
  `price` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `house_master_name` varchar(15) NOT NULL DEFAULT '' COMMENT '房主姓名',
  `mobile_1` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话1',
  `mobile_2` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话2',
  `house_type` varchar(15) NOT NULL DEFAULT '' COMMENT '房屋类型',
  `property_nature` varchar(15) NOT NULL DEFAULT '' COMMENT '产权性质',
  `build_time` int(11) NOT NULL DEFAULT '0' COMMENT '建房时间',
  `trim_time` int(11) NOT NULL DEFAULT '0' COMMENT '装修时间',
  `installation` varchar(255) NOT NULL DEFAULT '' COMMENT '配套设施',
  `direction` varchar(15) NOT NULL DEFAULT '' COMMENT '房屋朝向',
  `trim_type` varchar(15) NOT NULL DEFAULT '' COMMENT '装修类型',
  `has_elevator` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否有电梯：1是0否',
  `down_payment` tinyint(3) NOT NULL DEFAULT '0' COMMENT '首付比例：0-100',
  `look_up` varchar(15) NOT NULL DEFAULT '' COMMENT '看房方式',
  `certificate` varchar(15) NOT NULL DEFAULT '' COMMENT '证件',
  `now_status` varchar(15) NOT NULL DEFAULT '' COMMENT '房屋现状',
  `pay_type` varchar(15) NOT NULL DEFAULT '' COMMENT '付款方式',
  `has_loan` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否有贷款:1是0否',
  `can_remove_by_youself` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否可以自行解除贷款：1是0否',
  `house_tag` varchar(255) NOT NULL DEFAULT '' COMMENT '房源标签',
  `buy_time` int(11) NOT NULL DEFAULT '0' COMMENT '购买时间',
  `master_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '房主备注信息',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id',
  `agent_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '经纪人备注',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '上架时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '下架时间',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重：数值越大权重越高',
  `is_suggest` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否推荐：1是0否',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `check_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态：0待审核，1审核通过，2审核不通过',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '房屋状态：1正常0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类信息-二手房房源信息';

#
# Data for table "cateinfo_shhouse"
#


#
# Structure for table "cateinfo_shhouse_agent"
#

DROP TABLE IF EXISTS `cateinfo_shhouse_agent`;
CREATE TABLE `cateinfo_shhouse_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '经纪人姓名',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '经纪人电话',
  `avatar` int(11) NOT NULL DEFAULT '0' COMMENT '头像',
  `working_life` tinyint(3) NOT NULL DEFAULT '0' COMMENT '工作年限',
  `working_position` varchar(30) NOT NULL DEFAULT '' COMMENT '服务地址范围',
  `speciality` varchar(255) NOT NULL DEFAULT '' COMMENT '特长',
  `introduction` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '经纪人状态：1正常0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类信息二手房经纪人';

#
# Data for table "cateinfo_shhouse_agent"
#


#
# Structure for table "cateinfo_shhouse_escort_order"
#

DROP TABLE IF EXISTS `cateinfo_shhouse_escort_order`;
CREATE TABLE `cateinfo_shhouse_escort_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id',
  `shhouse_id` int(11) NOT NULL DEFAULT '0' COMMENT '预约房源id',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '订单金额',
  `pay_amount` int(11) NOT NULL DEFAULT '0' COMMENT '支付金额',
  `pay_validity_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付有效期，过期不能再支付',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `pay_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付方式：1微信，2余额，3vip抵扣',
  `refund_time` int(11) NOT NULL DEFAULT '0' COMMENT '申请退款时间',
  `refund_complate_time` int(11) NOT NULL DEFAULT '0' COMMENT '退款完成时间',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态：0待支付，1已支付，待同意，2已同意，待服务完成，3服务完成，4经纪人忙，暂时无法提供服务，5用户申请退款（只有4状态才能申请退款），6退款完成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='陪同服务订单';

#
# Data for table "cateinfo_shhouse_escort_order"
#


#
# Structure for table "cateinfo_shhouse_user"
#

DROP TABLE IF EXISTS `cateinfo_shhouse_user`;
CREATE TABLE `cateinfo_shhouse_user` (
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `is_vip` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否是vip：1是0否',
  `vip_end_time` int(11) NOT NULL DEFAULT '0' COMMENT 'vip截止时间',
  `look_mobile_times` int(11) NOT NULL DEFAULT '0' COMMENT '拥有查看电话次数',
  `escort_service_times` int(11) NOT NULL DEFAULT '0' COMMENT '拥有免费陪同看房服务次数',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息扩展-二手房';

#
# Data for table "cateinfo_shhouse_user"
#

INSERT INTO `cateinfo_shhouse_user` VALUES (12,b'0',0,0,0);

#
# Structure for table "cateinfo_shhouse_village_config"
#

DROP TABLE IF EXISTS `cateinfo_shhouse_village_config`;
CREATE TABLE `cateinfo_shhouse_village_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region` varchar(30) NOT NULL DEFAULT '' COMMENT '区域',
  `street` varchar(30) NOT NULL DEFAULT '' COMMENT '街道',
  `village` varchar(30) NOT NULL DEFAULT '' COMMENT '小区',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1可用0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类信息房屋楼盘配置';

#
# Data for table "cateinfo_shhouse_village_config"
#


#
# Structure for table "system_admin"
#

DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `admin_name` varchar(55) NOT NULL COMMENT '管理员名字',
  `admin_password` varchar(32) NOT NULL COMMENT '管理员密码',
  `role_id` int(11) DEFAULT NULL COMMENT '所属角色',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员';

#
# Data for table "system_admin"
#

INSERT INTO `system_admin` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3',1,1,'2019-09-03 13:31:20','2020-03-29 20:57:45',NULL),(3,'小白','d41d8cd98f00b204e9800998ecf8427e',6,1,'0000-00-00 00:00:00','2019-10-11 10:32:38',NULL),(4,'tyong','e10adc3949ba59abbe56e057f20f883e',6,1,'0000-00-00 00:00:00','2020-03-29 21:12:31',NULL);

#
# Structure for table "system_admin_login_log"
#

DROP TABLE IF EXISTS `system_admin_login_log`;
CREATE TABLE `system_admin_login_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `login_user` varchar(55) NOT NULL COMMENT '登录用户',
  `login_ip` varchar(15) NOT NULL COMMENT '登录ip',
  `login_area` varchar(55) DEFAULT NULL COMMENT '登录地区',
  `login_user_agent` varchar(155) DEFAULT NULL COMMENT '登录设备头',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `login_status` tinyint(1) DEFAULT '1' COMMENT '登录状态 1 成功 2 失败',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='管理员登录日志';

#
# Data for table "system_admin_login_log"
#

INSERT INTO `system_admin_login_log` VALUES (1,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36','2019-10-11 16:03:07',1),(2,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-25 18:18:19',1),(3,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36','2020-03-25 18:32:34',1),(4,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-27 10:34:45',2),(5,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-27 10:38:35',2),(6,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-27 10:39:23',2),(7,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-27 10:41:25',2),(8,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-27 10:41:41',1),(9,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 11:41:22',2),(10,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 11:41:31',1),(11,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 19:28:15',2),(12,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 19:28:32',1),(13,'tyong','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 19:44:08',1),(14,'admin','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 20:57:45',1),(15,'tyong','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36','2020-03-29 20:59:22',1),(16,'tyong','127.0.0.1','内网IP-内网IP','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400','2020-03-29 21:12:31',1);

#
# Structure for table "system_admin_operate_log"
#

DROP TABLE IF EXISTS `system_admin_operate_log`;
CREATE TABLE `system_admin_operate_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作日志id',
  `operator` varchar(55) NOT NULL COMMENT '操作用户',
  `operator_ip` varchar(15) NOT NULL COMMENT '操作者ip',
  `operate_method` varchar(100) NOT NULL COMMENT '操作方法',
  `operate_desc` varchar(155) NOT NULL COMMENT '操作简述',
  `operate_time` datetime NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='后台操作日志';

#
# Data for table "system_admin_operate_log"
#

INSERT INTO `system_admin_operate_log` VALUES (1,'admin','127.0.0.1','manager/editadmin','编辑管理员小白','2019-10-11 14:19:21'),(2,'admin','127.0.0.1','role/assignauthority','分配权限6','2019-10-11 14:19:37'),(3,'admin','127.0.0.1','role/edit','编辑角色研发','2019-10-11 14:19:40'),(4,'admin','127.0.0.1','role/edit','编辑角色：部门经理','2019-10-11 14:21:33'),(5,'admin','127.0.0.1','node/edit','编辑节点：主页','2019-10-11 14:22:18'),(6,'admin','127.0.0.1','node/delete','删除节点：10','2020-03-25 18:57:05'),(7,'admin','127.0.0.1','role/add','添加角色：测试角色','2020-03-27 11:51:24'),(8,'admin','127.0.0.1','role/add','添加角色：测试角色','2020-03-27 11:54:18'),(9,'admin','127.0.0.1','role/add','添加角色：测试角色1','2020-03-27 11:54:22'),(10,'admin','127.0.0.1','node/add','添加节点：系统管理','2020-03-29 19:31:00'),(11,'admin','127.0.0.1','manager/addadmin','添加管理员：tyong','2020-03-29 19:42:55'),(12,'admin','127.0.0.1','role/assignauthority','分配权限：6','2020-03-29 21:02:07'),(13,'tyong','127.0.0.1','node/add','添加节点：系统设置','2020-03-29 21:19:31'),(14,'tyong','127.0.0.1','role/assignauthority','分配权限：6','2020-03-29 21:19:53');

#
# Structure for table "system_admin_power_node"
#

DROP TABLE IF EXISTS `system_admin_power_node`;
CREATE TABLE `system_admin_power_node` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `node_name` varchar(55) NOT NULL COMMENT '节点名称',
  `node_path` varchar(55) NOT NULL COMMENT '节点路径',
  `node_pid` int(11) NOT NULL COMMENT '所属节点',
  `node_icon` varchar(55) DEFAULT NULL COMMENT '节点图标',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1 不是 2 是',
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`node_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理员权限节点';

#
# Data for table "system_admin_power_node"
#

INSERT INTO `system_admin_power_node` VALUES (1,'主页','#',0,'layui-icon layui-icon-home',2,'2019-09-03 14:17:38'),(2,'后台首页','index/index',1,'',1,'2019-09-03 14:18:24'),(3,'修改密码','index/editpwd',1,'',1,'2019-09-03 14:19:03'),(4,'权限管理','#',0,'layui-icon layui-icon-template',2,'2019-09-03 14:19:34'),(5,'管理员管理','manager/index',4,'',2,'2019-09-03 14:27:42'),(6,'添加管理员','manager/addadmin',5,'',1,'2019-09-03 14:28:26'),(7,'编辑管理员','manager/editadmin',5,'',1,'2019-09-03 14:28:43'),(8,'删除管理员','manager/deladmin',5,'',1,'2019-09-03 14:29:14'),(9,'日志管理','#',0,'layui-icon layui-icon-template-1',2,'2019-10-08 16:07:36'),(11,'登录日志','log/login',9,'',2,'2019-10-08 16:26:27'),(12,'操作日志','log/operate',9,'',2,'2019-10-08 17:02:10'),(13,'角色管理','role/index',4,'',2,'2019-10-09 21:35:54'),(14,'添加角色','role/add',13,'',1,'2019-10-09 21:40:06'),(15,'编辑角色','role/edit',13,'',1,'2019-10-09 21:40:53'),(16,'删除角色','role/delete',13,'',1,'2019-10-09 21:41:07'),(17,'权限分配','role/assignauthority',13,'',1,'2019-10-09 21:41:38'),(18,'节点管理','node/index',4,'',2,'2019-10-09 21:42:06'),(19,'添加节点','node/add',18,'',1,'2019-10-09 21:42:51'),(20,'编辑节点','node/edit',18,'',1,'2019-10-09 21:43:29'),(21,'删除节点','node/delete',18,'',1,'2019-10-09 21:43:44'),(22,'系统管理','#',0,'',2,'2020-03-29 19:31:00'),(23,'系统设置','systemconfig/index',22,'',2,'2020-03-29 21:19:31');

#
# Structure for table "system_admin_role"
#

DROP TABLE IF EXISTS `system_admin_role`;
CREATE TABLE `system_admin_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(55) NOT NULL COMMENT '角色名称',
  `role_node` varchar(255) NOT NULL COMMENT '角色拥有的权限节点',
  `role_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '角色状态 1 启用 2 禁用',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员角色';

#
# Data for table "system_admin_role"
#

INSERT INTO `system_admin_role` VALUES (1,'超级管理员','#',1),(3,'会计','1,2,3',1),(4,'部门经理','1,2,3,4,5,6,7,8',1),(5,'DBA','1,2,3',1),(6,'研发','1,2,3,4,5,6,7,8,13,14,15,16,17,18,19,20,21,9,11,12,22,23',1),(7,'测试角色','1,2,3',1),(8,'测试角色1','1,2,3',1);

#
# Structure for table "system_article"
#

DROP TABLE IF EXISTS `system_article`;
CREATE TABLE `system_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类id',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '文章标题',
  `describe` varchar(300) NOT NULL DEFAULT '' COMMENT '文章概要',
  `content` text COMMENT '文章内容',
  `cover` int(11) NOT NULL DEFAULT '0' COMMENT '文章封面图',
  `pictures` varchar(255) NOT NULL DEFAULT '' COMMENT '文章展示图',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重，值越大权重越高',
  `is_suggest` bit(1) NOT NULL DEFAULT b'0' COMMENT '推荐：1是0否',
  `collections` int(11) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `out_link` varchar(255) NOT NULL DEFAULT '' COMMENT '外链',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态：1可用，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统文章';

#
# Data for table "system_article"
#


#
# Structure for table "system_article_category"
#

DROP TABLE IF EXISTS `system_article_category`;
CREATE TABLE `system_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类id',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '分类等级，顶级分类为1',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `icon` int(11) NOT NULL DEFAULT '0' COMMENT '分类图标',
  `out_link` varchar(255) NOT NULL DEFAULT '' COMMENT '外链',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态：1可用，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统文章分类';

#
# Data for table "system_article_category"
#


#
# Structure for table "system_banner"
#

DROP TABLE IF EXISTS `system_banner`;
CREATE TABLE `system_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `picture` int(11) NOT NULL DEFAULT '0' COMMENT '展示图',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `target_rule` varchar(30) NOT NULL DEFAULT '' COMMENT '跳转规则',
  `target_param` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转参数',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '展示开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '展示结束时间',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重：值越大权重越高',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态：1可用0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统轮播图，广告图';

#
# Data for table "system_banner"
#


#
# Structure for table "system_config"
#

DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` text COMMENT '配置值',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '配置类型，不同类型对应不同的编辑方式',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '配置分组,便于后台分组管理',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '配置标题',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '配置说明',
  `enum_config` varchar(255) NOT NULL DEFAULT '' COMMENT '枚举配置，只有配置类型为枚举的时候该字段才会用到',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='系统配置';

#
# Data for table "system_config"
#

INSERT INTO `system_config` VALUES (1,'config_type','[\"string\", \"number\", \"array\", \"enum\", \"image\", \"text\"]','array','系统配置','配置类型','配置类型','',0,0),(2,'config_group','[\"基本配置\", \"系统配置\"]','array','系统配置','配置分组','配置分组','',0,0);

#
# Structure for table "system_file"
#

DROP TABLE IF EXISTS `system_file`;
CREATE TABLE `system_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '文件原名称',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `extend_name` varchar(10) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `mime` varchar(30) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `md5` varchar(32) NOT NULL DEFAULT '' COMMENT 'MD5值',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT 'sha1值',
  `uri` varchar(100) NOT NULL DEFAULT '' COMMENT '资源地址',
  `save_host` varchar(20) NOT NULL DEFAULT 'localhsot' COMMENT '保存主机:localhost本地',
  `carete_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mime_md5` (`mime`,`md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统文件';

#
# Data for table "system_file"
#


#
# Structure for table "system_guestbook"
#

DROP TABLE IF EXISTS `system_guestbook`;
CREATE TABLE `system_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态：1已读0未读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统留言板，意见，建议等';

#
# Data for table "system_guestbook"
#


#
# Structure for table "system_verify_code"
#

DROP TABLE IF EXISTS `system_verify_code`;
CREATE TABLE `system_verify_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `m_code` varchar(15) NOT NULL DEFAULT '' COMMENT '手机验证码',
  `send_times` smallint(6) NOT NULL DEFAULT '0' COMMENT '当日发送次数',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `status` bit(1) NOT NULL DEFAULT b'0' COMMENT '状态:1已使用0未使用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`) COMMENT '一个手机号只有一条记录'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统短信验证码';

#
# Data for table "system_verify_code"
#

INSERT INTO `system_verify_code` VALUES (1,'18223239699','0280',3,1585724916,1585725816,b'1');

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `wxapp_openid` varchar(100) DEFAULT NULL COMMENT '微信小程序openid',
  `wx_openid` varchar(100) DEFAULT NULL COMMENT '微信三方登陆openid',
  `qq_openid` varchar(100) DEFAULT NULL COMMENT 'qq三方登陆openid',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `avatar` int(11) NOT NULL DEFAULT '0' COMMENT '头像',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(3) NOT NULL DEFAULT '0' COMMENT '性别：1男0女',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `register_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `register_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '注册ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '最近登陆ip',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态：1正常0异常',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) COMMENT '用户名唯一',
  UNIQUE KEY `mobile` (`mobile`) COMMENT '手机号唯一'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户';

#
# Data for table "user"
#

INSERT INTO `user` VALUES (1,'tyong','','123','','','','','','',0,0,0,0,0.00,0,0,'',0,'',0,1),(3,NULL,'','1234','','','','','',NULL,0,0,0,0,0.00,0,0,'',0,'',0,1),(4,'ret','123456','12345','12345','12345','','','','18223239699',0,0,0,0,0.00,0,0,'',1585724952,'127.0.0.1',1,1),(5,'1111','123456',NULL,NULL,'12345','','','','18223239691',0,0,0,0,0.00,0,0,'',0,'',0,1),(6,'1','123456',NULL,NULL,'12345','','','','18223239692',0,0,0,0,0.00,0,0,'',0,'',0,1),(7,'13','123456',NULL,NULL,'12345','系统分配昵称','','','18223239693',1,0,0,0,0.00,0,0,'',0,'',0,1),(8,'131','123456',NULL,NULL,'12345','系统分配昵称','','','18223239694',1,0,0,0,0.00,0,1585643740,'127.0.0.1',1585649358,'127.0.0.1',9,1),(9,NULL,'',NULL,NULL,NULL,'系统分配昵称','','','18223239612',1,0,0,0,0.00,0,1585649369,'127.0.0.1',0,'',0,1),(10,NULL,'',NULL,NULL,NULL,'系统分配昵称','','','182232396121',1,0,0,0,0.00,0,1585649530,'127.0.0.1',0,'',0,1),(12,NULL,'',NULL,NULL,NULL,'系统分配昵称','','','18223239',1,0,0,0,0.00,0,1585650139,'127.0.0.1',1585706026,'127.0.0.1',1,1);

#
# Structure for table "user_banlance_recode"
#

DROP TABLE IF EXISTS `user_banlance_recode`;
CREATE TABLE `user_banlance_recode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `source` varchar(60) NOT NULL DEFAULT '' COMMENT '记录来源,分类',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `trend` bit(1) NOT NULL DEFAULT b'1' COMMENT '余额动向:1收入0支出',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1有效0无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户余额记录';

#
# Data for table "user_banlance_recode"
#


#
# Structure for table "user_browse_record"
#

DROP TABLE IF EXISTS `user_browse_record`;
CREATE TABLE `user_browse_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `source` varchar(60) NOT NULL DEFAULT '' COMMENT '浏览数据来源,分类',
  `record_id` int(11) NOT NULL DEFAULT '0' COMMENT '浏览记录的id',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1有效0无效',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户浏览记录';

#
# Data for table "user_browse_record"
#


#
# Structure for table "user_cash_record"
#

DROP TABLE IF EXISTS `user_cash_record`;
CREATE TABLE `user_cash_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `source` varchar(60) NOT NULL DEFAULT '' COMMENT '记录来源,分类',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `pay_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '交易方式:1微信2支付宝',
  `trend` bit(1) NOT NULL DEFAULT b'0' COMMENT '动向:1收入,0支出',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1有效0无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户现金交易记录';

#
# Data for table "user_cash_record"
#


#
# Structure for table "user_collection_record"
#

DROP TABLE IF EXISTS `user_collection_record`;
CREATE TABLE `user_collection_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `source` varchar(60) NOT NULL DEFAULT '' COMMENT '收藏数据来源,分类',
  `record_id` int(11) NOT NULL DEFAULT '0' COMMENT '收藏记录的id',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1有效0无效',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户浏览记录';

#
# Data for table "user_collection_record"
#


#
# Structure for table "user_site_message"
#

DROP TABLE IF EXISTS `user_site_message`;
CREATE TABLE `user_site_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `source` varchar(60) NOT NULL DEFAULT '' COMMENT '消息来源,分类',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0未读,1已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户站内信提醒';

#
# Data for table "user_site_message"
#

