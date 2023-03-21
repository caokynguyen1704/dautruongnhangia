UPDATE_TRY_VERSION_MAX = 15
UPDATE_VERSION_MAX = 15
VerPath = {
["1"] = {name="sszggyiosygy20180810192722m674000v1", size=2789627},
["2"] = {name="sszggyiosygy20180810212030m754324v2", size=112483},
["3"] = {name="sszggyiosygy20180811232415m711335v3", size=352290},
["4"] = {name="sszggyiosygy20180812002114m207429v4", size=82918},
["5"] = {name="sszggyiosygy20180813224611m795059v5", size=4696946},
["6"] = {name="sszggyiosygy20180814175822m580399v6", size=3553051},
["7"] = {name="sszggyiosygy20180815212958m170370v7", size=2857236},
["8"] = {name="sszggyiosygy20180817185417m136995v8", size=5620297},
["9"] = {name="sszggyiosygy20180818184451m891790v9", size=6518866},
["10"] = {name="sszggyiosygy20180818203758m909785v10", size=35379},
["11"] = {name="sszggyiosygy20180820212040m885502v11", size=3600597},
["12"] = {name="sszggyiosygy20180821024245m563372v12", size=1173345},
["13"] = {name="sszggyiosygy20180821034338m880343v13", size=21239},
["14"] = {name="sszggyiosygy20180821225157m154181v14", size=844224},
["15"] = {name="sszggyiosygy20180821225850m894548v15", size=4572},
}
VerMergePath = {
}
----------------------- 公共函数 ----------
-- CDN_URL = "http://cdnres.jstm.shiyuegame.com"
-- CDN_URL = "https://cdn.huanxiang.shiyue.cn"
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



CDN_PATH_BASE = CDN_URL.."/update/update_all/"

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_ios_sygy",
    register = REG_URL 
}
URL_PATH_ALL.get = function(platform)
    if IS_VERIFYIOS then
        FINAL_CHANNEL = "verifyios"          -- 最终渠道
    end
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    if IS_VERIFYIOS then
        platform = "verifyios"
        channelid = "verifyios"
    end
    return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
end

USE_RMB_FEN = false
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8


function PAY_ID_FUNC(id, money)
local list = {
    [3]='com.gmry.hx.6',
    [150]='com.gmry.hx.12',
    [1]='com.gmry.hx.30',
    [4]='com.gmry.hx1.30',
    [2]='com.gmry.hx.68',
    [20]='com.gmry.hx1.68',
    [100]='com.gmry.hx.88',
    [5]='com.gmry.hx.98',
    [21]='com.gmry.hx.128',
    [6]='com.gmry.hx.198',
    [7]='com.gmry.hx.328',
    [8]='com.gmry.hx.648',
    [12]='com.gmry.hx2.30',
    [16]='com.gmry.hx2.128',
    [17]='com.gmry.hx2.98',
    [18]='com.gmry.hx3.128',
    [26]='com.gmry.hx4.128',
    [27]='com.gmry.hx2.198',
    [28]='com.gmry.hx2.328',
    [29]='com.gmry.hx2.648',
    [151]='com.gmry.hx3.30',
    [152]='com.gmry.hx5.128',
    [153]='com.gmry.hx3.198',
    [154]='com.gmry.hx3.328',
}
    return list[id] or string.format("com.gmry.hx.%s", money)
end

-- 光明荣耀
-- -- if cc.GameDevice:callFunc("package_version",'',0,0,'') == "1.0.0" then
-- if BUILD_VERSION >= 5 then
--     UPDATE_SKIP = true
--     IS_VERIFYIOS = true
--     VERIFYIOS_SERVER_NAME = GAME_NAME
--     USE_VERIFYIOS_SCENE = false
--     cc.FileUtils:getInstance():addSearchPath("res_verifyios", true)
--     FINAL_SERVERS = {platform = 'verifyios', zone_id = 3, name = GAME_NAME, host = 's3.verifyios.huanxiang.shiyuegame.com', port = '10003', begin_time = 0}
--     IS_NEED_REAL_NAME = false
-- end
