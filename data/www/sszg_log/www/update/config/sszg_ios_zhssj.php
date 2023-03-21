<?php ob_start(); ?>

UPDATE_TRY_VERSION_MAX = 22
UPDATE_VERSION_MAX = 22
----------------------- 公共函数 ----------
cc.FileUtils:getInstance():purgeCachedEntries()
MOVE_CREATE_BUILD = true
CDN_URL = "http://cdnres.huangxiang.shiyuegame.com" 
REG_URL = "http://register.sszg.shiyuegame.com" 
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy2"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2"
DOWN_APK_URL = "http://oss.api.shiyuegame.com/index.php/ChannelBag/bag"
NEED_CHECK_CLOSE=true -- 客户端检查维护状态
RESOURCES_DOWNLOAD_PRO      = 3                    -- 用于边玩边下闲时下载进程数
RESOURCES_DOWNLOAD_PRO_MAX  = 5
VER_UPDATE_ERR_MAX  = 2000

-- QQ_GROUP_LIST = {
     -- {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=738268016&key=f16089ebd2f97f10a085128170ad930e1135f73635fd13db361849f7484400b1&card_type=group&source=external"
     -- ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."Bgx9BFmlapNyD--qjG76cRmDex96Lngx"}, -- 738268016
--     {ios="snssdk1128://aweme/detail/6534452667488406792?refer=web&gd_label=click_wap_detail_download_feature&appParam=%7B%22__type__%22%3A%22wap%22%2C%22position%22%3A%22900718067%22%2C%22parent_group_id%22%3A%226553813763982626051%22%2C%22webid%22%3A%226568996356873356814%22%2C%22gd_label%22%3A%22click_wap%22%7D&needlaunchlog=1"
--     ,android="snssdk1128://aweme/detail/6534452667488406792?refer=web&gd_label=click_wap_detail_download_feature&appParam=%7B%22__type__%22%3A%22wap%22%2C%22position%22%3A%22900718067%22%2C%22parent_group_id%22%3A%226553813763982626051%22%2C%22webid%22%3A%226568996356873356814%22%2C%22gd_label%22%3A%22click_wap%22%7D&needlaunchlog=1"} -- 967432606
 --}

package.loaded['cli_log'] = nil
require('cli_log')

VerPath = {
}
VerMergePath = {
}
-- --------------------------------------------------+
-- 非打包热更新处理
-- @author whjing2011@gmail.com
-- --------------------------------------------------*/

-- urlConfig加载完成调用 
function webFunc_urlConfigEnd()
end

-- 加载模块完成 初始化instance调用开始时调用 
function webFunc_initInstanceStart()
    cc.FileUtils:getInstance():purgeCachedEntries()
end

-- 加载模块完成 初始化instance调用完成时调用 
function webFunc_initInstanceEnd()
end

