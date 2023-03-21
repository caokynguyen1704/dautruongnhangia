UPDATE_TRY_VERSION_MAX = 0
UPDATE_VERSION_MAX = 0
----------------------- 公共函数 ----------
cc.FileUtils:getInstance():purgeCachedEntries()
MOVE_CREATE_BUILD = true
CDN_URL = "http://cdnres.huangxiang.shiyuegame.com" 
REG_URL = "http://register.sszg.shiyuegame.com" 
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy"
DOWN_APK_URL = "http://oss.api.shiyuegame.com/index.php/ChannelBag/bag"
NEED_CHECK_CLOSE=true -- 客户端检查维护状态
RESOURCES_DOWNLOAD_PRO      = 3                    -- 用于边玩边下闲时下载进程数
RESOURCES_DOWNLOAD_PRO_MAX  = 5
VER_UPDATE_ERR_MAX  = 200

QQ_GROUP_LIST = {
    {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=819641544&key=404f913a4983f8cb84335fc3a3babd29c9fd117bff7c74071264f9e73a0fafc3&card_type=group&source=external"
    ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."E5FLGKJYCDkC2_FafI0q0w1r0UDxD9HQ"}
}

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
end

-- 加载模块完成 初始化instance调用完成时调用 
function webFunc_initInstanceEnd()
end

-- 游戏开时完毕时调用 
function webFunc_GameStart()
end

CDN_URL="http://cdn.demo.zsyz.shiyuegame.com"
CDN_PATH_BASE = CDN_URL.."/update/update_all/"

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_expert5",
    register = CDN_URL 
}
URL_PATH_ALL.get = function(platform)
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    return string.format("%s/update/update_android_expert3/servers.php?account=%s&platform=%s&chanleId=%s&srvid=%s", URL_PATH.register, account, platform, channelid, srvid)
end

function get_notice_url(days, loginData)
   --	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    -- return string.format("http://%s/api.php/local/local/notice/?%s", host, os.time())
    return string.format("%s/update/update_android_expert5/notice.php?%s", URL_PATH.register, os.time())
end

