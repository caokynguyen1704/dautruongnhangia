UPDATE_TRY_VERSION_MAX = 28
UPDATE_VERSION_MAX = 27
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
    if Config.FunctionData then
        Config.FunctionData.data_info[13] = {id=13, name="icon_activities", icon_name="精彩活动", icon_res="icon_13", type=1, index=5, activate={{'lev',10}}, open_type=1, res_type=1, icon_effect="", is_verifyios=0, desc="10级开放", is_show=1, is_limit_action=0, other_icon_res=""}
    end
end

-- 游戏开时完毕时调用 
function webFunc_GameStart()
	if PartnerVo then
		function PartnerVo:getArtifactSkill(type)
		    if self.artifact_list == nil or next(self.artifact_list) == nil then return end

		    local skill_list = {}
		    for k,v in pairs(self.artifact_list) do
		        if v.extra then
		            local is_main = self:checkArtifactType(v)
		            if type == 2 and is_main then
		                return v.extra
		            elseif type == 1 and not is_main then
		                return v.extra
		            end
		        end
		    end
		end
	end
end

CDN_PATH_BASE = CDN_URL.."/update/update_all/"
-- CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2"
PLATFORM_NAME               = "sygame2"

SHOW_APK_URL = "http://apk.mengdawl.com/226_10064.apk"

GAME_NAME = cc.GameDevice:callFunc("app_name", '', 0, 0, '')

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_android_sygy2",
    register = REG_URL 
}
URL_PATH_ALL.get = function(platform)
    local data = URL_PATH_ALL[platform] or URL_PATH_ALL["other"]
    return data
end

function get_servers_url(account, platform, channelid, srvid, start, num)
    if TEST_APK then
        return string.format("http://cdn.demo.zsyz.shiyuegame.com/api/server_releasegy2.php?account=%s&platform=%s&chanleId=%s&srvid=%s", register, account, platform, DEF_CHANNEL or 'release', srvid)
    end
    return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
end

-- local now_ver = cc.UserDefault:getInstance():getIntegerForKey("local_version", 0)
-- now_ver = math.max(now_ver, NOW_VERSION)
-- local channel = cc.GameDevice:callFunc("channel", '', 0, 0, '')
-- if channel == "10001" or channel == "1002" then -- 测试散文件下载
-- if UPDATE_VERSION_MAX - now_ver > 3 then
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8

QQ_GROUP_LIST = {
    {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=571220165&key=5c35ff8adb11cb5480666c7a65ab5026b0b05559b9b8e6284305b8cd44e428d4&card_type=group&source=external"
     ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."dRkuylksj6y4F4RmyIvi2XRgWN5xHicS"}
}

if get_channel and get_channel() == '16_5' then 
    local webFunc1 = webFunc_GameStart or function() end
    function webFunc_GameStart()
        webFunc1()
        local sdkOnLogin2 = sdkOnLogin
        function sdkOnLogin()
            sdkOnLogin2()
            game_print("============>>>", get_channel())
            local view = LoginController:getInstance().loginView
            if view and view.plate_num then
               view.plate_num:setVisible(false)
            end
        end
    end
end
local channel = device.callFunc("channel")
local sub = device.callFunc("sub_channel")
-- local cflag = (sub == "1" or sub == "2")
if get_channel and channel == "16" then 
    if BUILD_VERSION < 4 and NOW_VERSION < 27 then
        VER_MAINTAIN_MSG = "本包暂停使用，请升级处理"
        sdkAlert("请前往下载最新包，然后再进入游戏\n客服QQ公众号：800185843", "确认", function()
             -- sdkCallFunc("openUrl", "http://apk.mengdawl.com/265_1002.apk")
             local url = "http://apk.mengdawl.com/222_10066.apk" 
             if get_channel() == "16_2" then
                 url = "http://apk.mengdawl.com/223_10064.apk"
             elseif get_channel() == "16_3" then
                 url = "http://apk.mengdawl.com/224_10046.apk"
             elseif get_channel() == "16_4" then
                 url = "http://apk.mengdawl.com/225_10064.apk"
             elseif get_channel() == "16_5" then
                 url = "http://apk.mengdawl.com/226_10046.apk"
             elseif get_channel() == "16_7" then
                 url = "http://apk.mengdawl.com/228_10066.apk"
             elseif get_channel() == "16_8" then
                 url = "http://apk.mengdawl.com/229_10064.apk"
             elseif get_channel() == "16_9" then
                 url = "http://apk.mengdawl.com/235_10046.apk"
             elseif get_channel() == "16_10" then
                 url = "http://apk.mengdawl.com/236_10046.apk"
             elseif get_channel() == "16_11" then
                 url = "http://apk.mengdawl.com/230_10066.apk"
             elseif get_channel() == "16_12" then
                 url = "http://apk.mengdawl.com/234_10046.apk"
             elseif get_channel() == "16_14" then
                 url = "http://apk.mengdawl.com/237_10066.apk"
             end
             sdkCallFunc("openUrl", url)
             cc.Director:getInstance():endToLua()
        end)
    end
end

