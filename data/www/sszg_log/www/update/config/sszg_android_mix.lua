
UPDATE_TRY_VERSION_MAX = 0
UPDATE_VERSION_MAX = 0
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
     -- {ios="mqqapi://card/show_pslcard?src_type=internal&version=1&uin=738268016&key=f16089ebd2f97f10a085128170ad930e1135f73635fd13db361849f7484400b1&card_type=group&source=external"
     -- ,android="mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3D".."Bgx9BFmlapNyD--qjG76cRmDex96Lngx"}, -- 738268016
--     {ios="snssdk1128://aweme/detail/6534452667488406792?refer=web&gd_label=click_wap_detail_download_feature&appParam=%7B%22__type__%22%3A%22wap%22%2C%22position%22%3A%22900718067%22%2C%22parent_group_id%22%3A%226553813763982626051%22%2C%22webid%22%3A%226568996356873356814%22%2C%22gd_label%22%3A%22click_wap%22%7D&needlaunchlog=1"
--     ,android="snssdk1128://aweme/detail/6534452667488406792?refer=web&gd_label=click_wap_detail_download_feature&appParam=%7B%22__type__%22%3A%22wap%22%2C%22position%22%3A%22900718067%22%2C%22parent_group_id%22%3A%226553813763982626051%22%2C%22webid%22%3A%226568996356873356814%22%2C%22gd_label%22%3A%22click_wap%22%7D&needlaunchlog=1"} -- 967432606
 --}

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
    local controller = WelfareController:getInstance()
    if controller then
        function controller:checkCanAdd(bid)
            local config = Config.HolidayClientData.data_info[bid]
            if config == nil then return false end
            local role_vo = RoleController:getInstance():getRoleVo()
            if role_vo.lev < config.open_lev then return false end
            local is_verifyios = config.is_verifyios 

            -- if is_verifyios == 1 then return true end   -- 如果是提审服都要显示
            if bid == WelfareIcon.bindphone then        -- 手机绑定
                return SHOW_BIND_PHONE 
            elseif bid == WelfareIcon.wechat then       -- 关注微信公众号
                return SHOW_WECHAT_CERTIFY
            elseif bid == WelfareIcon.poste then        -- 百度贴吧
                return SHOW_BAIDU_TIEBA
            elseif bid == WelfareIcon.invicode then     -- 个人推荐码
                return SHOW_SINGLE_INVICODE
            elseif bid == WelfareIcon.share_game then   -- 游戏分享
                return SHOW_GAME_SHARE
            else
                return (not MAKELIFEBETTER)
            end
        end
    end
end

CDN_PATH_BASE = CDN_URL.."/update/update_all/"

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

SHOW_BIND_PHONE             = false                 -- 是否需要显示手机绑定界面
SHOW_WECHAT_CERTIFY         = false                 -- 是否显示微信公众号关注
SHOW_BAIDU_TIEBA            = false                 -- 是否显示百度贴吧关注
SHOW_GAME_SHARE             = false                 -- 是否显示微信分享

CDN_RES_URL = CDN_URL .. "/update/update_allres_mix"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_mix"

