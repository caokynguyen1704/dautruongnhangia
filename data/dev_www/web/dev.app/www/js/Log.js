/*-----------------------------------------------------+
 * Git日志相关处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var Log = {
    init: function(){
        $('.select-repo').dropdown({
            onChange: function(val){
                Log.load();
            }
        });
        $('#keyword').change(function(){
            Log.load();
        });
        $('#logs_num').change(function(){
            Log.load();
        });
        $('.query').click(function(){
            Log.load();
        });
    },

    load: function(){
        var repo = $('#repo').val();
        if(undefined === repo) return;
        $.getJSON( "/dev/index.php/log/query",
                {
                    repo: repo,
                    num: $('#logs_num').val(),
                    keyword: $('#keyword').val()
                },
                function(data){
                    $('#logs').html('');
                    $.each(data, function(idx, el){
                        $('#logs').append($("<tr><td><a class='files' style='display:block'>" + el.msg + "</a><div class='ui flowing popup' style='overflow-y:auto; max-height:30em; min-width:30em;'><a class='ui top attached label'>改动的文件</a><p></p>" + el.files.join("<br />") + "</div></td><td>" + el.date + "</td><td>" + el.author + "</td></tr>"));
                    });
                    $('#logs .files').popup({on: 'click'});
                }
        );
    }
};
