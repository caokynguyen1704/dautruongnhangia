<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link rel="stylesheet" type="text/css" href="/dev/main.css" />
<script type="text/javascript" src="/dev/js_lib/jq.js"></script>
<link rel="stylesheet" type="text/css" href="/dev/js_lib/semantic.css" />
<script type="text/javascript" src="/dev/js_lib/semantic.js"></script>
<script type="text/javascript">
/*
$(document).ready(function(){
    $('.ui.form').form({
        username: {
            identifier: 'username',
            rules: [
                {type: 'empty', prompt: '请输入帐号名'}
            ]
        },
        password: {
            identifier: 'password',
            rules: [
                {type: 'empty', prompt: '密码不能为空'},
            ]
        },
        code: {
            identifier: 'code',
            rules: [
                {type: 'empty', prompt: '请输入右边图片中的文字'},
                {type: 'length[4]', prompt: '验证码长度不足'}
            ]
        },
    });
});
 */
</script>
<title>欢迎登录</title>
</head>
<body>
<form method="post" class="ui form" style="margin:20px 0 0 20px; width:520px;">
    <div class="field"><?= $msg ?></div>
    <div class="inline field">
        <label>用户名：</label>
        <div class="ui icon input">
            <input type="text" name="username" value="<?= $username ?>"/>
            <i class="user icon"></i>
        </div>
    </div>
    <div class="inline field">
        <label>密　码：</label>
        <div class="ui icon input">
            <input type="password" name="password" value="<?= $password ?>"/>
            <i class="lock icon"></i>
        </div>
        <input type="hidden" name="submit" value="submit" />
    </div>
    <?php if(App::sess()->need_captcha):?>
    <div class="fields">
        <div class="inline field">
            <label>验证码：</label>
            <input name="code" type="text" style="width:80px;" />
        </div>
        <div class="field">
            <a class="ui rounded image" href='#' onclick="document.getElementById('captcha').src='<?= App::url('/captcha') ?>';"><img id='captcha' src="<?= App::url('/captcha') ?>" /></a>
        </div>
    </div>
    <?php endif?>
    <input type="submit" name="submit" value="登录" class="ui blue submit button" />
</form>
</div>
</body>
</html>
