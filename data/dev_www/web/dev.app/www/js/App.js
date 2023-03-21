/*-----------------------------------------------------+
 * javascript应用
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var App = {
    // 是否开启调试模式
    DEBUG: true,
    // 使用App前需先设置此值
    baseUrl: undefined,

    // 打印一条普通信息
    info: function(msg){
        console.info('[INFO] ' + msg);
    },

    // 打印一条调试信息
    debug: function(msg){
        if(App.DEBUG){
            console.info('[DEBUG] ' + msg);
        }
    },

    // 打印一条错误信息
    error: function(msg){
        console.info('[ERR] ' + msg);
    },

    // 返回时间戳，单位：ms
    timestamp: function(){
        return new Date().getTime();
    },

    // 动态加载js文件
    require: function(classname, callback){
        if(typeof window[classname] !== 'undefined'){
            return window[classname];
        }
        if(typeof App.baseUrl == 'undefined'){
            App.error("未设置App.baseUrl，无法加载js类文件");
            return;
        }

        var url = App.baseUrl + '/js/' + classname + '.js';
        var start = App.timestamp();
        jQuery.ajax({
            async: false,
            url: url,
            cache: true,
            dataType: 'script',
            success: function(){
                if(typeof window[classname].onload == "function"){
                    window[classname].onload();
                }
                if(undefined !== callback){
                    callback();
                }
                App.debug("文件加载完成(" + (App.timestamp() - start)  +"ms): " + url);
            }
        });
        return window[classname];
    },
    
    // 显示计时器
    time_count : function(t){
        if(t == 0 && $('#time_count').size() == 0){
            $('<div id="time_count" class="ui yellow label" style="display:none;z-index:10000;position:fixed;right:10px;bottom:10px;"><i class="huge time red icon" style="margin:3px;float:left;"></i><span style="float:right;display:block;color:red;font-weight:bold; width:120px; height:50px; line-height:50px; text-align:center;"></span></div>').appendTo(document.body);
        }
        if($('#time_count').size() > 0){
            if(t==1) $('#time_count').show();
            $('#time_count span').text(t).css('font-size', 35).animate({'font-size': 18}, "slow");
            setTimeout("App.time_count("+(++t)+")", 1000);
        }
    }

};
