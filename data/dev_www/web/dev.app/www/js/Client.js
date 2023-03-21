/*-----------------------------------------------------+
 * 客户端相关处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var Client = {
    console: null,
    platform: '',
    url: '',
    ref: null,

    init: function(){
        $('.dropdown').dropdown({on: 'hover'});

        // 激活平台选择菜单
        $('.select-platform').dropdown({
            onChange: function(val){
                if(undefined === val) return;
                App.info("选择平台:" + val);
                Client.platform = val;
            }
        });

        // 激活模块选择框
        $('a.mod_selector').popup({on: 'click', lastResort:'bottom left'});
        $('.mod_selector .checkbox').checkbox().click(function(){
            var str = ',' + $('#mods').val() + ',';
            var val = $(this).find('input[type=checkbox]').attr('value');
            // alert($(this).attr('class'));
            if($(this).hasClass("checked")){
                if(str.indexOf(',' + val + ',') >= 0){
                    return;
                }else if($('#mods').val() == ""){
                    $('#mods').val(val);
                }else{
                    $('#mods').val($('#mods').val() + "," + val);
                }
            }else if(str.indexOf(',' + val + ',') >= 0){
                str = str.replace(','+ val, '');
                $('#mods').val(str.replace(/^,|,$/g, ''));
            }
        });
        $('#mods').change(function(){
            var str = ',' + $(this).val() + ',';
            $('.mod_selector .checkbox').each(function(){
                if(str.indexOf(',' + $(this).find('input[type=checkbox]').attr('value') + ',') >= 0){
                    if(!$(this).hasClass('checked')) $(this).click();
                }else{
                    if($(this).hasClass('checked')) $(this).click();
                }
            });
        });
        $('.m_clear').click(function(){
            $('#mods').val('');
            $('form.mod_selector .checkbox.checked').click();
        });
        function find(s){
            var n = 0;
            $('form.mod_selector .checkbox').each(function(){
                var val = $(this).find('input[type=checkbox]').val();
                $(this).removeClass('find');
                if(val.indexOf(s) == 0 && s != ''){
                    if((n++)==0){
                        var p = $(this).closest('div.fields').scrollTop(0);
                        p.scrollTop(Math.max(0, $(this).position().top - p.position().top - 10));
                    }
                    $(this).addClass('find');
                }
            });
        }
        $('.m_sel').click(function(){
            if($(this).hasClass('findt')){
                $(this).removeClass('findt');
                $('form.mod_selector .checkbox').removeClass('find');
                return;
            }
            $('.m_sel').removeClass('findt');
            find($(this).addClass('findt').text());
        });
        $('.m_find').keyup(function(e){
            if(e.keyCode == 32 || e.keyCode == 13){
                $('form.mod_selector .checkbox.find').not('.checked').removeClass('find').click();
                $(this).val('');
            }else{
                find($(this).val());
            }
        });
        $('#cmd').keydown(function(e){
            if(e.keyCode == 13){
                var url = $(this).attr('url') + "?cmd=" + encodeURIComponent($(this).val());
                $(this).addClass('disabled').val('');
                App.time_count(0);
                $.get(url, function(data){
                    $('#time_count').remove();
                    $(this).removeClass('disabled');
                    $("#result").html(data); 
                    Client.reloadConsole();
                });
            }
        });
        $('form.mod_selector .submit').click(function(){
            var form = $('form.mod_selector');
            var url = form.attr('url') + "?" + form.serialize();
            var btn = $('form.mod_selector .submit');
            btn.addClass('disabled');
            App.time_count(0);
            $.get(url, function(data){
                $('#time_count').remove();
                $("#result").html(data); 
                btn.removeClass('disabled');
                Client.reloadConsole();
            });
        });

        // 激活按键
        $('.cli_btns').each(function(){
            $(this).click(function(){
                var el = $(this);
                if(el.hasClass('disabled')) return;
                if(el.attr('confirm') && !confirm(el.attr('confirm'))) return;

                if(el.attr('url')){
                    // 禁用所有按钮
                    $('.cli_btns').each(function(){
                        $(this).removeClass('btn').addClass('disabled');
                    });
                    var url = el.attr('url');
                    url += (url.indexOf('?') >= 0 ? "&":"?") + "platform=" + Client.platform;
                    App.time_count(0);
                    $.get(url, function(data){
                        $('#time_count').remove();
                        Client.reloadConsole();
                        $("#result").html(data); 
                        // 恢复所有按钮
                        $('.cli_btns').each(function(){
                            $(this).removeClass('disabled').addClass('btn');
                        });
                    });
                }
            });
        });
        $('.apk_btn').each(function(){
            $(this).click(function(){
                if($("#apklist").is(":hidden")){
                    $("#apklist").show();    //如果元素为隐藏,则将它显现
                    $("#console").hide();    //如果元素为隐藏,则将它显现
                    $.get($(this).attr('url'), function(data){
                        $('#apklist').html(data);
                    });
                }else{
                    $("#apklist").hide();     //如果元素为显现,则将其隐藏
                    $("#console").show();    //如果元素为隐藏,则将它显现
                }            
            })
        });

        // 初始化
        Client.console = $('#console');
        Client.url = Client.console.attr('url');
        Client.platform = $('#select-platform').val();
        Client.autoRefresh();

        // 当鼠标滚动超过底部时阻滚动父窗体
        Client.console.bind('mousewheel', function(e, d){
            var t = $(this);
            if(d > 0 && t.scrollTop() === 0){
                e.preventDefault();
            }else{
                if(d < 0 && (t.scrollTop() == t.get(0).scrollHeight - t.innerHeight())){
                    e.preventDefault();
                }
            }
        });
    },

    // 自动刷新
    autoRefresh: function(){
        var c = Client.console;
        var b = $('body');
        var w = $(window);
        console.info(b.prop('scrollHeight') + " : " + w.scrollTop() + " : " + b.height());
        console.info(c.prop('scrollHeight') + " : " + c.scrollTop() + " : " + c.height());
        // 当滚动条到达底部时自动刷新内容
        if(
                !Client.ref ||
                (
                    (c.prop('scrollHeight') - c.scrollTop() <= c.height() + 30) &&
                    (b.prop('scrollHeight') - w.scrollTop() <= b.height() + 30)
                )
        ){
            Client.reloadConsole();
        }

        if(!Client.ref){
            Client.ref = setInterval(Client.autoRefresh, 2000);
            c.scrollTop(c.prop('scrollHeight'));
        }
    },

    // 重载console
    reloadConsole : function(){
        $.get(Client.url + '?platform=' + Client.platform, function(data){
            Client.console.html(data);
            Client.console.scrollTop(Client.console.prop('scrollHeight'));
        });
    }
};
