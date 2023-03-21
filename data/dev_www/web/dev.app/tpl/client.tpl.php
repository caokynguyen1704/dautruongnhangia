<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>客户端管理 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>客户端<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Client').init();
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
<div id="result" style="display:none"></div>
<div id="client_panel" class="ui orange bottom attached active tab segment" data-tab="client">
    <div class="ui menu">
        <div class="ui dropdown item srv_btns pack_zip"><i class="checkered flag icon"></i>更新地图<i class="dropdown icon"></i>
            <div class="menu">
                <a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/gen/map'><i class="checkered flag icon"></i>生成地图</a>
                <a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/gen/city'><i class="checkered flag icon"></i>同步中心城</a>
            </div>
        </div>
        <div class="ui dropdown item srv_btns update_svn"><i class="checkered flag icon"></i>更新客户端<i class="dropdown icon"></i>
            <div class="menu">
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=config&time=60' confirm='确定要更新SVN客户端config版本？'><i class="checkered flag icon"></i>更新客户端CONFIG(1h)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=all&time=60' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端SVN(1h)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=all&time=1440' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端SVN(1d)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=all&time=14400' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端SVN(10d)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=src&time=60' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端代码SVN(1h)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=src&time=1440' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端代码SVN(1d)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=res&time=60' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端资源SVN(1h)</a>
        	<a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/update_tosvn?type=res&time=1440' confirm='确定要更新SVN客户端版本？'><i class="checkered flag icon"></i>更新客户端资源SVN(1d)</a>
            </div>
        </div>
        <a class="item apk_btn" href="javascript:void(0);" url="<?= $this->baseUrl ?>/client/apklist"><i class="checkered flag icon"></i>APK列表</a>
        <a class="item apk_btn" href="javascript:void(0);" url="<?= $this->baseUrl ?>/client/ipalist"><i class="checkered flag icon"></i>IPA列表</a>
        <div class="ui dropdown item srv_btns pack_zip"><i class="checkered flag icon"></i>打热更包<i class="dropdown icon"></i>
            <div class="menu">
                <a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/pack_apk_zip' confirm='确定要生成客户端[APK]版本热更新包？'><i class="checkered flag icon"></i>安卓热更新包</a>
                <a class="item cli_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/client/pack_ipa_zip' confirm='确定要生成客户端[IOS]版本热更新包？'><i class="checkered flag icon"></i>IOS热更新包</a>
            </div>
        </div>
    </div>
    <div class="segment" style="margin:0 0 0.5em 0; padding:0.5em 0 0.5em 0.5em; background:#f6f6f6; border:1px solid #ccc; border-radius:4px;">
        <div id="console" url="<?= $console ?>" style="height:38em; overflow-y: scroll;"></div>
        <div id="apklist" style="height:38em; overflow-y: scroll;display:none"></div>
    </div>
    <div class="ui fluid input">
        <input id="cmd" url="<?= $this->baseUrl ?>/client/cmd" placeholder="输入help获得控制台帮助信息" type="text" style="width:600px"/>
    </div>
<div>
<?php $this->blockEnd('content') ?>
