<?php ob_start(); ?>

UPDATE_TRY_VERSION_MAX = 181
UPDATE_VERSION_MAX = 181
----------------------- 公共函数 ----------
cc.FileUtils:getInstance():purgeCachedEntries()
MOVE_CREATE_BUILD = true
CDN_URL = "http://cdnres.huangxiang.shiyuegame.com" 
REG_URL = "https://register-sszg.shiyuegame.com" 
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy2"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2"
DOWN_APK_URL = "http://oss.api.shiyuegame.com/index.php/ChannelBag/bag"
NEED_CHECK_CLOSE=true -- 客户端检查维护状态
RESOURCES_DOWNLOAD_PRO      = 3                    -- 用于边玩边下闲时下载进程数
RESOURCES_DOWNLOAD_PRO_MAX  = 5
VER_UPDATE_ERR_MAX  = 1000
INC_VER_MD5_FILE = true
DELETE_SPINE_ERROR_RES = true

package.loaded['cli_log'] = nil
if GAME_FLAG ~= "symlf_mix" and PLATFORM_NAME  ~= "mix" and FINAL_CHANNEL ~= "9377_wiseInfo" then
    require('cli_log')
end

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
-- SAVE_RES_FILE_LOCAL = true
NEW_CDN_RES_GY = true
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy2_ios"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2_ios"

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
    local url = string.format("%s/index.php/serverList/lists2", URL_PATH.register)
    local body = string.format("account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", account, platform, channelid or '', srvid or '', start or 0, num or 0)
    return url, body
    --return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
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
    local list = PAY_ID_LIST or {-- 特殊礼包充值
    }
    return list[id] or string.format("%s.%s", PAY_ID_PRE, money)
end
PAY_ID_PRE = 'com.zhcl'
SHOWVERSIONMSG = ""
IS_NEED_SHOW_LOGO = false
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
// $ip = getIP();
$verify_ip = false;// preg_match('/^17\..*/',$ip);
$build_ver=$_REQUEST["build_ver"];
# $package_ver=$_REQUEST["package_ver"];
$is_verifyios = true;
if(($build_ver >= 0 && $is_verifyios) || $verify_ip){
ob_clean();
?>
URL_CONFIG_USE_LOCAL = true
URL_PATH_ALL = true
<?php 
}
ob_flush();
?>
