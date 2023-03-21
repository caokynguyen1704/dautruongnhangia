<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>更新日志 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>更新日志<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Log').init();
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
<div class="ui bottom attached active tab segment" data-tab="log">
    <div id="repo_selector" class="ui selection dropdown select-repo">
        <input type="hidden" id="repo" value="client">
        <div class="default text">选择目标仓库</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <div class="item" data-value="client">客户端日志</div>
            <div class="item" data-value="server">服务端日志</div>
            <div class="item" data-value="web">WEB日志</div>
            <div class="item" data-value="tools">开发工具日志</div>
        </div>
    </div>
    <div class="ui left icon input" style="width:34em"><input type="text" id="keyword" placeholder="输入关键词查找相关日志"><i class="search icon"></i></div>
    <div class="ui selection dropdown select-repo">
        <input type="hidden" id="logs_num" value="15">
        <div class="default text">显示行数</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <div class="item" data-value="15">显示15行</div>
            <div class="item" data-value="30">显示30行</div>
            <div class="item" data-value="100">显示100行</div>
        </div>
    </div>
    <div class="ui button query">显示</div>
    <table class='ui table'>
        <thead>
            <tr><th>日志内容</th><th style="width:12em;">提交时间</th><th style="width:10em;">提交者</th></tr>
        </thead>
        <tbody id="logs"></tbody>
    </table>
</div>
<?php $this->blockEnd('content') ?>

