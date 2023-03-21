<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>查看日志 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>查看日志<?php $this->blockEnd('hl')?>

<?php $this->blockStart('content') ?>
<div class="container">
    <div class="ui bottom attached active tab segment" data-tab="release">
        <table width="100%" class='files'>
            <tr><th>日志内容</th><th style="width:120px;">提交时间</th><th>提交者</th></tr>
            <?php foreach($logs as $log):?>
            <tr><td><a href="<?= $log['url'] ?>"><?= $log['msg'] ?></a></td><td class="r"><?= $log['date'] ?></td><td class="r"><?= $log['author'] ?></td></tr>
            <?php endforeach?>
        </table>
    </div>
</div>
<?php $this->blockEnd('content') ?>
