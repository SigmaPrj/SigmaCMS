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

