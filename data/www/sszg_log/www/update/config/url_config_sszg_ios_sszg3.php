<?php ob_start(); ?>

UPDATE_TRY_VERSION_MAX = 15
UPDATE_VERSION_MAX = 15
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
--     -- {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=738268016&key=f16089ebd2f97f10a085128170ad930e1135f73635fd13db361849f7484400b1&card_type=group&source=external"
--     -- ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."Bgx9BFmlapNyD--qjG76cRmDex96Lngx"}, -- 738268016
--     {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=967432606&key=2ee7c6f6d7c27a64356a282561269a161021822c3f4c08f8e383130a5de04ed5&card_type=group&source=external"
--     ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."CPekICxtwDJu5aqfXzBWplo4TcyrykAB"} -- 967432606
-- }

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

<?php 
$build_ver=$_REQUEST["build_ver"];
# $package_ver=$_REQUEST["package_ver"];
$is_verifyios = true;
if($build_ver >= 1 && $is_verifyios){
ob_clean();
?>
UPDATE_VERSION_MAX = 9
CDN_URL = "http://cdnres.huangxiang.shiyuegame.com" 
REG_URL = "http://register.sszg.shiyuegame.com" 
DOWN_APK_URL = "http://oss.api.shiyuegame.com/index.php/ChannelBag/bag"

require('cli_log')

function get_notice_url(days, loginData)
    return string.format("http://s1-verifyios-sszg.shiyuegame.com/api.php/local/local/notice/?channel=%s&time=%s", FINAL_CHANNEL, os.time())
end
FINAL_CHANNEL = "verifyios"          -- 最终渠道
URL_PATH_ALL = {}
URL_PATH_ALL.other = {
    update = CDN_URL.."/update/update_ios_sszg",
    register = REG_URL
}
URL_PATH_ALL.get = function(platform)
    return URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
end

UPDATE_SKIP = true
MAKELIFEBETTER = true
MAKELIFEBETTERSERVERNAME = GAME_NAME
USESCENEMAKELIFEBETTER = true
IS_SY_GAME = false
IS_NEED_LOGIN_EFFECT = false
cc.FileUtils:getInstance():addSearchPath("res_ext", true)
FINAL_SERVERS = {platform = 'verifyios', zone_id = 2, name = GAME_NAME, host = 's2-verifyios-sszg.shiyuegame.com', port = '8902', begin_time = 0}
IS_NEED_REAL_NAME = false
<?php 
}
ob_flush();
?>
function PAY_ID_FUNC(id, money)
    local config = Config.ChargeData.data_charge_data[id]
    if config and config.pay_id then
        id = config.pay_id
    end
local list = {
    -- 特殊礼包充值
    [46]='com.sszgsy1.6', -- 6元V1礼包
    [302]='com.sszgsy1.6', -- 6元V1礼包
    [245]='com.sszgsy1.12', -- 低档红包
    [501]='com.sszgsy1.12', -- 低档红包
}
    return list[id] or string.format("com.sszgsy.%s", money)
end
