<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<script type="text/javascript" src="/dev/js_lib/jq.js"></script>
<script type="text/javascript" src="/dev/js_lib/jq.mousewheel.js"></script>
<script type="text/javascript" src="/dev/js/App.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    App.baseUrl = '/dev';
    App.require('Main').start();
    <?= $this->block('script') ?>
});
</script>
<link rel="stylesheet" type="text/css" href="/dev/js_lib/semantic.css" />
<script type="text/javascript" src="/dev/js_lib/semantic.js"></script>
<style type="text/css">
.dp50{width:50%; float:left; display: inline; *margin-right:-1px; *padding-right:1px; _margin-right:-1px; _padding-right:1px; height:100px;}
.dp33{width:33.3%; float:left; display: inline; *margin-right:-1px; *padding-right:1px; _margin-right:-1px; _padding-right:1px;  height:100px;}
.g{color:#3a3;}
.r{color:#a33;}
.o{color:#f60;}
.container{margin:0 auto; width:1048px;}
body{font-size:1em; font-family:Verdana,Arial,Helvetica,sans-serif;}
.nav{font-size:1.125em; font-weight:bold; height:3.25em; padding:1em 0 0 0; margin:0 0 1em 0; background:#000; color:#fff;}
.nav .button{float:right; margin:-0.7em 0 0 0;}
.nav i{color:#f60;}
.table tbody tr:hover{background:#eeeef6;}
.table tbody tr td:first-child:hover{cursor:pointer;}
#main_menu a{line-height:2em;}
.a_list a{cursor:pointer; border-right: #f96 1px solid; padding:2px 6px; font-weight:bold;}
.a_list a:hover{color:orange;}
.findt{color:red; background:#fda;}
.find{background:#fda;}
</style>
<title><?= $this->block('title') ?></title>
</head>
<body id="main">
<div class="nav">
    <div class="container">
        <i class="user icon"></i>欢迎你，<?= App::sess()->username ?> 
	<a href="<?= App::url('/logout') ?>" class="ui blue button">退出</a>
    </div>
</div>
<div class="container">
    <div id="main_menu" class="ui attached tabular menu" hl="<?php $this->block('hl')?>">
        <a class="red item" href="<?= $this->baseUrl ?>/release" data-tab="release"><i class="circular download icon"></i>版本浏览</a>
        <a class="green item" href="<?= $this->baseUrl ?>/log" data-tab="log"><i class="circular git icon"></i>更新日志</a>
        <a class="yellow item" href="<?= $this->baseUrl ?>/client" data-tab="client"><i class="circular rocket icon"></i>客户端</a>
        <a class="orange item" href="<?= $this->baseUrl ?>/server" data-tab="server"><i class="circular database icon"></i>服务器</a>
        <a class="blue item" href="<?= $this->baseUrl ?>/diffdata" data-tab="diffdata"><i class="circular database icon"></i>数据比对</a>
        <!--<a class="blue item" href="<?= $this->baseUrl ?>/makedata" data-tab="makedata"><i class="circular database icon"></i>数据更新</a>-->
        <!-- <a class="item" href="/dev/editor" target="_blank"><i class="circular edit icon"></i>地图编辑</a> -->
        <a class="item" href="http://192.168.1.43/lab" target='_blank'><i class="circular keyboard icon"></i>活动测试服</a>
	<div class="ui dropdown item lang_list"><i class="checkered flag icon"></i>多语言<i class="dropdown icon"></i>
            <div class="menu">
                <a class="item lang_btns" target='_blank' href='http://sszg-gy-dev.shiyuegame.com/dev/index.php/server?username=<?=App::sess()->username ?>&type=<?=App::sess()->group ?>&project_id=<?=App::sess()->project_id ?>&sign=<?=App::sess()->sign ?>'>北美</a>
            </div>
        </div>
    </div>
<?php $this->block('content') ?>
</div>
<i class="large circle arrow up icon goto_top" title="返回顶部" style="z-index:1000; cursor:pointer; position:fixed; right:20px; bottom:60px;"></i>
<i class="large circle arrow down icon goto_bottom" title="返回底部" style="z-index:1000; cursor:pointer; position:fixed; right:20px; bottom:30px;"></i>
</body>
</html>
