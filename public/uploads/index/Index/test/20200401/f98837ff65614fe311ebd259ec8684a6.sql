# Host: localhost  (Version: 5.5.53)
# Date: 2020-03-29 22:01:09
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cateinfo_shhouse"
#

DROP TABLE IF EXISTS `cateinfo_shhouse`;
CREATE TABLE `cateinfo_shhouse` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类信息-二手房房源信息';

#
# Data for table "cateinfo_shhouse"
#


#
# Structure for table "cateinfo_shhouse_agent"
#

DROP TABLE IF EXISTS `cateinfo_shhouse_agent`;
CREATE TABLE `cateinfo_shhouse_agent` (
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '经纪人姓名',
  `avatar` int(11) NOT NULL DEFAULT '0' COMMENT '头像',
  `working_life` tinyint(3) NOT NULL DEFAULT '0' COMMENT '工作年限',
  `working_position` varchar(30) NOT NULL DEFAULT '' COMMENT '服务地址范围',
  `speciality` varchar(255) NOT NULL DEFAULT '' COMMENT '特长',
  `introduction` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类信息二手房经纪人';

#
# Data for table "cateinfo_shhouse_agent"
#


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员';

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='管理员登录日志';

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='后台操作日志';

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理员权限节点';

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
  `savename` varchar(25) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` varchar(50) NOT NULL DEFAULT '' COMMENT '保存路径',
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
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

#
# Data for table "user"
#


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