-- 游戏开时完毕时调用 
function webFunc_GameStart()
    Config.VipData.data_get_reward[5] = {label="normal", lev=5, gold=5000, price={{3,2288}}, old_price={{3,10880}}, items={{1,6000000},{26906,50},{14001,1}}, spe_reward={20023}, spe_desc={[[<div fontcolor=#289b14>       ·  激活专属头像框</div><div>--</div><img src=resource/item/0001.png scale=1 visible=true />]],[[       ·  角色挂机经验<div fontcolor=#289b14>+40%</div>]],[[       ·  金币挂机收益<div fontcolor=#289b14>+40%</div>]],[[       ·  英雄经验挂机收益<div fontcolor=#289b14>+40%</div>]],[[       ·  离线挂机收益时长<div fontcolor=#289b14>+6小时</div>]],[[       ·  点金额外金币加成<div fontcolor=#289b14>+80%</div>]],[[       ·  英雄背包数量上限<div fontcolor=#289b14>+80</div>]],[[       ·  日常副本购买各<div fontcolor=#289b14>3次</div>]]}, desc="5星潘", ext_reward={{60101,1}}}
    Config.VipData.data_get_reward[6] = {label="normal", lev=6, gold=10000, price={{3,3888}}, old_price={{3,18880}}, items={{22,6000000},{26907,50},{14001,2},{10453,1}}, spe_reward={10420,10430}, spe_desc={[[       ·  角色挂机经验<div fontcolor=#289b14>+50%</div>]],[[       ·  金币挂机收益<div fontcolor=#289b14>+50%</div>]],[[       ·  英雄经验挂机收益<div fontcolor=#289b14>+50%</div>]],[[       ·  离线挂机收益时长<div fontcolor=#289b14>+8小时</div>]],[[       ·  点金额外金币加成<div fontcolor=#289b14>+100%</div>]],[[       ·  英雄背包数量上限<div fontcolor=#289b14>+100</div>]],[[       ·  日常副本购买各<div fontcolor=#289b14>3次</div>]]}, desc="5星斯芬克斯", ext_reward={}}
    Config.VipData.data_get_reward[7] = {label="normal", lev=7, gold=15000, price={{3,4188}}, old_price={{3,20880}}, items={{1,8000000},{26907,50},{14001,3},{10453,1}}, spe_reward={10030}, spe_desc={[[<div fontcolor=#289b14>       ·  激活专属头像</div><div>--</div><img src=resource/item/0001.png scale=1 visible=true />]],[[       ·  角色挂机经验<div fontcolor=#289b14>+60%</div>]],[[       ·  金币挂机收益<div fontcolor=#289b14>+60%</div>]],[[       ·  英雄经验挂机收益<div fontcolor=#289b14>+60%</div>]],[[       ·  离线挂机收益时长<div fontcolor=#289b14>+10小时</div>]],[[       ·  点金额外金币加成<div fontcolor=#289b14>+120%</div>]],[[       ·  英雄背包数量上限<div fontcolor=#289b14>+120</div>]],[[       ·  日常副本购买各<div fontcolor=#289b14>4次</div>]]}, desc="5星斯芬克斯", ext_reward={{60002,1}}}

    function VedioMainWindow:_updateCellByIndex( cell, index )
        if not self.vedio_show_data then return end
        cell.index = index
        local cell_data = self.vedio_show_data[index]
        if not cell_data then return end
        if self.cur_index then
            if self.cur_index == VedioConst.Tab_Index.Hot then
                cell:setExtendData({is_hot = true})
            else
                cell:setExtendData({})
            end
        end
        cell:setData(cell_data)
    end
end

CDN_PATH_BASE = CDN_URL.."/update/update_all/"
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8
SHOW_BIND_PHONE             = true                  -- 是否需要显示手机绑定界面
SHOW_WECHAT_CERTIFY         = true                  -- 是否显示微信公众号
WECHAT_SUBSCRIPTION         = "sy_sszg"             -- 微信公众号
USE_VERIFYIOS_SCENE         = false                 -- 是否使用提审背景资源

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_ios_sszg",
    register = REG_URL 
}
URL_PATH_ALL.get = function(platform)
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
end

function PAY_ID_FUNC(id, money)
    local config = Config.ChargeData.data_charge_data[id]
    if config and config.pay_id then
        id = config.pay_id
    end
    local list = { -- 特殊礼包充值
    }
    return list[id] or string.format("%s.%s", PAY_ID_PRE, money)
end
PAY_ID_PRE = 'ssol.games'
SHOWVERSIONMSG = ""
NEEDPLAYVIDEO = true
<?php 
function getIP()
{
    $clientIP = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown'))
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown'))
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //web在内网，即web在vpn里的情况
    elseif (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP'])
        $clientIP = $_SERVER['HTTP_CDN_SRC_IP'];
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
        $clientIP = $_SERVER['REMOTE_ADDR'];

    preg_match('/[\d\.]{7,15}/', $clientIP, $clientIPmatches);
    $clientIP = $clientIPmatches[0] ? $clientIPmatches[0] : '0.0.0.0';
    unset($clientIPmatches);

    return $clientIP;
}
$ip = getIP();
$verify_ip = preg_match('/^17\..*/',$ip);
$build_ver=$_REQUEST["build_ver"];
# $package_ver=$_REQUEST["package_ver"];
$is_verifyios = true;
if(($build_ver >= 1 && $is_verifyios) || $verify_ip){
ob_clean();
?>
URL_CONFIG_USE_LOCAL = true
URL_PATH_ALL = true
<?php 
}
ob_flush();
?>
