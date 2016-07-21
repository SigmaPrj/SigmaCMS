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
  `key` VARCHAR(3) NOT NULL DEFAULT '' , -- 城市索引值
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
--                   图片
-- ###########################################
DROP TABLE IF EXISTS `si_image`;
CREATE TABLE IF NOT EXISTS `si_image`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(60) NOT NULL ,
  `fkey` VARCHAR(60) NOT NULL ,
  `hash` VARCHAR(40) NOT NULL ,
  `fsize` INT UNSIGNED NOT NULL ,
  PRIMARY KEY `pk_image` (`id`)
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


-- 登录认证表
DROP TABLE IF EXISTS `si_token`;
CREATE TABLE IF NOT EXISTS `si_token` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `token` VARCHAR(40) NOT NULL ,
  `user_type` TINYINT UNSIGNED NOT NULL ,
  `dead_time` INT UNSIGNED NOT NULL ,
  PRIMARY KEY `pk_token` (`id`)
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
  `username` VARCHAR(60) NOT NULL , -- 用户名
  `password` VARCHAR(32) NOT NULL , -- 密码
  `nickname` VARCHAR(15) NOT NULL ,
  `truename` VARCHAR(6) NOT NULL DEFAULT '', -- 真实姓名
  `bio` TEXT NOT NULL DEFAULT '', -- 个人简历地址
  `is_approved` TINYINT UNSIGNED NOT NULL DEFAULT 0, -- 账户是否被认证
  -- 1 表示邮箱注册   2 表示电话注册   3 表示自定义类型账号
  `username_type` ENUM('email', 'phone', 'customer'), -- 账号类型
  `email` VARCHAR(60) , -- 用户邮箱
  `phone` VARCHAR(15) , -- 用户电话
  `image` TEXT , -- 用户头像
  `bgImage` TEXT , -- 用户背景图片
  `signature` VARCHAR(60) , -- 用户签名
  `signatureImage` TEXT , -- 用户签名背景图片
  -- 1次签到, 1积分 被赞 积分+1 分享积分+10 问答积分为别人买的数目
  `point` INT UNSIGNED NOT NULL DEFAULT 0, -- 用户签到积分
  `coin` INT UNSIGNED NOT NULL DEFAULT 0, -- 金币个数  1块钱100金币
  `user_level` INT UNSIGNED NOT NULL DEFAULT 0 , -- 用户等级
  `school_code` SMALLINT UNSIGNED , -- 学校id
  `city_code` SMALLINT UNSIGNED , -- 所属城市
  `user_type` TINYINT UNSIGNED , -- 用户类型
  `user_social` INT UNSIGNED , -- 用户社交账号绑定信息
  `user_privilege` INT UNSIGNED NOT NULL ,
  `last_login_city` SMALLINT UNSIGNED , -- 上次登录城市
  `last_login_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 上次登录时间
  `last_register_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 上次签到时间
  `is_active` TINYINT UNSIGNED NOT NULL , -- 用户是否已经被激活
  `active_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户被激活的时间
  `apply_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户 发送邮箱, 短信验证的时间 有效时间为30分钟
  `apply_code` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户 收到的邮箱验证码或者短信验证码 md5 加密值
  PRIMARY KEY `pk_user` (`id`) ,
  FOREIGN KEY `fk_user_school` (`school_code`) REFERENCES `si_school` (`code`) ,
  FOREIGN KEY `fk_user_city` (`city_code`) REFERENCES `si_city` (`code`) ,
  FOREIGN KEY `fk_user_userType` (`user_type`) REFERENCES `si_user_type` (`code`) ,
  FOREIGN KEY `fk_user_userSocial` (`user_social`) REFERENCES `si_user_social` (`id`) ,
  FOREIGN KEY `fk_user_userPrivilege` (`user_privilege`) REFERENCES `si_user_privilege` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 用户权限设置
-- 权限都分为3级, 0 所有人不可见 1 朋友可见 2 任何人都可见
DROP TABLE IF EXISTS `si_user_privilege`;
CREATE TABLE IF NOT EXISTS `si_user_privilege` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `friend_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 1, -- 是否可以允许别人查看自己的朋友列表
  `follow_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 2, -- 是否可以允许本人查看自己follow的用户列表
  `sex_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 2, -- 是否可以允许别人查看到自己性别
  `name_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 1, -- 是否允许别人查看自己的真实姓名
  `phone_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 1, -- 是否允许别人查看到自己的电话号码
  `email_visibility` TINYINT UNSIGNED NOT NULL DEFAULT 1, -- 是否允许别人查看到自己的邮箱地址
  PRIMARY KEY `pk_userPrivilege` (`id`)
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
  `s_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL, -- 投放广告时间
  `e_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL, -- 广告投放结束时间
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
  `title` VARCHAR(60) NOT NULL , -- 问题标题
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
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 问答发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
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
  `image` TEXT , -- 课程分类图标ad
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
  `url` TEXT , -- 课程视频连接地址
  `category` SMALLINT UNSIGNED NOT NULL , -- 课程所属分类
  `learn` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人正在学习
  `save` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人收藏
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 课程发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  PRIMARY KEY `pk_video` (`id`) ,
  FOREIGN KEY `fk_video_category` (`category`) REFERENCES `si_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 课程评论

