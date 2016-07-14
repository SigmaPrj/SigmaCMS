# 简介

该项目为采用`CodeIgniter`PHP框架,实现的后台服务。

提供权限管理和RESTful接口, 以及用户认证。



## 任务进度

- [x] 数据库表创建 (90%)
- [] 首页API接口
- [] 问答页API接口
- [] 课程页API接口
- [] 资源API接口
- [] 活动API接口
- [] 社区API接口
- [] 发现API接口
- [] 资讯API接口
- [] 我的API接口



## 使用

### 获取源码
```shell

$ git clone git@github.com:SigmaPrj/SigmaCMS.git 

$ cd ./SigmaCMS
```

### 配置环境

修改`hosts文件`, 添加

`127.0.0.1          sigma.test.com`

启动Apache和Mysql

打开Mysql

`$ mysql -u root -p`

执行根目录下得`db.sql`文件, 创建sql结构
