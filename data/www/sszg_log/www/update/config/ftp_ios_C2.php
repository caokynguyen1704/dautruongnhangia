<?php ob_start(); ?>

UPDATE_TRY_VERSION_MAX = 18
UPDATE_VERSION_MAX = 18
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
VER_UPDATE_ERR_MAX  = 1000
INC_VER_MD5_FILE = true

package.loaded['cli_log'] = nil
if GAME_FLAG ~= "symlf_mix" and PLATFORM_NAME  ~= "mix" then
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
	--选中index索引对象(如果列表允许 会排序在开始第一位)
	function CommonScrollViewSingleLayout:selectCellByIndex(index)
	    local index = index or 1
	    if self.cellList[index] == nil then
	        index = 1
	    end
	    if self.cellList[index] == nil then  return end
	    
	    --一屏幕显示的最大数量
	    local maxRefreshNum 
	    if self.dir == ScrollViewDir.horizontal then  -- 水平
	        maxRefreshNum = self.cacheMaxSize - self.row
	    else
	        maxRefreshNum = self.cacheMaxSize - self.col
	    end
	    local number = self:numberOfCells()
	    if number < maxRefreshNum then
	        --不够显示一屏幕
	        if self.time_show_index == 0 then
	            self.time_show_index = index
	        end
	        for i = 1, number do
	            if i <= self.time_show_index then
	                self:updateCellAtIndex(i)
	            end
	            self.activeCellIdx[i] = true
	        end
	    else
	        --列表允许 情况
	        if self.dir == ScrollViewDir.horizontal then  -- 水平
	            --容器x方向位置
	            local container_x
	            if index == 1 then
	                container_x =  0
	            else
	                container_x =  -(self.cellList[index].x - (self.item_width + self.space_x) * 0.5 )
	            end
	            --容器x方向最大位置
	            local max_contariner_x = -(self.container_size.width - self.size.width)

	            --这两个值都是负数
	            if container_x < max_contariner_x then
	                container_x = max_contariner_x
	            end
	            local show_index = math.floor(math.abs(container_x) / self.item_width) + 1
	            if self.time_show_index < show_index then
	                self.time_show_index = show_index
	            end
	            self.container:setPositionX(container_x)
	            self:checkRectIntersectsRect()
	        else -- 垂直
	            local container_y
	            if index == 1 then
	                container_y = (self.start_y + self.cellList[index].y + self.item_height * 0.5) - self.size.height 
	            else
	                container_y = (self.cellList[index].y + (self.item_height + self.space_y) * 0.5) - self.size.height 
	            end
	            if container_y < 0 then
	                container_y = 0
	            end
	            local index_1 = math.floor( (self.container_size.height - (container_y + self.size.height)) / self.item_height) + 1
	            local show_index = (index_1 - 1) * self.col + 1
	            if self.time_show_index < show_index then
	                self.time_show_index = show_index
	            end
	            self.container:setPositionY(- container_y)
	            self:checkRectIntersectsRect()
	        end
	    end

	    if index > 0 and index <= self:numberOfCells() then
	        local cell = self:getCacheCellByIndex(index)
	        cell.index = index
	        self.cellList[index].cell = cell
	        self:onCellTouched(cell, index)
	    end 
	end

	function RoleDecorateTabBodyPanel:layoutUI()
	    local csbPath = PathTool.getTargetCSB("roleinfo/role_decorate_tab_body_panel")
	    self.root_wnd = cc.CSLoader:createNode(csbPath)
	    self:addChild(self.root_wnd)

	    --读取文件的大小
	    self.size = self.root_wnd:getContentSize()
	    self:setContentSize(self.size)

	    self.main_container = self.root_wnd:getChildByName("main_container")

	    local tab_container = self.main_container:getChildByName("tab_container")
	    
	    self.tab_list = {}

	    local tab_name = {
	        [1] = TI18N("1~5星"),
	        [2] = TI18N("6星"),
	        [3] = TI18N("10星"),
	        [4] = TI18N("皮肤"),
	    }
	    for i=1,4 do
	        local tab_btn = {}
	        local item = tab_container:getChildByName("tab_btn_"..i)
	        tab_btn.btn = item
	        tab_btn.index = i
	        tab_btn.select_bg = item:getChildByName("select_img")
	        tab_btn.select_bg:setVisible(false)
	        tab_btn.label = item:getChildByName("label")
	        tab_btn.label:setString(tab_name[i] or "")
	        tab_btn.label:setTextColor(cc.c4b(0xff,0xf4,0xe4,0xff))
	        -- tab_btn.btn:setVisible(false)

	        self.tab_list[i] = tab_btn
	    end


	    self.comfirm_btn = self.main_container:getChildByName("comfirm_btn")
	    self.comfirm_btn_label = self.comfirm_btn:getChildByName("label")
	    self.comfirm_btn_label:setString(TI18N("确定"))

	    self.scrollCon = self.main_container:getChildByName("scrollCon")

	    self.main_container:getChildByName("att_label"):setString(TI18N("形象属性"))
	    self.main_container:getChildByName("lock_label"):setString(TI18N("解锁条件"))


	    self.unlock = createRichLabel(20,178,cc.p(0.5,0.5),cc.p(448,406))
	    self.main_container:addChild(self.unlock)
	    self.unlock:setString(TI18N("当前形象已解锁"))
	    self.unlock:setVisible(false)

	    self.not_attr_tips = createRichLabel(20,186,cc.p(0.5,0.5),cc.p(448,544))
	    self.main_container:addChild(self.not_attr_tips)
	    self.not_attr_tips:setString(TI18N("当前形象无属性加成"))
	    self.not_attr_tips:setVisible(false)

	    self.attr_label_list = {}
	    local pos = {
	        [1] = cc.p(350, 544),
	        [2] = cc.p(460, 544),
	        [3] = cc.p(350, 510),
	        [4] = cc.p(460, 510)
	    }
	    for i=1,4 do
	        self.attr_label_list[i] = createRichLabel(20, cc.c4b(0x24,0x90,0x03,0xff), cc.p(0,0), pos[i])
	        self.main_container:addChild(self.attr_label_list[i])
	        self.attr_label_list[i]:setString("")
	        -- self.attr_label_list[i]:setVisible(false)
	    end

	    local pos_x = 320
	    local pos_y = 421
	    local pos_w = 574 --pos_x到最右边的位置
	    self.term_list = {}
	    self.term_status_list = {}
	    for i=1,3 do
	        local y =  pos_y - 24 * (i - 1)
	        self.term_list[i] = createRichLabel(20, 175, cc.p(0,0), cc.p(pos_x, y), nil, nil, 500)
	        self.term_status_list[i] = createRichLabel(20, cc.c4b(0x64,0x32,0x23,0xff), cc.p(1, 0), cc.p(pos_w, y))
	        self.main_container:addChild(self.term_list[i])
	        self.main_container:addChild(self.term_status_list[i])
	    end

	    --申请形象信息
	    RoleController:getInstance():requestRoleModelInfo()
	end

	--改变模型
	function RoleDecorateTabBodyPanel:updateSpine( vo )
	    if self.record_id and self.record_id == vo.id then
	        return
	    end
	    self.record_id = vo.id
	    local fun = function()
	        if not self.spine then
	            self.spine = BaseRole.new(BaseRole.type.role, vo.id, nil)
	            self.spine:setAnimation(0,PlayerAction.show,true) 
	            self.spine:setCascade(true)
	            self.spine:setPosition(cc.p(150,484))
	            self.spine:setAnchorPoint(cc.p(0.5,0)) 
	            -- self.spine:setScale(0.8)
	            self.main_container:addChild(self.spine) 
	            self.spine:setCascade(true)
	            self.spine:setOpacity(0)
	            local action = cc.FadeIn:create(0.2)
	            self.spine:runAction(cc.Sequence:create(action))
	        end
	    end
	    if self.spine then
	        self.spine:setCascade(true)
	        --如果有快速切换 不要这个延迟
	        -- local action = cc.FadeOut:create(0.2)
	        -- self.spine:runAction(cc.Sequence:create(action, cc.CallFunc:create(function()
	            doStopAllActions(self.spine)
	            self.spine:removeFromParent()
	            self.spine = nil
	            fun()
	        -- end)))
	    else
	        fun()
	    end    
	end

	local controller = MainuiController:getInstance()
	if controller then
		function controller:iconClickHandle(id,item,action_id)
		    if id == nil then return end
		    if id == MainuiConst.icon.friend then
		        FriendController:getInstance():openFriendWindow(true)
		    elseif id == MainuiConst.icon.mail then
		        MailController:getInstance():openMailPanel(true)
		    elseif id == MainuiConst.icon.daily then
		        TaskController:getInstance():openTaskMainWindow(true)
		    elseif id == MainuiConst.icon.welfare then 
		        WelfareController:getInstance():openMainWindow(true)
		    elseif id == MainuiConst.icon.first_charge then
		        ActionController:getInstance():openFirstChargeView(true)
		    elseif id == MainuiConst.icon.first_charge_new or id == MainuiConst.icon.first_charge_new1 then
		        NewFirstChargeController:getInstance():openNewFirstChargeView(true)
		    elseif id == MainuiConst.icon.icon_charge1 then
		        ActionController:getInstance():openFirstChargeView(true)
		    elseif id == MainuiConst.icon.icon_charge2 then
		        ActionController:getInstance():openFirstChargeView(true)
		    elseif id == MainuiConst.icon.seven_login then
		        ActionController:getInstance():openSevenLoginWin(true)
		    elseif id == MainuiConst.icon.limit_recruit then
		        RecruitHeroController:getInstance():openRecruitHeroWindow(true)
		    elseif id == MainuiConst.icon.limit_gift_entry then
		        ActionController:getInstance():openActionLimitGiftMainWindow(true)
		    elseif id == MainuiConst.icon.seven_rank then 
		        ActionController:getInstance():sender22700(0)
		    elseif id == MainuiConst.icon.crossserver_rank then
		        ActionController:getInstance():sender22700(1)
		    elseif id == MainuiConst.icon.festival or id == MainuiConst.icon.action then     -- 节日活动,竞猜活动
		        ActionController:getInstance():openActionMainPanel(true, id)
		    elseif id == MainuiConst.icon.day_charge then
		        ActionController:getInstance():openActionMainPanel(true, nil, 91005)
		    elseif id == MainuiConst.icon.godpartner then
		        ActionController:getInstance():openActionMainPanel(true, nil, 93006)
		    elseif id == MainuiConst.icon.stronger then
		        StrongerController:getInstance():openMainWin(true)
		    elseif id == MainuiConst.icon.rank then
		        RankController:getInstance():openMainView(true)
		    elseif id == MainuiConst.icon.download then
		        self:openDownloadView(true)
		    elseif id == MainuiConst.icon.icon_firt1 then
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.VIP,5)
		    elseif id == MainuiConst.icon.icon_firt2 then
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.VIP,8)
		    elseif id == MainuiConst.icon.icon_firt3 then
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.VIP,12)
		    elseif id == MainuiConst.icon.icon_firt4 then
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.VIP,13)
		    elseif id == MainuiConst.icon.icon_firt5 then
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.VIP,14)
		    elseif id == MainuiConst.icon.champion then
		        MainSceneController:getInstance():openBuild(CenterSceneBuild.arena, ArenaConst.arena_type.rank) 
		    elseif id == MainuiConst.icon.escort then
		        self:requestOpenBattleRelevanceWindow(BattleConst.Fight_Type.Escort) 
		    elseif id == MainuiConst.icon.godbattle then
		        GodbattleController:getInstance():requestEnterGodBattle() 
		    elseif id == MainuiConst.icon.dungeon_double_time then
		        self:requestOpenBattleRelevanceWindow(BattleConst.Fight_Type.GuildDun)
		    elseif id == MainuiConst.icon.festval then
		        -- ActionController:getInstance():openFestvalLoginWindow(true, ActionRankCommonType.common_day)
		    elseif id == MainuiConst.icon.festval_spring then
		        -- ActionController:getInstance():openFestvalLoginWindow(true, ActionRankCommonType.festval_day)
		    elseif id == MainuiConst.icon.festval_lover then
		        -- ActionController:getInstance():openFestvalLoginWindow(true, ActionRankCommonType.lover_day)
		    elseif id == MainuiConst.icon.combine_login then
		        -- ActionController:getInstance():openFestvalLoginWindow(true, 1011)
		    elseif id == MainuiConst.icon.seven_goal or id == MainuiConst.icon.seven_goal1 or
		           id == MainuiConst.icon.seven_goal2 or id == MainuiConst.icon.seven_goal3 or id == MainuiConst.icon.seven_goal4 then
		        ActionController:getInstance():openSevenGoalView(true)
		    elseif id == SevenGoalEntranceID.period_1 then
		        SevenGoalController:getInstance():openSevenGoalAdventureView(true)
		    elseif id == SevenGoalEntranceID.period_2 then
		        SevenGoalController:getInstance():openSevenGoalSecretView(true)
		    elseif id == MainuiConst.icon.guildwar then
		        self:requestOpenBattleRelevanceWindow(BattleConst.Fight_Type.GuildWar)
		    elseif id == MainuiConst.icon.direct_gift then
		        ActionController:getInstance():openDirectBuyGiftWin(true, 991016)
		    elseif id == MainuiConst.icon.lucky_treasure then
		        ActionController:getInstance():openLuckyTreasureWin(true)
		    elseif id == MainuiConst.icon.preferential then
		        -- ActionController:getInstance():openPreferentialWindow(true, 991014, id)
		    elseif id == MainuiConst.icon.other_preferential then
		        -- ActionController:getInstance():openPreferentialWindow(true, 91014, id)
		    elseif id == MainuiConst.icon.ladder or id == MainuiConst.icon.ladder_2 then
		        self:requestOpenBattleRelevanceWindow(BattleConst.Fight_Type.LadderWar)
		    elseif id == MainuiConst.icon.certify then -- 实名认证
		        RoleController:getInstance():openRoleAttestationWindow(true)
		    elseif id == MainuiConst.icon.fund then -- 基金
		        ActionController:getInstance():openActionFundWindow(true)
		    elseif id == MainuiConst.icon.charge then -- 充值
		        VipController:getInstance():openVipMainWindow(true, VIPTABCONST.CHARGE)
		    elseif id == MainuiConst.icon.day_first_charge then --每日首充
		        DayChargeController:getInstance():openDayFirstChargeView(true)
		    elseif id == MainuiConst.icon.vedio then --录像馆
		        VedioController:getInstance():openVedioMainWindow(true)
		    elseif id == MainuiConst.icon.open_server_recharge then --开服小额充值
		        ActionController:getInstance():openActionOpenServerGiftWindow(true, ActionRankCommonType.open_server)
		    elseif id == OrderActionEntranceID.entrance_id or id == OrderActionEntranceID.entrance_id1 or 
		        id == OrderActionEntranceID.entrance_id2 or id == OrderActionEntranceID.entrance_id3 then --战令活动
		        OrderActionController:getInstance():openOrderActionMainView(true)
		    elseif id == MainuiConst.icon.return_action then
		        ReturnActionController:getInstance():openReturnActionMainPanel(true)
		    elseif id == MainuiConst.icon.oppo_gotocommunity then
		        callFunc("gotoCommunity")
		    elseif id == MainuiConst.icon.crosschampion then
		        self:requestOpenBattleRelevanceWindow(BattleConst.Fight_Type.CrossChampion)
		    elseif id == MainuiConst.icon.personal_gift then --个人推送
		        FestivalActionController:getInstance():openPersonalGiftView(true)
		    elseif id == MainuiConst.icon.special_vip then
		        -- sdkCallFunc("openUrl", "https://wvw.9377.cn/tg/yxh5-zztq.html?1565688089")
		        NoticeController:getInstance():openNoticeView("https://wvw.9377.cn/tg/yxh5-zztq.html?1565688089")
		    end
		end
	end
