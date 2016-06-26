/*
Navicat MySQL Data Transfer

Source Server         : astion
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-01-16 10:45:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ast_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ast_admin`;
CREATE TABLE `ast_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `admin_username` varchar(16) NOT NULL COMMENT '管理员用户名',
  `admin_password` varchar(16) NOT NULL COMMENT '管理员密码',
  `admin_level` enum('1','2') NOT NULL COMMENT '管理员级别',
  `admin_relname` varchar(8) NOT NULL COMMENT '管理员名字',
  `admin_power` set('文章列表','添加文章','文章分类','产品列表','添加产品','产品分类','会员列表','添加会员','第三方账号','留言管理','评价管理','banner管理','广告位管理','图片链接','文字链接','基础设置','seo设置') NOT NULL COMMENT '管理员权限',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of ast_admin
-- ----------------------------
INSERT INTO `ast_admin` VALUES ('1', 'admin', '123asd', '1', 'aaa', '文章列表,添加文章,文章分类,产品列表,添加产品,产品分类,会员列表,添加会员,第三方账号,留言管理,评价管理,banner管理,广告位管理,图片链接,文字链接,基础设置,seo设置');
INSERT INTO `ast_admin` VALUES ('2', 'admin1', '123asd', '2', '', '');
INSERT INTO `ast_admin` VALUES ('3', 'admin2', '123asd', '2', '', '文章列表,添加文章,文章分类,产品列表,添加产品,产品分类,会员列表,添加会员,第三方账号,留言管理,评价管理,banner管理,广告位管理,图片链接,文字链接,基础设置,seo设置');

-- ----------------------------
-- Table structure for `ast_banner`
-- ----------------------------
DROP TABLE IF EXISTS `ast_banner`;
CREATE TABLE `ast_banner` (
  `ban_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'bannerID',
  `ban_name` varchar(16) NOT NULL COMMENT 'banner名称',
  `ban_order` tinyint(4) NOT NULL COMMENT 'banner排序',
  `ban_gourl` varchar(100) NOT NULL COMMENT 'banner跳转页面',
  `ban_url` varchar(100) NOT NULL COMMENT 'banner图地址',
  `ban_pageid` tinyint(4) NOT NULL COMMENT 'banner所属页面ID',
  PRIMARY KEY (`ban_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='banner表';

-- ----------------------------
-- Records of ast_banner
-- ----------------------------
INSERT INTO `ast_banner` VALUES ('14', 'banner_1', '1', '/Public/Uploads/2016-01-16/56999ee4a0ef6.jpg', '/Public/Uploads/2016-01-16/56999ee4a0ef6.jpg', '0');
INSERT INTO `ast_banner` VALUES ('13', 'banner_2', '2', '', '/Public/Uploads/2016-01-16/56999e66378c4.jpg', '0');
INSERT INTO `ast_banner` VALUES ('12', 'banner_3', '3', '/Public/Uploads/2016-01-16/56999e59632ae.jpg', '/Public/Uploads/2016-01-16/56999e59632ae.jpg', '0');
INSERT INTO `ast_banner` VALUES ('15', '4', '4', '', '/Public/Uploads/2016-01-16/5699a0ce3be08.jpg', '0');

-- ----------------------------
-- Table structure for `ast_banner_page`
-- ----------------------------
DROP TABLE IF EXISTS `ast_banner_page`;
CREATE TABLE `ast_banner_page` (
  `bp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'banner所属页面ID',
  `bp_name` varchar(16) NOT NULL COMMENT 'banner所属页面名称',
  PRIMARY KEY (`bp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ast_banner_page
-- ----------------------------
INSERT INTO `ast_banner_page` VALUES ('1', '首页');
INSERT INTO `ast_banner_page` VALUES ('2', '大足节会');
INSERT INTO `ast_banner_page` VALUES ('3', '旅游资讯');
INSERT INTO `ast_banner_page` VALUES ('4', '最新活动');
INSERT INTO `ast_banner_page` VALUES ('5', '节会新闻');
INSERT INTO `ast_banner_page` VALUES ('6', '资金管理');

-- ----------------------------
-- Table structure for `ast_label`
-- ----------------------------
DROP TABLE IF EXISTS `ast_label`;
CREATE TABLE `ast_label` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `label_content` varchar(100) NOT NULL,
  `label_procate_id` int(11) NOT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ast_label
-- ----------------------------
INSERT INTO `ast_label` VALUES ('1', '服装-男装-女装/品牌-李宁-nake/类型-羽绒服-夹克-体恤', '1');
INSERT INTO `ast_label` VALUES ('3', '服装-男装-女装/品牌-李宁-nake/类型-羽绒服-夹克-体恤', '2');

-- ----------------------------
-- Table structure for `ast_member`
-- ----------------------------
DROP TABLE IF EXISTS `ast_member`;
CREATE TABLE `ast_member` (
  `vip_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `vip_username` varchar(16) NOT NULL COMMENT '用户名',
  `vip_account` varchar(16) NOT NULL COMMENT '账号',
  `vip_password` varchar(16) NOT NULL COMMENT '密码',
  `vip_mail` varchar(25) NOT NULL COMMENT '邮箱',
  `vip_shopping_cart` varchar(2000) NOT NULL COMMENT '购物车',
  `vip_avatar` varchar(100) NOT NULL COMMENT '头像',
  `vip_address` varchar(1000) DEFAULT '' COMMENT '会员地址',
  `vip_addtime` varchar(30) DEFAULT NULL COMMENT '注册时间',
  `vip_birthday` varchar(255) DEFAULT NULL COMMENT '生日',
  `vip_relname` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `vip_tel` varchar(255) DEFAULT NULL COMMENT '电话',
  `vip_zipCode` varchar(255) DEFAULT NULL,
  `vip_state` varchar(255) DEFAULT NULL,
  `vip_fixedTel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vip_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ast_member
-- ----------------------------
INSERT INTO `ast_member` VALUES ('1', '丿灬依丶小念', 'asdasd1', 'asdasd', 'asdasd1@qq.com', '李宁连帽卫衣-2016011413300001;李宁连帽卫衣-2016011413300001;李宁连帽卫衣-2016011413300001', '/Public/Home/images/icon17.png', '', null, null, null, null, null, '0', null);
INSERT INTO `ast_member` VALUES ('2', '山斗坝的召唤师', 'asdasd2', 'asdasd', 'asdasd2@qq.com', '', '', '', null, null, null, null, null, '0', null);
INSERT INTO `ast_member` VALUES ('3', 'CqKx男子汉', 'asdasd3', 'asdasd', 'asdasd3@qq.com', '', '', '', null, null, null, null, null, null, null);
INSERT INTO `ast_member` VALUES ('4', '123456', 'demo12345', '123456', '994724434@qq.com', '', '', '重庆合川', null, null, null, null, null, null, null);
INSERT INTO `ast_member` VALUES ('5', '张飞很好啊啊', '7812345', '123456', '994724434@qq.com', '', '', '重庆合川', '1452828302', '2016-01-15', '李海龙', '18883276432', '重庆合川', '1', '42354343');
INSERT INTO `ast_member` VALUES ('6', '', '李海龙', '123456', '994724435@qq.com', '', '', '', null, null, null, null, null, null, null);
INSERT INTO `ast_member` VALUES ('7', '', '花花世界', '123456', '994724435@qq.com', '', '', '', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for `ast_nav`
-- ----------------------------
DROP TABLE IF EXISTS `ast_nav`;
CREATE TABLE `ast_nav` (
  `nav_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nav_name` varchar(20) NOT NULL COMMENT '名称',
  `nav_pid` tinyint(4) NOT NULL COMMENT '父ID',
  `nav_url` varchar(100) NOT NULL COMMENT '导航链接',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='导航表';

-- ----------------------------
-- Records of ast_nav
-- ----------------------------
INSERT INTO `ast_nav` VALUES ('1', '文章管理', '0', '');
INSERT INTO `ast_nav` VALUES ('2', '产品管理', '0', '');
INSERT INTO `ast_nav` VALUES ('3', '会员管理', '0', '');
INSERT INTO `ast_nav` VALUES ('4', '营销活动', '0', '');
INSERT INTO `ast_nav` VALUES ('5', '图片管理', '0', '');
INSERT INTO `ast_nav` VALUES ('6', '友情链接', '0', '');
INSERT INTO `ast_nav` VALUES ('7', '网站设置', '0', '');
INSERT INTO `ast_nav` VALUES ('8', '文章列表', '1', '/Admin/Article/index');
INSERT INTO `ast_nav` VALUES ('9', '添加文章', '1', '/Admin/Article/add');
INSERT INTO `ast_nav` VALUES ('10', '文章分类', '1', '/Admin/Article/sort');
INSERT INTO `ast_nav` VALUES ('11', '产品列表', '2', '/Admin/Product/index');
INSERT INTO `ast_nav` VALUES ('12', '添加产品', '2', '/Admin/Product/add');
INSERT INTO `ast_nav` VALUES ('13', '产品分类', '2', '/Admin/Product/sort');
INSERT INTO `ast_nav` VALUES ('14', '会员信息', '3', '/Admin/Member/index');
INSERT INTO `ast_nav` VALUES ('15', '添加会员', '3', '/Admin/Member/add');
INSERT INTO `ast_nav` VALUES ('17', '留言管理', '4', '/Admin/Message/index');
INSERT INTO `ast_nav` VALUES ('18', '评价管理', '4', '');
INSERT INTO `ast_nav` VALUES ('19', 'banner管理', '5', '/Admin/Photo/index');
INSERT INTO `ast_nav` VALUES ('20', '广告位管理', '5', '');
INSERT INTO `ast_nav` VALUES ('22', '文字链接', '6', '/Admin/Link/words_link');
INSERT INTO `ast_nav` VALUES ('23', '基础设置', '7', ' ');
INSERT INTO `ast_nav` VALUES ('24', 'seo设置', '7', '/Admin/Setup/index');
INSERT INTO `ast_nav` VALUES ('25', '旅游景点', '7', '/Admin/Setup/one_picture');
INSERT INTO `ast_nav` VALUES ('26', '出行方式', '7', '/Admin/Setup/trip_mode');

-- ----------------------------
-- Table structure for `ast_procate`
-- ----------------------------
DROP TABLE IF EXISTS `ast_procate`;
CREATE TABLE `ast_procate` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `cate_name` varchar(16) NOT NULL COMMENT '分类名称',
  `cate_pid` tinyint(4) NOT NULL COMMENT '分类父ID',
  `cate_order` tinyint(4) NOT NULL COMMENT '分类排序',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='产品分类表';

-- ----------------------------
-- Records of ast_procate
-- ----------------------------
INSERT INTO `ast_procate` VALUES ('1', '服装', '0', '1');
INSERT INTO `ast_procate` VALUES ('2', '特产', '0', '1');
INSERT INTO `ast_procate` VALUES ('3', '美食', '0', '1');
INSERT INTO `ast_procate` VALUES ('4', '男装', '1', '1');
INSERT INTO `ast_procate` VALUES ('5', '女装', '1', '2');
INSERT INTO `ast_procate` VALUES ('6', '民族服装', '1', '3');
INSERT INTO `ast_procate` VALUES ('7', '肉松饼', '2', '2');
INSERT INTO `ast_procate` VALUES ('8', '巧克力', '2', '1');
INSERT INTO `ast_procate` VALUES ('9', '蛋糕', '2', '1');
INSERT INTO `ast_procate` VALUES ('10', '童装', '1', '4');

-- ----------------------------
-- Table structure for `ast_procmt_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ast_procmt_reply`;
CREATE TABLE `ast_procmt_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回复者ID',
  `reply_user` varchar(16) NOT NULL COMMENT '回复者名称',
  `reply_content` text NOT NULL COMMENT '回复内容',
  `reply_addtime` int(11) NOT NULL COMMENT '回复时间',
  `reply_cmtid` int(11) NOT NULL COMMENT '回复的内容的ID',
  PRIMARY KEY (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品评论回复表';

-- ----------------------------
-- Records of ast_procmt_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `ast_procomment`
-- ----------------------------
DROP TABLE IF EXISTS `ast_procomment`;
CREATE TABLE `ast_procomment` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论者ID',
  `cmt_user` varchar(16) NOT NULL COMMENT '评论者名称',
  `cmt_content` text NOT NULL COMMENT '评论内容',
  `cmt_addtime` int(11) NOT NULL COMMENT '评论时间',
  `cmt_pro_id` int(11) NOT NULL COMMENT '评论的产品的ID',
  PRIMARY KEY (`cmt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='产品评论表';

-- ----------------------------
-- Records of ast_procomment
-- ----------------------------

-- ----------------------------
-- Table structure for `ast_product`
-- ----------------------------
DROP TABLE IF EXISTS `ast_product`;
CREATE TABLE `ast_product` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pro_title` varchar(200) NOT NULL COMMENT '标题',
  `pro_subhead` varchar(200) NOT NULL COMMENT '副标题',
  `pro_thumb` varchar(100) NOT NULL COMMENT '缩略图',
  `pro_attribute` varchar(500) NOT NULL COMMENT '属性',
  `pro_photo1` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo2` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo3` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo4` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo5` varchar(100) NOT NULL COMMENT '照片',
  `pro_cost_price` float NOT NULL COMMENT '原价',
  `pro_current_price` float NOT NULL COMMENT '现价',
  `pro_package_price` float NOT NULL COMMENT '组合价格',
  `pro_deal` int(11) NOT NULL COMMENT '交易成功数',
  `pro_stocknum` int(11) NOT NULL COMMENT '库存',
  `pro_discuss` int(11) NOT NULL COMMENT '评论数',
  `pro_classify_id` int(11) NOT NULL COMMENT '分类ID',
  `pro_order` int(11) DEFAULT '1',
  `pro_introduction` varchar(2000) DEFAULT NULL COMMENT '商品详情',
  `pro_isshow` int(4) DEFAULT NULL COMMENT '是否显示',
  `pro_sku` varchar(16) NOT NULL COMMENT '商品号',
  `pro_promotion_price` float NOT NULL COMMENT '促销价',
  `pro_freight` float NOT NULL COMMENT '运费',
  `pro_labels` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='产品';

-- ----------------------------
-- Records of ast_product
-- ----------------------------
INSERT INTO `ast_product` VALUES ('22', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK677', '玫瑰花 静谧摄影壁纸1', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '颜色-红色-3/尺寸-13-25/重量-23/ajo-asda-asd/艾斯顿-asdaasd-asdasdasd', '/Public/Home/images/9.jpg', '/Public/Home/images/15.jpg', '/Public/Home/images/16.jpg', '/Public/Home/images/17.jpg', '/Public/Uploads/2016-01-15/56988b5de5cee.jpg', '43', '599', '0', '10', '100000', '735', '1', '0', 'sdfasdfasdfasdf', '1', '201601141330000', '238', '0', null);
INSERT INTO `ast_product` VALUES ('18', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK677', '天猫认证 官方正品 假一罚十', '/Public/Uploads/2016-01-11/569372ffa6aa9.jpg', '颜色-红色-3/尺寸-13-25/重量-14-25', '/Public/Uploads/2016-01-15/5698881366a33.jpg', '/Public/Uploads/2016-01-15/5698883c17814.jpg', '', '', '', '43', '23', '0', '0', '0', '0', '1', '0', 'sdfasdfasdfasdf', '1', '201601141330000', '0', '0', null);
INSERT INTO `ast_product` VALUES ('19', '天猫认证 官方正品 假一罚3', '', '/Public/Uploads/2016-01-11/5693737ae4ef0.jpg', '19-颜色-红色-白色', '', '', '', '', '', '199', '34', '0', '0', '0', '0', '1', '0', '&lt;div style=&quot;text-align:left;&quot;&gt;\r\n	&lt;span style=&quot;font-size:12px;line-height:1.5;&quot;&gt;dfasdfasdfasfasdfasfdasdfasfdasfasf&lt;/span&gt; \r\n&lt;/div&gt;', '1', '201601141330000', '0', '0', '男装-nake-夹克');
INSERT INTO `ast_product` VALUES ('20', '天猫认证 官方正品 假一罚十', '', '/Public/Uploads/2016-01-11/569373bd0e3e7.jpg', '20-颜色 -白色-红色', '', '', '', '', '', '199', '23', '0', '0', '0', '0', '7', '0', 'dfgsdgsdfgsdfgsdfgsdfgsdffsd', '1', '2016011413300001', '0', '0', '男装-nake-夹克');
INSERT INTO `ast_product` VALUES ('21', '玫瑰花 静谧摄影壁纸3', 'qweewrrew233', '/Public/Uploads/2016-01-12/56946573386f5.jpg', '21-颜色-红色-白色', '', '', '', '', '', '333', '33', '0', '0', '0', '0', '7', '0', 'fdasdfasdfasfasdfasdfasdfsfsdafasdfsadfasdf345d2343c333', '1', '2016011413300001', '0', '0', null);
INSERT INTO `ast_product` VALUES ('25', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK677', '天猫认证 官方正品 假一罚十', '/Public/Uploads/2016-01-15/5698a70edfdf7.jpg', '', '', '', '', '', '', '43', '23', '0', '0', '0', '0', '1', '1', '但是发生的发生地方啊啥都发生短发啊啥都发生啊速度发广东省对方感受到', '1', '2016011504001401', '0', '0', '男装-nake-夹克');

-- ----------------------------
-- Table structure for `ast_productbak`
-- ----------------------------
DROP TABLE IF EXISTS `ast_productbak`;
CREATE TABLE `ast_productbak` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pro_title` varchar(200) NOT NULL COMMENT '标题',
  `pro_subhead` varchar(200) NOT NULL COMMENT '副标题',
  `pro_thumb` varchar(100) NOT NULL COMMENT '缩略图',
  `pro_attribute` varchar(500) NOT NULL COMMENT '属性',
  `pro_photo1` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo2` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo3` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo4` varchar(100) NOT NULL COMMENT '照片',
  `pro_photo5` varchar(100) NOT NULL COMMENT '照片',
  `pro_cost_price` float NOT NULL COMMENT '原价',
  `pro_current_price` float NOT NULL COMMENT '现价',
  `pro_package_price` float NOT NULL COMMENT '组合价格',
  `pro_deal` int(11) NOT NULL COMMENT '交易成功数',
  `pro_stocknum` int(11) NOT NULL COMMENT '库存',
  `pro_discuss` int(11) NOT NULL COMMENT '评论数',
  `pro_classify_id` int(11) NOT NULL COMMENT '分类ID',
  `pro_order` int(11) DEFAULT '1',
  `pro_introduction` varchar(2000) DEFAULT NULL COMMENT '商品详情',
  `pro_isshow` int(4) DEFAULT NULL COMMENT '是否显示',
  `pro_biaoqian` varchar(255) DEFAULT NULL COMMENT '商品标签\r\n',
  `pro_sku` varchar(255) NOT NULL COMMENT '商品编号',
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='产品';

-- ----------------------------
-- Records of ast_productbak
-- ----------------------------
INSERT INTO `ast_productbak` VALUES ('22', '', '', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '颜色-红色-3/尺寸-13-25/重量-23/22-3-4', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '/Public/Admin/images/cry.png', '', '', '', '0', '0', '0', '0', '0', '0', '0', '1', '', '1', null, '');
INSERT INTO `ast_productbak` VALUES ('18', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK677', '天猫认证 官方正品 假一罚十123', '/Public/Uploads/2016-01-14/569734115c824.jpg', '颜色-红色-3/尺寸-13-25/重量-14-25', '/Public/Uploads/2016-01-14/56976f57c4397.jpg', '/Public/Uploads/2016-01-14/56976ff2189c1.jpg', '/Public/Uploads/2016-01-14/569770c864e36.jpg', '/Public/Uploads/2016-01-14/569771cc3b91a.jpg', '/Public/Uploads/2016-01-14/569772971f895.jpg', '43', '23', '0', '0', '0', '0', '1', '1', 'sdfasdfasdfasdf3饿的', '1', null, '');
INSERT INTO `ast_productbak` VALUES ('19', '天猫认证 官方正品 假一罚3', '', '/Public/Uploads/2016-01-11/5693737ae4ef0.jpg', '19-颜色-红色-白色', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '/Public/Uploads/2016-01-14/56978fa783e98.jpg', '/Public/Uploads/2016-01-14/569794978ba74.jpg', '/Public/Uploads/2016-01-14/56979910bf821.jpg', '/Public/Uploads/2016-01-14/5697992f21b6b.jpg', '199', '34', '0', '0', '0', '0', '1', '0', '&lt;div style=&quot;text-align:left;&quot;&gt;\r\n	&lt;span style=&quot;font-size:12px;line-height:1.5;&quot;&gt;dfasdfasdfasfasdfasfdasdfasfdasfasf&lt;/span&gt; \r\n&lt;/div&gt;', '1', null, '');
INSERT INTO `ast_productbak` VALUES ('20', '天猫认证 官方正品 假一罚十', '', '/Public/Uploads/2016-01-11/569373bd0e3e7.jpg', '20-颜色 -白色-红色', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '/Public/Uploads/2016-01-14/56979c30324c2.jpg', '/Public/Uploads/2016-01-14/56979cb911c76.jpg', '/Public/Uploads/2016-01-14/56979ce133a0c.jpg', '', '199', '23', '0', '0', '0', '0', '2', '0', 'dfgsdgsdfgsdfgsdfgsdfgsdffsd', '1', null, '');
INSERT INTO `ast_productbak` VALUES ('21', '玫瑰花 静谧摄影壁纸3', 'qweewrrew233', '/Public/Uploads/2016-01-12/56946573386f5.jpg', '21-颜色-红色-白色', '/Public/Uploads/2016-01-11/56938eeb118ee.jpg', '/Public/Uploads/2016-01-14/56979d1038cee.jpg', '/Public/Uploads/2016-01-14/56979d918bc08.jpg', '/Public/Uploads/2016-01-14/56979da3d6f85.jpg', '/Public/Uploads/2016-01-15/56984db4c3f34.jpg', '333', '33', '0', '0', '0', '0', '3', '0', 'fdasdfasdfasfasdfasdfasdfsfsdafasdfsadfasdf345d2343c333', '1', null, '');
INSERT INTO `ast_productbak` VALUES ('25', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK677', '天猫认证 官方正品 假一罚十', '/Public/Uploads/2016-01-14/569738d95705a.jpg', '', '/Public/Uploads/2016-01-14/56979ea414467.jpg', '/Public/Uploads/2016-01-14/56979f8e40bc6.jpg', '/Public/Uploads/2016-01-14/5697a0033ca0d.jpg', '/Public/Uploads/2016-01-14/5697a123444bf.jpg', '/Public/Uploads/2016-01-15/56984d35dcfc8.jpg', '199', '23', '0', '0', '0', '0', '1', '1', '啥都发生的发生的发啊啥都发生的发生的发啊速度发沙发大师傅啊啥都发生发啊速度发', '1', null, '2016011401574501');
INSERT INTO `ast_productbak` VALUES ('26', '2015冬季新款正品李宁卫衣男装篮球宽松舒适保暖运动卫衣 AWDK99', '天猫认证 官方正品 假一罚3', '/Public/Uploads/2016-01-14/5697393ac4fec.jpg', '', '/Public/Uploads/2016-01-15/569847aa753df.jpg', '/Public/Uploads/2016-01-15/569848f5e9b9d.jpg', '/Public/Uploads/2016-01-15/5698527e98e64.jpg', '', '', '199', '34', '0', '0', '0', '0', '1', '1', '啥都发生的发生的发是风沙大法啥都发生风沙大法按时暗示法发生的发生地方啊阿发按时发暗示法', '1', null, '2016011401592201');

-- ----------------------------
-- Table structure for `ast_seo`
-- ----------------------------
DROP TABLE IF EXISTS `ast_seo`;
CREATE TABLE `ast_seo` (
  `seo_id` int(11) NOT NULL AUTO_INCREMENT,
  `seo_describe` varchar(500) DEFAULT NULL,
  `seo_keyword` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`seo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ast_seo
-- ----------------------------
INSERT INTO `ast_seo` VALUES ('1', '完美商城我', '很好很好');
