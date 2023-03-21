
UPDATE_TRY_VERSION_MAX = 36
UPDATE_VERSION_MAX = 36
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
INC_VER_DOWNLOAD_PRO_MAX = 10
SHOW_GM=true
DOWN_APK_URL = "http://192.168.1.110/index.php/ChannelBag/bag"
NEW_CDN_RES_GY = true
SAVE_RES_FILE_LOCAL = true
INC_VER_MD5_FILE = true

SHOW_BAIDU_TIEBA = false                  -- 是否显示百度贴吧
SHOW_SINGLE_INVICODE = true                  -- 是否显示个人推荐码
SHOW_BIND_PHONE = false                  -- 是否需要显示手机绑定界面
SHOW_WECHAT_CERTIFY = false                  -- 是否显示微信公众号
SHOW_GAME_SHARE = false                   -- 游戏分享

RESOURCES_DOWNLOAD_PRO_MAX = 8
RESOURCES_DOWNLOAD_PRO = 5

NEED_SHOW_REPAIR = true

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_release2gydev",
    register = REG_URL 
}
URL_PATH_ALL.get = function(platform)
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    function sdkOnPay(money, buyNum, prodId, productName, productDesc)
       Send(10399, {msg="pay "..prodId})
    end
    local url = string.format("%s/index.php/serverList/all2", URL_PATH.register)
    local body = string.format("account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", account, platform, channelid or '', srvid or '', start or 0, num or 0)
    return url, body
    --return string.format("%s/index.php/server/all2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
    --- sdkCallFunc("openUrl", "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU4MzY1MDU5Mw==&scene=126&bizpsid=0&subscene=0#wechat_redirect")
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
end

if TEST_APK then
    SHOW_GM=false
    GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')
    FINAL_SERVERS = {platform = 'release2', zone_id = 1, name = GAME_NAME, host = 's1-release2-sszg.shiyuegame.com', port = '11002', begin_time = 0}
end
if TEST_APK2 then
    SHOW_GM=false
    GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')
    FINAL_SERVERS = {platform = 'release2', zone_id = 2, name = GAME_NAME, host = 's2-release2-sszg.shiyuegame.com', port = '11003', begin_time = 0}
end
local channel = device.getChannel and device.getChannel()
game_print("================>>aaa", channel)
if channel == "16_22" or channel == "47_2" or TEST_APK_FLAG then
    game_print("================>>aaa", channel)
    UPDATE_SKIP = true
end

