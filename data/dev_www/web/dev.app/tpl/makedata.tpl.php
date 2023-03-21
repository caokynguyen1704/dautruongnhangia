<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>数据更新 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>数据更新<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Util');
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
    <div class="ui three column grid" style=" border:1px solid #ccc; border-radius:4px;margin-top:5px; max-height:520px; overflow:auto;">
    <?php foreach($files as $file): ?>
    <div class="column">
       <label><?=$file['name']?></label>
       <a href="<?=$this->baseUrl?>/makedata/gen_file?file=<?=$file['name']?>" target="_blank">生成热更数据</a>
    </div>
    <?php endforeach; ?>
   </div> 
<?php $this->blockEnd('content') ?>
