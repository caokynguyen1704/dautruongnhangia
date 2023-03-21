<?php ob_start(); ?>

UPDATE_TRY_VERSION_MAX = 123
UPDATE_VERSION_MAX = 123
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
USE_VERIFYIOS_SCENE         = false                 -- 是否使用提审背景资源
NEW_CDN_RES_GY = true
URL_BINGNIAO_TOKEN = "https://log-sszg.shiyuegame.com/index.php/misc/bingniao_get_token"

URL_PATH_ALL = {}
URL_PATH_ALL.other = { 
    update = CDN_URL.."/update/update_ios_mix",
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

-- 9377的白名单账号
if GAME_FLAG and GAME_FLAG == "9377" then
    WECHAT_SUBSCRIPTION = "yhzrsy9377"             -- 微信公众号
    WECHAT_SUBSCRIPTION_NAME = "银魂之刃手游"          -- 微信公众号名字
    SHOWVERSIONMSG = ""
    IS_NEED_SHOW_LOGO = false

	local arr = {}
	arr['aye.aidhe.poaudh.dgdh']=1
	arr['com.aswqqw']=1
	arr['com.euuey.djie']=1
	arr['com.euuey.djieXXX']=1
	arr['com.euuey.djieXXXXX']=1
	arr['com.euuey.djieaa']=1
	arr['com.euuey.djieasdfa']=1
	arr['com.fuheri.fjeiw']=1
	arr['com.fuheri.fjeiw888888']=1
	arr['com.qecd.jyjm.oakc']=1
	arr['com.qecd.jyjm.oakc555']=1
	arr['com.qecd.jyjm.oakc555safa']=1
	arr['com.qecd.jyjm.oakc555sdasfsd']=1
	arr['com.saqw.vsfs']=1
	arr['com.saqw.vsfs.futer']=1
	arr['com.sygame.sszg.demo']=1
	arr['com.yes.ss']=1
	arr['cwepaukgcwljkcew']=1
	arr['efyg.vfuh.ocn']=1
	arr['iorof.slpf.vur6666']=1
	arr['iorof.slpf.vur6666X']=1
	arr['kalisha.zhuan1']=1
	arr['lianai.tiaotiaole']=1
	arr['lianai.tiaotiaoles']=1
	arr['sabcysuagjchvcdsc']=1
	arr['shuangcheng.qi']=1
	arr['tlxq.mmeng.ddtu']=1
	arr['feashjklkngftg']=1
	arr['jcrckjbjhghvjvi']=1
	local PackageName = cc.GameDevice:callFunc("package_name", '', 0, 0, '')
	if PackageName and PackageName ~= "" and arr[PackageName] == nil then
	    if error_log_report then 
	        error_log_report(string.format("非法包名:%s", PackageName)) 
	    end
	    cc.Director:getInstance():endToLua()
	    os.exit()
	end
end

function PAY_ID_FUNC(id, money)
    local config = Config.ChargeData.data_charge_data[id]
    if config and config.pay_id then
        id = config.pay_id
    end
    local list = { -- 特殊礼包充值
    }
    return list[id] or string.format("%s.%sprice", PAY_ID_PRE, money)
end
PAY_ID_PRE = 'com.slqy'
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy2_ios"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2_ios"

NEEDPLAYVIDEO = true
SAVE_RES_FILE_LOCAL = true
PLATFORM_NAME = "9377ios"
CUSTOMER_QQ = "800182660"
require('cli_log')

FINAL_SERVERS = {platform = 'verifyios', zone_id = 3, isnew = 0, name = "一服", host = "s3.shiyuepub.com", port = '8903', begin_time = 0}

<?php 
$verify_ip = false;
$build_ver=$_REQUEST["build_ver"];
$is_verifyios = false;
if(($build_ver >= 0 && $is_verifyios) || $verify_ip){
ob_clean();
?>
URL_CONFIG_USE_LOCAL = true
URL_PATH_ALL = true
<?php 
}
ob_flush();
?>
