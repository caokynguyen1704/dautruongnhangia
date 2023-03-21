<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>版本浏览 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>版本浏览<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Release').init();
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
<div class="ui bottom attached active tab segment" data-tab="release">
    <div class="ui segment">
        <div class="ui top attached large label">版本更新日志(当前版本<?= $select_version ?>)</div>
        <div class='version-diff'></div>
    </div>
</div>
<?php $this->blockEnd('content') ?>
