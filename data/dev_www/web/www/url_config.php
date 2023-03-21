<?php 
// 允许跨域请求
header("Access-Control-Allow-Origin: *");
$platform=$_GET['platform']; // 平台
$channel=$_GET['channel'];  // 渠道
// 服务器列表地址
echo("window.SERVER_LIST_URL='http://cdn.demo.zsyz.shiyuegame.com/api/role_h5.php';\n"); 
// CDN地址
echo("window.CDN_URL='http://cdn.demo.zsyz.shiyuegame.com/h5_cdn/';");
// 数据表地址
echo("window.DATA_URL='http://localhost/data/';");
