<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>数模 - 登录</title>
    <link rel="stylesheet" href="/Public/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/Public/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/Public/css/login.css">
</head>
<body>

<div id="formbackground" style="position:absolute; width:100%; height:100%; z-index:-1">
    <img src="/Public/imgs/login-bk.jpg" height="100%" width="100%"/>
</div>
<div class="mask"></div>

<div class="model">
    <h3>用户登录</h3>
    <?php echo form_open('Login/login'); ?>
        <input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" placeholder="请输入用户名/邮箱"/>
        <input type="password" name="password" value="<?php echo set_value('password'); ?>" size="50" placeholder="请输出密码"/>
        <?php echo validation_errors(); ?>
        <input type="submit" value="登录" />
    </form>
</div>


<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
</body>
</html>