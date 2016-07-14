-- 数据库如果存在,删除
DROP DATABASE IF EXISTS `sigma`;

-- 创建数据库,如果不存在
CREATE DATABASE IF NOT EXISTS `sigma`;

-- 切换数据库
USE `sigma`;


-- -------------------------------------------------------------------------

--                        第三方库使用的表

-- -------------------------------------------------------------------------

-- 创建api访问表
DROP TABLE IF EXISTS `si_keys`;
CREATE TABLE `si_keys` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(40) NOT NULL,
  `level` INT(2) NOT NULL,
  `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
  `date_created` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 创建session表
DROP TABLE IF EXISTS `si_sessions`;
CREATE TABLE IF NOT EXISTS `si_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -------------------------------------------------------------------------

--                        RBAC使用的表

-- -------------------------------------------------------------------------

-- 创建权限表
DROP TABLE IF EXISTS `si_privilege`;
CREATE TABLE IF NOT EXISTS `si_privilege` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL , -- 权限名称
  `parent_id` VARCHAR(45) NOT NULL , -- 父权限id
  PRIMARY KEY `pk_privilege` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 创建role表
DROP TABLE IF EXISTS `si_role`;
CREATE TABLE IF NOT EXISTS `si_role` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL, -- 角色名称
  `privileges` VARCHAR(255) NOT NULL, -- 权限值
  PRIMARY KEY `pk_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 创建admin表
DROP TABLE IF EXISTS `si_admin`;
CREATE TABLE IF NOT EXISTS `si_admin` (
  `id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `role_id` INT NOT NULL, -- 角色id
  PRIMARY KEY `pk_admin` (`id`),
  FOREIGN KEY `fk_admin_role` (`role_id`) REFERENCES `si_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -------------------------------------------------------------------------

--                        核心业务逻辑表

-- -------------------------------------------------------------------------

-- ###########################################
--                   城市
-- ###########################################

DROP TABLE IF EXISTS `si_city`;
CREATE TABLE IF NOT EXISTS `si_city` (
  `code` SMALLINT UNSIGNED NOT NULL , -- 城市代码
  `name` VARCHAR(45) NOT NULL DEFAULT '' , -- 城市名称
  PRIMARY KEY `pk_city` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                   学校
-- ###########################################

DROP TABLE IF EXISTS `si_school`;
CREATE TABLE IF NOT EXISTS `si_school` (
  `code` SMALLINT UNSIGNED NOT NULL , -- 学校代码
  `name` VARCHAR(45) NOT NULL DEFAULT '' , -- 学校名称
  `city_code` SMALLINT UNSIGNED NOT NULL , -- 城市id
  PRIMARY KEY `pk_school` (`code`) ,
  FOREIGN KEY `fk_school_city` (`city_code`) REFERENCES `si_city` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                   用户
-- ###########################################


-- 用户类型, 确定是学生还是老师,还是活动举办方等。
DROP TABLE IF EXISTS `si_user_type`;
CREATE TABLE IF NOT EXISTS `si_user_type` (
  `code` TINYINT UNSIGNED NOT NULL , -- 用户类型编码
  `name` VARCHAR(45) NOT NULL , -- 用户类型名称
  PRIMARY KEY `pk_userType` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 用户社交平台关联信息 老师和学生才会有
DROP TABLE IF EXISTS `si_user_social`;
CREATE TABLE IF NOT EXISTS `si_user_social` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `qq` VARCHAR(20) , -- 用户QQ号
  `is_qq` TINYINT UNSIGNED NOT NULL , -- 用户是否绑定QQ
  `wechat` VARCHAR(30) , -- 用户微信号
  `is_wechat` TINYINT UNSIGNED NOT NULL , -- 用户是否绑定微信
  `weibo` VARCHAR(60) , -- 用户微博号
  `is_weibo` TINYINT UNSIGNED NOT NULL , -- 用户是否绑定微博
  PRIMARY KEY `pk_userSocial` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 用户注册相关基本信息
DROP TABLE IF EXISTS `si_user`;
CREATE TABLE IF NOT EXISTS `si_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL , -- 用户名
  `password` VARCHAR(32) NOT NULL , -- 密码
  -- 1 表示邮箱注册   2 表示电话注册   3 表示自定义类型账号
  `username_type` ENUM('email', 'phone', 'customer'), -- 账号类型
  `email` VARCHAR(255) , -- 用户邮箱
  `phone` VARCHAR(15) , -- 用户电话
  `image` TEXT , -- 用户头像
  `signature` VARCHAR(255) , -- 用户签名
  -- 1次签到, 1积分 被赞 积分+1 分享积分+10 问答积分为别人买的数目
  `point` INT UNSIGNED NOT NULL DEFAULT 0, -- 用户签到积分
  `coin` INT UNSIGNED NOT NULL DEFAULT 0, -- 金币个数  1块钱100金币
  `user_level` INT UNSIGNED NOT NULL DEFAULT 0 , -- 用户等级
  `school_code` INT UNSIGNED , -- 学校id
  `city_code` SMALLINT UNSIGNED , -- 所属城市
  `user_type` TINYINT UNSIGNED , -- 用户类型
  `user_social` INT UNSIGNED , -- 用户社交账号绑定信息
  `last_login_city` SMALLINT UNSIGNED , -- 上次登录城市
  `last_login_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 上次登录时间
  `last_register_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 上次签到时间
  `is_active` TINYINT UNSIGNED NOT NULL , -- 用户是否已经被激活
  `active_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 用户账户被激活的时间
  `apply_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 用户账户 发送邮箱, 短信验证的时间 有效时间为30分钟
  `apply_code` int(10) unsigned DEFAULT 0 NOT NULL , -- 用户账户 收到的邮箱验证码或者短信验证码 md5 加密值
  PRIMARY KEY `pk_user` (`id`) ,
  FOREIGN KEY `fk_user_school` (`school_code`) REFERENCES `si_school` (`code`) ,
  FOREIGN KEY `fk_user_city` (`city_code`) REFERENCES `si_city` (`code`) ,
  FOREIGN KEY `fk_user_userType` (`user_type`) REFERENCES `si_user_type` (`code`) ,
  FOREIGN KEY `fk_user_userSocial` (`user_social`) REFERENCES `si_user_social` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ###########################################
