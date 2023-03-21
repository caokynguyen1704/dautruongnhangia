
UPDATE_TRY_VERSION_MAX = 129
UPDATE_VERSION_MAX = 129
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
URL_BINGNIAO_TOKEN = "https://log-sszg.shiyuegame.com/index.php/misc/bingniao_get_token"

GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_mix",
    register = REG_URL 
}
URL_PATH_ALL.get = function(platform)
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    -- local url = string.format("%s/index.php/serverList/lists2", URL_PATH.register)
 --    local body = string.format("account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", account, platform, channelid or '', srvid or '', start or 0, num or 0)
 --    return url, body
    return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
end

WAIT_SDK_INIT_SUC = false
FILTER_CHARGE = false
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 10
SAVE_RES_FILE_LOCAL = true --是否使用本地边玩边下
-- CDN_RES_URL = CDN_URL .. "/update/update_allres_mix"
-- CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_mix"

local idfa = getIdFa()
local channel = FINAL_CHANNEL or ""
if idfa == "error" or idfa == "" then
    idfa = channel..os.time()..math.random(100000000000, 900000000000)
    cc.UserDefault:getInstance():setStringForKey("init_idfa", idfa)
    cc.UserDefault:getInstance():flush()
end

PLATFORM_NAME = "9377"
CUSTOMER_QQ = "800182660"
require('cli_log')
SHOW_WECHAT_CERTIFY = true
SHOW_GAME_SHARE = true
WECHAT_SUBSCRIPTION = "yhzrsy9377"             -- 微信公众号
WECHAT_SUBSCRIPTION_NAME = "银魂之刃手游"          -- 微信公众号名字
WECHAT_SUBSCRIPTION_IMG = "wechat_subscription_9377"    -- 微信公众号二维码地址(由于不是出第一个包就已经确定的所以全部包含和渠道)
if FINAL_CHANNEL == "9377yhzr3" then
    BUG_PANEL_DESC = "    欢迎您进驻《%s》的冒险世界，如您在游戏中发现BUG或有什么建议。欢迎您填写留言并提交给我们，我们会认真查看每一条留言，让冒险世界做的更好！"
end
if FINAL_CHANNEL == "9377slzj" or FINAL_CHANNEL == "9377yyb" or FINAL_CHANNEL == "9377mtzx" or FINAL_CHANNEL == "9377sydl"or FINAL_CHANNEL == "9377smcs" or FINAL_CHANNEL == "9377yybyh" or FINAL_CHANNEL == "9377yybmscs" or FINAL_CHANNEL == "9377yhzr3" or FINAL_CHANNEL == "9377yhzr2" then
    SHOW_BIND_PHONE = false
else
    SHOW_BIND_PHONE = true
end
IS_NEED_SHOW_LOGO = false
SHOWVERSIONMSG = ""
