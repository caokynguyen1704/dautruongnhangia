/*-----------------------------------------------------+
 * javascript公共函数库
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
var Util = {
    /**
     * 弹出居中窗口
     * @param string src 窗口内容地址
     * @param int w 宽度
     * @param int h 高度
     * @param bool resizable 是否允许改变大小和出现滚动条
     * @param string name 窗口名称
     * @return null
     */
    winOpen: function(src, w, h, name, r){
        w = w? w : 600;
        h = h? h : 480;
        var l = (screen.width - w) / 2;
        var t = (screen.height - h) / 2;
        r = r ? 'resizable=yes,scrollbars=yes' : 'resizable=no,scrollbars=no';
        name = name ? name : '_blank';
        var win = window.open(src, name, 'width=' + w + ',height=' + h + ',top=' + t + ',left=' + l + ',' + r);
        win.focus();
        return win;
    },

    /*
     * 去除文本中的html标签
     */
    stripHTML: function(str) {
        var container = document.createElement('div');
        container.innerHTML = str;
        return container.textContent || container.innerText;
    }
};
