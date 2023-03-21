UPDATE_TRY_VERSION_MAX = 0
UPDATE_VERSION_MAX = 0
----------------------- 公共函数 ----------
cc.FileUtils:getInstance():purgeCachedEntries()
MOVE_CREATE_BUILD = true
CDN_URL = "http://cdnres.huangxiang.shiyuegame.com" 
REG_URL = "http://register.sszg.shiyuegame.com" 
CDN_RES_URL = CDN_URL .. "/update/update_allres_tx_try"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_tx_try"
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

<?php ob_start(); ?>

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
	Config.HolidayClientData.data_info = {
	[8001] = {bid=8001, sort_val=2, title="超值月卡", ico="1", type_ico=1, panel_type=1, type=2, is_verifyios=1, open_lev=10, items={}},
	[8002] = {bid=8002, sort_val=5, title="神格许愿", ico="2", type_ico=0, panel_type=2, type=2, is_verifyios=1, open_lev=15, items={}},
	[8003] = {bid=8003, sort_val=1, title="签到", ico="3", type_ico=0, panel_type=3, type=2, is_verifyios=1, open_lev=8, items={}},
	[8004] = {bid=8004, sort_val=3, title="升级有礼", ico="4", type_ico=0, panel_type=4, type=2, is_verifyios=1, open_lev=5, items={}},
	[8005] = {bid=8005, sort_val=4, title="战力礼包", ico="5", type_ico=0, panel_type=5, type=2, is_verifyios=1, open_lev=10, items={}},
	[8006] = {bid=8006, sort_val=8, title="微信有礼", ico="18", type_ico=0, panel_type=6, type=2, is_verifyios=0, open_lev=10, items={{1,50},{2,1},{44110,1}}},
	[991003] = {bid=991003, sort_val=6, title="投资计划", ico="8", type_ico=0, panel_type=7, type=2, is_verifyios=1, open_lev=10, items={}},
	[991008] = {bid=991008, sort_val=7, title="成长基金", ico="20", type_ico=0, panel_type=8, type=2, is_verifyios=1, open_lev=10, items={}}
	}
end

CDN_PATH_BASE = CDN_URL.."/update/update_all/"
-- CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2"
PLATFORM_NAME               = "txtry"

VER_MAINTAIN_MSG = "暂停使用"

GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_txtry",
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

UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8

function sdkOnPay()
    message("请前行下载游戏安装后充值")
end

FINAL_SERVERS = {platform = 'txtry', zone_id = 1, name = "闪烁之光", host = 's1-txtry-sszg.shiyuegame.com', port = '45021', begin_time = 0}

function webFunc_initInstanceEnd()
if LoginController == nil then return end
function LoginController:registAccount()
    local function randomName(str)  
        local result = str
        local a = string.char(math.random(65, 90))
        local b = string.char(math.random(97, 122))
        local c = string.char(math.random(48, 57))
        if math.random(3) % 3 == 0 then
            result = result..a
        elseif  math.random(3) % 2 == 0 then
            result = result..b
        else
            result = result..c
        end
        if StringUtil.getStrLen(result)<12 then
            result = randomName(result)
        end
        return result
    end
    local url = "http://s1-txtry-sszg.shiyuegame.com/api.php/local/local/accout/"
    local xhr = cc.XMLHttpRequest:new()
    xhr.responseType = cc.XMLHTTPREQUEST_RESPONSE_STRING
    xhr:open("POST", url)
    xhr:registerScriptHandler(function()
        local data = {}
        if xhr.readyState == 4 and (xhr.status >= 200 and xhr.status < 207) then
            local str_table = assert(loadstring("return "..xhr.response))() or 'error'
            if type(str_table) == "table" and str_table.error == "success" then
                data.usrName = str_table.data
            else
                data.usrName = randomName("")
            end
        else
            data.usrName = randomName("")
        end
        data.password = "sygame123"
        self:loginPlatformRequest(data)
    end)
    xhr:send()
end
end

