/*-----------------------------------------------------+
 * 版本浏览相关处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var Release = {
    init: function(){
        $('.cards .image').dimmer({on: 'hover'});
        $('.select-version').dropdown({
            on: 'hover',
            onChange: function(val){
                Release.load();
            }
        });
    },

    load: function(){
        var ver = $('#select-version').val();
        $.getJSON(
                "/dev/index.php/release/diff",
                {ver: ver},
                function(data){
                    var text = "";
                    for(var tag in data){
                        text += "<p></p><div class='ui secondary segment'><a class='ui blue ribbon label'>"+ tag +"</a><p></p>";
                        for(var repo in data[tag]){
                            if(!Object.keys(data[tag][repo]).length) continue;

                            text += "<a class='ui tag label'>" + repo + "</a><ul>";
                            for(var k in data[tag][repo]){
                                text += "<li>" + data[tag][repo][k] + "</li>";
                            }
                            text += "</ul>";
                        }
                        text += "</div>";
                    }
                    $(".version-diff").html(text);
                }
        );
    }
};
