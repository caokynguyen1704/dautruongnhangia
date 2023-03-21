
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

SHOW_BIND_PHONE             = false                  -- 是否需要显示手机绑定界面
SHOW_WECHAT_CERTIFY         = false                  -- 是否显示微信公众号
WECHAT_SUBSCRIPTION         = "sy_sszg"             -- 微信公众号
SHOW_BAIDU_TIEBA            = false                 -- 百度贴吧
SHOW_SINGLE_INVICODE        = true                  -- 个人推荐码
SHOW_GAME_SHARE             = false                  -- 游戏分享
IS_SPECIAL_UNION_CHANNEL    = false                 -- 是否为联运渠道
IS_NEED_SHOW_ERWEIMA        = false                 -- 是否显示二维码
BUG_PANEL_DESC  = "    欢迎您进驻《%s》的冒险世界，如您在游戏中发现BUG或有什么建议。欢迎您填写留言并提交给我们，我们会认真查看每一条留言，让冒险世界做的更好！"

CONF_IDFA_WEB = "http://log.sszg.shiyuegame.com/index.php/misc/idfa_log"

GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_sszgmix",
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
FILTER_CHARGE = false

UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8

local channel = device.getChannel and device.getChannel()
if channel == "71_1" then -- 联想渠道需要特殊处理
    function PAY_ID_FUNC(prodId, money) 
        return money
    end
elseif channel == "69_1" then
    function PAY_ID_FUNC(prodId, money) 
        local list={
            [6] = 1,
            [30] = 2,
            [68] = 3,
            [128] = 4,
            [198] = 5,
            [328] = 6,
            [648] = 7,
            [1] = 8,
            [3] = 9,
            [12] = 10,
            [18] = 11,
            [50] = 12,
            [448] = 13,
            [98] = 14,
        }
        return list[money] or money
    end
elseif channel == "66_1" then
    IS_OPPO_CHANNEL = true 
end
PLATFORM_NAME = "symix2"
if channel == "66_1" or channel == "65_1" or channel == "51_1" then
    PLATFORM_NAME  = "symix"
end
if getIdFa() == "error" then
    local idfa = channel..os.time()..math.random(100000000000, 900000000000)
    cc.UserDefault:getInstance():setStringForKey("init_idfa", idfa)
    cc.UserDefault:getInstance():flush()
end
require('cli_log')
function log_get_device_type()
    return cc.GameDevice:callFunc("getDeviceName", '', 0, 0, '')
end

NEED_SHOW_REPAIR = true
SAVE_RES_FILE_LOCAL = false
NEW_CDN_RES_GY = true  