end

CDN_PATH_BASE = CDN_URL.."/update/update_all/"
UPDATE_MODE_BY_INC = true
INC_VER_DOWNLOAD_PRO_MAX = 8
USE_VERIFYIOS_SCENE         = false                 -- 是否使用提审背景资源
NEW_CDN_RES_GY = true

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
    return string.format("%s/index.php/server/lists2?account=%s&platform=%s&chanleId=%s&srvid=%s&start=%s&num=%s", URL_PATH.register, account, platform, channelid or '', srvid or '', start or 0, num or 0)
end

function get_notice_url(days, loginData)
	local host = REAL_LOGIN_DATA and REAL_LOGIN_DATA.host or loginData.host
    local channel = LoginPlatForm:getInstance():getChannel()
    return string.format("http://%s/api.php/local/local/notice/?channel=%s&time=%s", host, channel, os.time())
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
SHOWVERSIONMSG = ""
CDN_RES_URL = CDN_URL .. "/update/update_allres_gy2_ios"
CDN_RES_GY_URL = CDN_URL .. "/update/update_allres_gy2_ios"

SHOWVERSIONMSG = ""
NEEDPLAYVIDEO = true
SAVE_RES_FILE_LOCAL = true
IS_NEED_SHOW_LOGO = false

--FINAL_SERVERS = {platform = 'verifyios', zone_id = 3, isnew = 0, name = "一服", host = "s3.shiyuepub.com", port = '8903', begin_time = 0}
<?php 
function getIP()
{
    $clientIP = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown'))
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown'))
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //web在内网，即web在vpn里的情况
    elseif (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP'])
        $clientIP = $_SERVER['HTTP_CDN_SRC_IP'];
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
        $clientIP = $_SERVER['REMOTE_ADDR'];

    preg_match('/[\d\.]{7,15}/', $clientIP, $clientIPmatches);
    $clientIP = $clientIPmatches[0] ? $clientIPmatches[0] : '0.0.0.0';
    unset($clientIPmatches);

    return $clientIP;
}
$ip = getIP();
$verify_ip = false; // preg_match('/^17\..*/',$ip);
$build_ver=$_REQUEST["build_ver"];
# $package_ver=$_REQUEST["package_ver"];
$is_verifyios = false;
// if($build_ver == 2){
//  echo("FINAL_SERVERS = FINAL_SERVERS or {platform = 'release2', zone_id = 1, isnew = 0, name = MAKELIFEBETTERSERVERNAME, host = 's1-release2-sszg.shiyuegame.com', port = '11002', begin_time = 0}");
// }
if(($build_ver >= 0 && $is_verifyios) || $verify_ip){
ob_clean();
?>
URL_CONFIG_USE_LOCAL = true
URL_PATH_ALL = true
<?php 
}
ob_flush();
?>
