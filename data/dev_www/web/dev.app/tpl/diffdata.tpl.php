<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>数据比对 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>数据比对<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Util');
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
<div class="ui menu">
 <a class="item apk_btn" href="<?= $this->baseUrl ?>/diffdata"><i class="checkered flag icon"></i>服务端</a>
 <a class="item apk_btn" href="<?= $this->baseUrl ?>/diffdata/clifile"><i class="checkered flag icon"></i>客户端</a>
 <p class="item"><span class='r'>开发服(develop)与当前线上服(master)文件比对, 当前仅显示有变化的文件</span></p>
</div> 
    <div class="ui three column grid" style=" border:1px solid #ccc; border-radius:4px;margin-top:5px; max-height:720px; height:500px; overflow:auto;">
    <?php foreach($files as $file): ?>
    <div class="column" style="max-height:30px" title="文件最后修改时间：<?= $file['mtime']?>&#10;当前文件大小：<?= $file['size'] ?> kb">
       <label><?=$file['name']?>.<?=$file['type']?></label>
       <a href="<?=$this->baseUrl?>/diffdata/<?=$difftype?>?file=<?=$file['name']?>" target="_blank">比对</a>
    </div>
    <?php endforeach; ?>
   </div> 
<?php $this->blockEnd('content') ?>