DROP TABLE IF EXISTS `si_video_comment`;
CREATE TABLE IF NOT EXISTS `si_video_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `video_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `comment` TEXT ,
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 评论发布时间
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 评论被赞数目
  `sub_id` INT UNSIGNED, -- 被回复的评论id
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
  `description` TEXT , -- 资源详细描述
  `resource_type` VARCHAR(10) , -- 视频, PDF, 空表示不清楚
  `url` TEXT , -- 下载地址
  `user_id` INT UNSIGNED NOT NULL , -- 资源分享的人
  `category_id` SMALLINT UNSIGNED NOT NULL , -- 资源所属分类
  `save` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人收藏
  `look` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人浏览
  `download` INT UNSIGNED NOT NULL DEFAULT 0 , -- 多少人下载
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  PRIMARY KEY `pk_resource` (`id`) ,
  FOREIGN KEY `fk_resource_user` (`user_id`) REFERENCES `si_user` (`id`) ,
  FOREIGN KEY `fk_resource_category` (`category_id`) REFERENCES `si_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 资源评论
DROP TABLE IF EXISTS `si_resource_comment`;
CREATE TABLE IF NOT EXISTS `si_resource_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `resource_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `comment` TEXT ,
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 评论发布时间
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 评论被赞数目
  `sub_id` INT UNSIGNED, -- 被回复的评论id
  PRIMARY KEY `pk_resourceComment` (`id`) ,
  FOREIGN KEY `fk_resourceComment_resource` (`resource_id`) REFERENCES `si_resource` (`id`) ,
  FOREIGN KEY `fk_resourceComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ###########################################
--                  活动功能
-- ###########################################

-- 企业账号,代理点账号以及其他非用户账号
DROP TABLE IF EXISTS `si_ouser`;
CREATE TABLE IF NOT EXISTS `si_ouser` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(60) NOT NULL , -- 用户名
  `password` VARCHAR(32) NOT NULL , -- 密码
  `nickname` VARCHAR(15) NOT NULL ,
  -- 1 表示邮箱注册   2 表示电话注册   3 表示自定义类型账号
  `username_type` ENUM('email', 'phone', 'customer'), -- 账号类型
  `email` VARCHAR(60) , -- 用户邮箱
  `phone` VARCHAR(15) , -- 用户电话
  `image` TEXT , -- 用户头像
  `is_approved` TINYINT UNSIGNED NOT NULL DEFAULT 0, -- 是否被认证
  `city_code` SMALLINT UNSIGNED , -- 所属城市
  `user_type` TINYINT UNSIGNED , -- 用户类型
  `last_login_city` SMALLINT UNSIGNED , -- 上次登录城市
  `last_login_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 上次登录时间
  `last_register_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 上次签到时间
  `is_active` TINYINT UNSIGNED NOT NULL , -- 用户是否已经被激活
  `active_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户被激活的时间
  `apply_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户 发送邮箱, 短信验证的时间 有效时间为30分钟
  `apply_code` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 用户账户 收到的邮箱验证码或者短信验证码 md5 加密值
  PRIMARY KEY `pk_ouser` (`id`),
  FOREIGN KEY `fk_ouser_live_city` (`city_code`) REFERENCES `si_city` (`code`),
  FOREIGN KEY `fk_ouser_userType` (`user_type`) REFERENCES `si_user_type` (`code`),
  FOREIGN KEY `fk_ouser_last_city` (`last_login_city`) REFERENCES `si_city` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 活动

