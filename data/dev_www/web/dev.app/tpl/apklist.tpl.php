    <div class="ui one column grid" style="padding:10px 20px; max-height:520px; width:1000px;">
    <?php foreach($files as $key => $file): ?>
    <div class="column" style="margin:0; padding:3px 0;">
       <label><?=$key?> 生成时间:<?= $file['mtime']?> 大小:<?= $file['size']?>MB</label>
       <a href="http://cdn.demo.sszg.shiyuegame.com/dev/<?=$ftype?>s/<?=$file['name']?>.<?=$ftype?>" target="_blank">下载<?=$ftype?></a>
       <a href="http://pan.123gm.top:81/share/qrcode?w=300&h=300&url=http://cdn.demo.sszg.shiyuegame.com/dev/<?=$ftype?>s/<?=$file['name']?>.<?=$ftype?>" target="_blank">二维码</a>
    </div>
    <?php endforeach; ?>
   </div> 
