<?php $this->layout('main') ?>

<?php $this->blockStart('title') ?>服务器管理 - 开发工具<?php $this->blockEnd('title') ?>

<?php $this->blockStart('hl') ?>服务器<?php $this->blockEnd('hl')?>

<?php $this->blockStart('script') ?>
    App.require('Util');
    App.require('Server').init();
<?php $this->blockEnd('script') ?>

<?php $this->blockStart('content') ?>
<div id="result" style="display:none"></div>
<div id="server" class="ui orange bottom attached active tab segment" data-tab="server">
    <?= $select_gamenode?>
    <a class="ui right floated blue button" href="javascript:void(0);" onclick="Util.winOpen('<?= $this->baseUrl ?>/server/log?name=' + Server.gamenode, 960, 640, 'srv_log', true)">游戏节点日志</a>
    <div class="ui menu">
        <a class="item srv_btns file_selector"><i class="settings icon"></i>生成数据文件<?php if($update_file_num):?><div class="flowing ui red circular label" <?php if($update_file_num) echo " title='存在[$update_file_num]个文件发生变化，还没有更新哦'";?>><?=$update_file_num?></div><?php endif?></a>
        <div class='ui flowing popup'>
            <div class="ui top attached label">请选择以下需要生成的数据文件[<?=count($datafiles)?>]:(单次生成不宜超时10个文件，越大文件请逐个生成)</div>
            <form url="<?= $this->baseUrl ?>/server/gen/data" class="ui form file_selector">
                <p>
                <a class="ui submit button">生成数据</a>
                <a class="ui submit button hotswap" title="生成并热更服务器">生成热更</a>
                <a class="ui submit button" title="同步更新客户端SVN"  url='<?= $this->baseUrl ?>/client/update_conf_tosvn'>同步客户端</a>
                <a class="ui button" title="下载活动配置"  href='<?= $this->baseUrl ?>/server/holiday_cfg' target='_blank'>下载活动配置</a>
                <select name="select_make_filemod" style="display:none">
                     <option value="all">生成服、客端</option>
                     <option value="erl">生成服务端</option>
                     <option value="cli">生成客户端</option>
                </select>
		<a id="sel_filter" class="ui toggle checkbox"><input type="checkbox" value='t'/><label>全部/变化</label></a>
                </p>
                <p class="a_list">
                <a class="f_clear">清空</a><a class="f_update">变化</a>
                <?php $str="abcdefghijklnmopqrstuvwxzy"; for($i=0;$i<26;$i++){echo('<a class="f_sel">'.substr($str,$i,1).'</a>');}?>
                <input type="text" class="f_find" title="空格键选择当前匹配项" placeholder="搜索开头关键词" style="width:120px"/>
		</p>
                <input type="text" style="display:none" desc="辅助功能，防止f_find回车跳转页面" />
                <input type="hidden" name="from" value="client" />
                <textarea id="files" name="files" style="height:50px; min-height:50px;"></textarea>
                <div class="four fields" style="margin-top:5px; max-width:980px; max-height:380px; overflow:auto;">
                    <?php foreach($datafiles as $file): ?>
                    <?php if($file['update_flag']):?>
                    <div class="field popup update_flag" title=">>>>>SVN文件[<?=$file['name']?>]发生变化请注意生成<<<<<&#10;文件最后修改时间：<?= $file['mtime']?>&#10;上次生成更新时间：<?= $file['gen_time']?>&#10;当前文件大小：<?= $file['size'] ?> kb"><div class="ui toggle checkbox file_box"><input type="checkbox"  value=<?= $file['name'] ?>><label><?= $file['name'] ?><?=$file['update_flag']?></label></div></div>
                    <?php else:?>
                    <div class="field popup not_update_flag" title="文件最后修改时间：<?= $file['mtime']?>&#10;上次生成更新时间：<?= $file['gen_time']?>&#10;当前文件大小：<?= $file['size'] ?> kb"><div class="ui toggle checkbox file_box"><input type="checkbox"  value=<?= $file['name'] ?>><label><?= $file['name'] ?></label></div></div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>

        <div class="ui dropdown item srv_btns gen_map"><i class="checkered flag icon"></i>生成地图数据<i class="dropdown icon"></i>
            <div class="menu">
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/upsvn'>更新SVN</a>
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/gen/map'>生成地图数据</a>
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/gen/battle'>生成战场数据</a>
            </div>
        </div>
        <div class="ui dropdown item srv_btns make_srv"><i class="checkered flag icon"></i>编译服务器<i class="dropdown icon"></i>
            <div class="menu">
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/make'>编译服务器</a>
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/make_cfg'>编译配置文件</a>
                <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/data_check'>数据检查</a>
            </div>
        </div>

        <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/hotswap'><i class="refresh icon"></i>热更服务器</a>
        <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/stop' confirm='确定要关闭服务器？'><i class="power icon"></i>关闭服务器</a>
        <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/start'><i class="play icon"></i>启动服务器</a>
        <a class="item srv_btns" href="javascript:void(0);" url='<?= $this->baseUrl ?>/server/status'><i class="heartbeat icon"></i>服务器状态</a>
    </div>

    <div class="segment" style="margin:0 0 0.5em 0; padding:0.5em 0 0.5em 0.5em; background:#f6f6f6; border:1px solid #ccc; border-radius:4px;">
        <div id="console" url="<?= $console ?>" style="height:37em; overflow-y: scroll;"></div>
    </div>

    <div class="ui fluid input">
        <input id="cmd" url="<?= $this->baseUrl ?>/server/cmd" placeholder="输入help获得控制台帮助信息" type="text" style="width:600px"/>
    </div>
<div>
<?php $this->blockEnd('content') ?>