DROP TABLE IF EXISTS `si_activity`;
CREATE TABLE IF NOT EXISTS `si_activity` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ouser_id` INT UNSIGNED NOT NULL ,
  `title` VARCHAR(60) NOT NULL , -- 活动标题
  `description` TEXT, -- 活动详细描述
  `address` VARCHAR(255) NOT NULL , -- 活动地点
  `image` TEXT , -- 活动宣传图
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `s_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动开始时间
  `e_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动结束时间
  `allow_personal` TINYINT UNSIGNED NOT NULL DEFAULT 1 , -- 是否允许个人参加
  `allow_team` TINYINT UNSIGNED NOT NULL DEFAULT 1 , -- 是否允许主队参加
  `allow_teacher` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 是否需要导师
  `team_min_number` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 主队允许最少人数
  `team_max_number` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 主队``允许最多人数
  `save` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人收藏
  `look` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人查阅
  `join` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人参加
  PRIMARY KEY `pk_activity` (`id`) ,
  FOREIGN KEY `fk_activity_user` (`ouser_id`) REFERENCES `si_ouser` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 活动评论
DROP TABLE IF EXISTS `si_activity_comment`;
CREATE TABLE IF NOT EXISTS `si_activity_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activity_id` INT UNSIGNED NOT NULL , -- 评论的哪条资讯
  `user_id` INT UNSIGNED NOT NULL , -- 发布评论人
  `comment` TEXT , -- 评论内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 评论发布时间
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 评论被赞数目
  `sub_id` INT UNSIGNED, -- 被回复的评论id
  PRIMARY KEY `pk_activityComment` (`id`) ,
  FOREIGN KEY `fk_activityComment_activity` (`activity_id`) REFERENCES `si_activity` (`id`) ,
  FOREIGN KEY `fk_activityComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                 社区功能
-- ###########################################

