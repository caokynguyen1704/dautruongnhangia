
UPDATE_TRY_VERSION_MAX = 315
UPDATE_VERSION_MAX = 315
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
PLATFORM_NAME               = "symlf"

SHOW_BIND_PHONE             = true                  -- 是否需要显示手机绑定界面
SHOW_WECHAT_CERTIFY         = true                  -- 是否显示微信公众号
WECHAT_SUBSCRIPTION         = "sy_sszg"             -- 微信公众号
SHOW_BAIDU_TIEBA            = false                 -- 百度贴吧
SHOW_SINGLE_INVICODE        = true                  -- 个人推荐码
SHOW_GAME_SHARE             = true                  -- 游戏分享
IS_SPECIAL_UNION_CHANNEL    = false                 -- 是否为联运渠道

CONF_IDFA_WEB = "http://log.sszg.shiyuegame.com/index.php/misc/idfa_log"

GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_sszg",
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
FILTER_CHARGE = false

-- local now_ver = cc.UserDefault:getInstance():getIntegerForKey("local_version", 0)
-- now_ver = math.max(now_ver, NOW_VERSION)
-- local channel = cc.GameDevice:callFunc("channel", '', 0, 0, '')
-- if channel == "10001" or channel == "1002" then -- 测试散文件下载
-- if UPDATE_VERSION_MAX - now_ver > 3 then
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 35

-- QQ_GROUP_LIST = {
--     {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=571220165&key=5c35ff8adb11cb5480666c7a65ab5026b0b05559b9b8e6284305b8cd44e428d4&card_type=group&source=external"
--      ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."dRkuylksj6y4F4RmyIvi2XRgWN5xHicS"}
-- }

if BUILD_VERSION < 3 then
    VER_MAINTAIN_MSG = "旧包关闭入口"
    get_server_url=function() end
    sdkAlert("本次测试已结束，感谢您的参与！\n玩家交流群：967432606","确定",function() 
        cc.Director:getInstance():endToLua()
    end)
end
local channel = device.getChannel and device.getChannel()
game_print("================>>aaa", channel)
if channel == "16_22" or channel == "47_2" then
    game_print("================>>aaa", channel)
    FINAL_SERVERS = {platform = 'release2', zone_id = 2, name = GAME_NAME, host = 's2-release2-sszg.shiyuegame.com', port = '11003', begin_time = 0}
end

if channel == "47_9" or channel == "46_1" or channel == "16_30" or channel == "59_1" or channel == "60_1" or channel == "61_1" or channel == "63_1" then
    IS_NEED_LOGIN_EFFECT = false
    IS_SPECIAL_UNION_CHANNEL = true
end
if channel == "47_9" or channel == "46_1" or channel == "47_10" then
    IS_NEED_SHOW_LOGO = false
    IS_NEED_LOGIN_EFFECT = false
    SHOWVERSIONMSG = ""
    SHOW_GAME_SHARE = false 
    SHOW_WECHAT_CERTIFY = false
end

NEED_SHOW_REPAIR = true
SAVE_RES_FILE_LOCAL = false
NEW_CDN_RES_GY = true  
-- if BUILD_VERSION > 8 then
-- end