--                首页轮播图
-- ###########################################

-- 获取最近n条广告
-- 获取所有广告
-- 获取广告
-- 添加广告

-- 首页轮播图 / 广告
DROP TABLE IF EXISTS `si_advertisement`;
CREATE TABLE IF NOT EXISTS `si_advertisement` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` TEXT , -- 广告图片地址
  `level` INT UNSIGNED NOT NULL , -- 广告优先级
  `s_date` int(10) unsigned DEFAULT 0 NOT NULL, -- 投放广告时间
  `e_date` int(10) unsigned DEFAULT 0 NOT NULL, -- 广告投放结束时间
  PRIMARY KEY `pk_advertisement` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--              首页问答 及 问答页面
-- ###########################################

-- 获取最热门n条问答
-- 获取最新n条问答
-- 获取所有问答
-- 获取所有免费问答
-- 获取所有付积分问答
-- 获取所有付金币回答
-- 添加问答
-- 删除问答
-- 修改问答

DROP TABLE IF EXISTS `si_question`;
CREATE TABLE IF NOT EXISTS `si_question` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL , -- 问题标题
  `user_id` INT UNSIGNED NOT NULL , -- 回答问题的用户id
  `url` TEXT , -- 回答的音频资源地址
  `duration` SMALLINT UNSIGNED NOT NULL DEFAULT 0 , -- 音频持续时间
  -- 1 用积分 2 用金币
  `pay_type` TINYINT NOT NULL , -- 收听付费回答的支付类型
  `pay_num` SMALLINT UNSIGNED NOT NULL DEFAULT 0 , -- 收听回答的费用
  `is_free` TINYINT UNSIGNED NOT NULL  DEFAULT 1 , -- 是否免费 , 默认免费
  `look` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人听过
  `save` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人收藏
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人赞过
  `publish_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 问答发布时间
  `last_look_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  PRIMARY KEY `pk_question` (`id`) ,
  FOREIGN KEY `fk_question_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                  课程及资源分类
-- ###########################################

-- 课程/资源 分类
DROP TABLE IF EXISTS `si_category`;
CREATE TABLE IF NOT EXISTS `si_category` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL , -- 课程分类名称
  `parent_id` SMALLINT UNSIGNED NOT NULL , -- 父级分类
  PRIMARY KEY `pk_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ###########################################
--                  课程功能
-- ###########################################

-- 课程
-- 获取特定分类下面的所有视频

DROP TABLE IF EXISTS `si_video`;
CREATE TABLE IF NOT EXISTS `si_video` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(60) NOT NULL , -- 课程名称
  `description` TEXT , -- 课程详细描述
  `image` TEXT , -- 课程图片地址
  `category` SMALLINT UNSIGNED NOT NULL , -- 课程所属分类
  `learn` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人正在学习
  `save` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人收藏
  `publish_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 课程发布时间
  `last_look_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  PRIMARY KEY `pk_video` (`id`) ,
  FOREIGN KEY `fk_video_category` (`category`) REFERENCES `si_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 课程 章节内容
DROP TABLE IF EXISTS `si_video_item`;
CREATE TABLE IF NOT EXISTS `si_video_item` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `url` TEXT ,
  `video_id` INT UNSIGNED NOT NULL , -- 所属视频标题
  `parent_id` INT UNSIGNED NOT NULL , -- 父标题, 为0表示没有父标题
  PRIMARY KEY `pk_videoItem` (`id`) ,
  FOREIGN KEY `fk_videoItem_video` (`video_id`) REFERENCES `si_video` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 课程评论

DROP TABLE IF EXISTS `si_video_comment`;
CREATE TABLE IF NOT EXISTS `si_video_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `video_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `comment` TEXT ,
  `publish_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 评论发布时间
  `sub_id` INT UNSIGNED NOT NULL , -- 被回复的评论id
  PRIMARY KEY `pk_videoComment` (`id`) ,
  FOREIGN KEY `fk_videoComment_video` (`video_id`) REFERENCES `si_video` (`id`) ,
  FOREIGN KEY `fk_videoComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ###########################################
--                    资源功能
-- ###########################################

-- 资源
DROP TABLE IF EXISTS `si_resource`;
CREATE TABLE IF NOT EXISTS `si_resource` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(60) NOT NULL , -- 资源标题
  `description` TEXT NOT NULL , -- 资源详细描述
  `resource_type` VARCHAR(10) , -- 视频, PDF, 空表示不清楚
  `url` TEXT , -- 下载地址
  `user_id` INT UNSIGNED NOT NULL , -- 资源分享的人
  `save` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人收藏
  `look` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人浏览
  `download` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人下载
  `publish_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 发布时间
  PRIMARY KEY `pk_resource` (`id`) ,
  FOREIGN KEY `fk_resource_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 资源评论
DROP TABLE IF EXISTS `si_resource_comment`;
CREATE TABLE IF NOT EXISTS `si_resource_comment` (

) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ###########################################
--                    收藏功能
-- ###########################################