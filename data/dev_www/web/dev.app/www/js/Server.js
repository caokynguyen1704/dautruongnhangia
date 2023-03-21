/*-----------------------------------------------------+
 * 服务端相关处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var Server = {
    console: null,
    gamenode: '',
    url: '',
    ref: null,

    init: function(){
        Server.console = $('#console');
        Server.url = Server.console.attr('url');
        Server.gamenode = $('#select-gamenode').val();
        Server.autoRefresh();

        // 激活数据文件选择框
        $('a.file_selector').popup({on: 'click', lastResort:'bottom left'});
        $('#sel_filter').checkbox().click(function(){
            // if($(this).is(":checked")){
            if($(this).hasClass("checked")){
	    	$('form.file_selector .checkbox').not('.checked').parent('.not_update_flag').hide();
	    }else{
	    	$('form.file_selector .checkbox').not('.checked').parent('.not_update_flag').show();
            }
	});
        $('#sel_filter').checkbox().click();
        $('form.file_selector .file_box').checkbox().click(function(){
            var str = ',' + $('#files').val() + ',';
            var val = $(this).find('input[type=checkbox]').attr('value');
            if($(this).hasClass("checked")){
                if(str.indexOf(',' + val + ',') >= 0){
                    return;
                }else if($('#files').val() == ""){
                    $('#files').val(val);
                }else{
                    $('#files').val($('#files').val() + "," + val);
                }
            }else if(str.indexOf(',' + val + ',') >= 0){
                str = str.replace(','+ val, '');
                $('#files').val(str.replace(/^,|,$/g, ''));
            }
        });
        $('form.file_selector .submit').click(function(){
            var form = $('form.file_selector');
            var btn = $('form.file_selector .submit');
            var url = form.attr('url') + "?" + form.serialize();
            if($(this).hasClass('hotswap')) url += "&hotswap=true&name=" + Server.gamenode;
            if($(this).attr('url')) url = $(this).attr('url');
            btn.addClass('disabled');
            App.time_count(0);
            $.get(url, function(data){
                $("#result").html(data); 
                btn.removeClass('disabled');
                $('#time_count').remove();
                Server.reloadConsole();
            });
        });
        $('#files').change(function(){
            var str = ',' + $(this).val() + ',';
            $('form.file_selector .file_box').each(function(){
                if(str.indexOf(',' + $(this).find('input[type=checkbox]').attr('value') + ',') >= 0){
                    if(!$(this).hasClass('checked')) $(this).click();
                }else{
                    if($(this).hasClass('checked')) $(this).click();
                }
            });
        });
        $('.f_update').click(function(){
            $('form.file_selector .file_box').not('.checked').has('label font').click();
        });
        $('.f_clear').click(function(){
            $('#files').val('');
            $('form.file_selector .file_box.checked').click();
        });
        function find(s){
            $('form.file_selector .file_box').removeClass('find').has('input[type=checkbox][value^='+s+']')
            .addClass('find').first().each(function(){
                var p = $(this).closest('div.fields').scrollTop(0);
                p.scrollTop(Math.max(0, $(this).position().top - p.position().top - 10));
            });
        }
        $('.f_sel').click(function(){
            if($(this).hasClass('findt')){
                $(this).removeClass('findt');
                $('form.file_selector .file_box').removeClass('find');
                return;
            }
            $('.f_sel').removeClass('findt');
            find($(this).addClass('findt').text());
        });
        $('.f_find').keyup(function(e){
            if(e.keyCode == 32 || e.keyCode == 13){
                $('form.file_selector .file_box.find').not('.checked').removeClass('find').click();
                $(this).val('');
            }else{
                find($(this).val());
            }
        });
        $('#cmd').keydown(function(e){
            if(e.keyCode == 13){
                var url = $(this).attr('url') + "?cmd=" + encodeURIComponent($(this).val()) + "&name=" + Server.gamenode;
                $(this).addClass('disabled').val('');
                App.time_count(0);
                $.get(url, function(data){
                    $(this).removeClass('disabled');
                    $("#result").html(data); 
                    Server.reloadConsole();
                    $('#time_count').remove();
                });
            }
        });

        // 激活地图数据生成菜单
        $('.gen_map').dropdown({on: 'hover'});
        // 激活编译服务器菜单
        $('.make_srv').dropdown({on: 'hover'});

        // 激活服务器选择菜单
        $('.select-gamenode').dropdown({
            onChange: function(val){
                if(undefined === val) return;
                Server.gamenode = $('#select-gamenode').val();
            }
        });

        // 激活按键
        $('.srv_btns').click(function(){
            var el = $(this);
            if(el.hasClass('disabled')) return;
            if(el.attr('confirm') && !confirm(el.attr('confirm'))) return;

            if(el.attr('url')){
                // 禁用所有按钮
                $('.srv_btns').each(function(){
                    $(this).removeClass('btn').addClass('disabled');
                });
                var url = el.attr('url');
                url += (url.indexOf('?') >= 0 ? "&":"?") + "name=" + Server.gamenode;
                App.time_count(0);
                $.get(url, function(data){
                    $("#result").html(data); 
                    $('#time_count').remove();
                    Server.reloadConsole();
                    // 恢复所有按钮
                    $('.srv_btns').each(function(){
                        $(this).removeClass('disabled').addClass('btn');
                    });
                });
            }
        });

        // 当鼠标滚动超过底部时阻滚动父窗体
        Server.console.bind('mousewheel', function(e, d){
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
        var c = Server.console;
        var b = $('body');
        var w = $(window);
        // 当滚动条到达底部时自动刷新内容
        if(
                !Server.ref ||
                (
                    (c.prop('scrollHeight') - c.scrollTop() <= c.height() + 30) &&
                    (b.prop('scrollHeight') - w.scrollTop() <= b.height() + 30)
                )
        ){
            Server.reloadConsole();
        }

        if(!Server.ref){
            Server.ref = setInterval(Server.autoRefresh, 2000);
            c.scrollTop(c.prop('scrollHeight'));
        }
    },

    // 重载console
    reloadConsole : function(){
        $.get(Server.url + '?platform=' + Server.platform, function(data){
            Server.console.empty();
            Server.console.html(data);
            Server.console.scrollTop(Server.console.prop('scrollHeight'));
        });
    }
};
