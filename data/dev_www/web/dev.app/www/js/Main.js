var Main = {
    start: function(){
        App.require('Util');

        // 主导航位置自动激活
        $("#main_menu a").each(function(idx, el){
            if(Util.stripHTML($(el).html()) == $("#main_menu").attr('hl')){
                $(el).addClass('active');
            }
        });

        $(window).scroll(function(){
            if($(this).scrollTop() == 0){
                $('.goto_top').hide();
                $('.goto_bottom').show();
            }else if($(this).scrollTop() >= document.body.scrollHeight - $(this).height()){
                $('.goto_top').show();
                $('.goto_bottom').hide();
            }else{
                $('.goto_top,.goto_bottom').show();
            }
        });

        $('.goto_top').click(function(){
            $(window).scrollTop(0);
        });
        $('.goto_bottom').click(function(){
            $(window).scrollTop(document.body.scrollHeight - $(window).height());
        });
	// 激活多语言生成菜单
	$('.lang_list').dropdown({on: 'hover'});
    }
};