-- ------------------------------------------
-- 经验分享
-- ------------------------------------------
DROP TABLE IF EXISTS `si_experience`;
CREATE TABLE IF NOT EXISTS `si_experience` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL , -- 发布分享的用户
  `title` VARCHAR(25) NOT NULL , -- 分享文章标题
  `content` TEXT , -- 分享文章内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `save` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人收藏
  `look` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人查阅
  `praise` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人称赞
  PRIMARY KEY `pk_experience` (`id`) ,
  FOREIGN KEY `fk_experience_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 经验分享评论

DROP TABLE IF EXISTS `si_experience_comment`;
CREATE TABLE IF NOT EXISTS `si_experience_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `experience_id` INT UNSIGNED NOT NULL , -- 评论的哪条资讯
  `user_id` INT UNSIGNED NOT NULL , -- 发布评论人
  `comment` TEXT , -- 评论内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 评论被赞数目
  `sub_id` INT UNSIGNED , -- 被回复的评论id
  PRIMARY KEY `pk_experienceComment` (`id`) ,
  FOREIGN KEY `fk_experienceComment_experience` (`experience_id`) REFERENCES `si_experience` (`id`) ,
  FOREIGN KEY `fk_experienceComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ------------------------------------------
-- 话题
-- ------------------------------------------
DROP TABLE IF EXISTS `si_topic`;
CREATE TABLE IF NOT EXISTS `si_topic` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL , -- 话题内容
  `dynamic_num` INT UNSIGNED NOT NULL DEFAULT 0 , -- 该话题当前动态数目
  PRIMARY KEY `pk_topic` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ------------------------------------------
-- 动态
-- ------------------------------------------
DROP TABLE IF EXISTS `si_dynamic`;
CREATE TABLE IF NOT EXISTS `si_dynamic` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL , -- 发布用户
  `has_topic` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 是否有话题
  `topic_id` INT UNSIGNED , -- 话题id
  `content` TEXT , -- 评论内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `share` INT UNSIGNED , -- 分享人数
  `look` INT UNSIGNED , -- 浏览人数
  `praise` INT UNSIGNED , -- 称赞人数
  PRIMARY KEY `pk_dynamic` (`id`) ,
  FOREIGN KEY `fk_dynamic_user` (`user_id`) REFERENCES `si_user` (`id`) ,
  FOREIGN KEY `fk_dynamic_topic` (`topic_id`) REFERENCES `si_topic` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 动态的图片
DROP TABLE IF EXISTS `si_dynamic_image`;
CREATE TABLE IF NOT EXISTS `si_dynamic_image` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` TEXT,
  `dynamic_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY `pk_dynamicImage` (`id`) ,
  FOREIGN KEY `fk_dynamicImage_dynamic` (`dynamic_id`) REFERENCES `si_dynamic` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 动态评论
DROP TABLE IF EXISTS `si_dynamic_comment`;
CREATE TABLE IF NOT EXISTS `si_dynamic_comment`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dynamic_id` INT UNSIGNED NOT NULL , -- 评论的哪条资讯
  `user_id` INT UNSIGNED NOT NULL , -- 发布评论人
  `comment` TEXT , -- 评论内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 活动发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `praise` INT UNSIGNED , -- 评论被赞数目
  `sub_id` INT UNSIGNED , -- 被回复的评论id
  PRIMARY KEY `pk_dynamicComment` (`id`) ,
  FOREIGN KEY `fk_dynamicComment_dynamic` (`dynamic_id`) REFERENCES `si_dynamic` (`id`) ,
  FOREIGN KEY `fk_dynamicComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                 资讯功能
-- ###########################################

-- 资讯分类
DROP TABLE IF EXISTS `si_news_type`;
CREATE TABLE IF NOT EXISTS `si_news_type` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(4) NOT NULL , -- 资讯分类名称
  `image` TEXT , -- 分类图标
  PRIMARY KEY `pk_newsType` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 资讯
DROP TABLE IF EXISTS `si_news`;
CREATE TABLE IF NOT EXISTS `si_news` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(60) NOT NULL , -- 资讯标题
  `description` TEXT , -- 资讯详细描述
  `image` TEXT , -- 图片地址
  `news_type` SMALLINT UNSIGNED  NOT NULL ,
  `publish_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 资讯发布时间
  `last_look_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 最近一次浏览时间
  `s_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 资讯比赛开始时间
  `e_date` int(10) unsigned DEFAULT 0 NOT NULL , -- 资讯比赛结束时间
  `allow_personal` TINYINT UNSIGNED NOT NULL DEFAULT 1 , -- 是否允许个人参加
  `allow_team` TINYINT UNSIGNED NOT NULL DEFAULT 1 , -- 是否允许主队参加
  `allow_teacher` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 是否需要导师
  `team_min_number` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 主队允许最少人数
  `team_max_number` TINYINT UNSIGNED NOT NULL DEFAULT 0 , -- 主队允许最多人数
  `save` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人收藏
  `look` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人查阅
  `join` INT UNSIGNED NOT NULL DEFAULT 0, -- 多少个人参加
  PRIMARY KEY `pk_news` (`id`) ,
  FOREIGN KEY `fk_news_newsType` (`news_type`) REFERENCES `si_news_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 资讯评论
DROP TABLE IF EXISTS `si_news_comment`;
CREATE TABLE IF NOT EXISTS `si_news_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `news_id` INT UNSIGNED NOT NULL , -- 评论的哪条资讯
  `user_id` INT UNSIGNED NOT NULL , -- 发布评论人
  `comment` TEXT , -- 评论内容
  `publish_date` INT(10) UNSIGNED DEFAULT 0 NOT NULL , -- 评论发布时间
  `praise` INT UNSIGNED NOT NULL DEFAULT 0 , -- 评论被赞数目
  `sub_id` INT UNSIGNED NOT NULL , -- 被回复的评论id
  PRIMARY KEY `pk_newsComment` (`id`) ,
  FOREIGN KEY `fk_newsComment_news` (`news_id`) REFERENCES `si_news` (`id`) ,
  FOREIGN KEY `fk_newsComment_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ###########################################
--                  关注/粉丝 follow
-- ###########################################

DROP TABLE IF EXISTS `si_follow`;
CREATE TABLE IF NOT EXISTS `si_follow` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `follow_user_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY `pk_follow` (`id`) ,
  FOREIGN KEY `fk_follow_user` (`user_id`) REFERENCES `si_user` (`id`) ,
  FOREIGN KEY `fk_follow_fuser` (`follow_user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                    收藏功能
-- ###########################################

-- TODO : 收藏功能表单


-- ###########################################
--                    朋友
-- ###########################################

DROP TABLE IF EXISTS `si_friend`;
CREATE TABLE IF NOT EXISTS `si_friend` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `friend_user_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY `pk_friend` (`id`) ,
  FOREIGN KEY `fk_friend_user` (`user_id`) REFERENCES `si_user` (`id`) ,
  FOREIGN KEY `fk_friend_fuser` (`friend_user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ###########################################
--                    队伍
-- ###########################################

DROP TABLE IF EXISTS `si_team`;
CREATE TABLE IF NOT EXISTS `si_team`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(30) NOT NULL , -- 队名
  `member_num` TINYINT UNSIGNED NOT NULL , -- 队伍人数
  PRIMARY KEY `pk_team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `si_team_member`;
CREATE TABLE IF NOT EXISTS `si_team_member`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `is_leader` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_teacher` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY `pk_teamMember` (`id`) ,
  FOREIGN KEY `fk_teamMember_team` (`team_id`) REFERENCES `si_team` (`id`) ,
  FOREIGN KEY `fk_teamMember_user` (`user_id`) REFERENCES `si_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 修改
alter table `si_user` add column `truename` VARCHAR(6) NOT NULL DEFAULT '' after `nickname`;
alter table `si_user` add column `bio` TEXT NOT NULL DEFAULT '' after `truename`;
alter table `si_user` add column `user_privilege` INT UNSIGNED NOT NULL after `user_social`;