<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>GM-360Play</title>
<link href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.staticfile.org/bootstrap-select/1.13.10/css/bootstrap-select.min.css" rel="stylesheet">
<link href="images/main.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.staticfile.org/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/bootbox.js/4.4.0/bootbox.min.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/bootstrap-select/1.13.10/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/bootstrap-select/1.13.10/js/i18n/defaults-zh_CN.js"></script>
</head>
<body>
  <div class="intro" style="margin-top:0px;">
  	 &nbsp;
    <div class="col-md-4 col-centered tac"> <img src="images/logo.png" alt="logo"> </div>
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-8 col-centered">
          <form method="post" id="register-form" autocomplete="off" action="#" class="nice-validator n-default" novalidate>
            &nbsp;
            <div class="form-group">
              <input type="password" class="form-control" id="sqm" name="sqm" placeholder="请输入GM密码 GM" autocomplete="off">
            </div>
            <div class="form-group">
              <select id="qu" class="form-control" name="qu"><option value="0">服务器</option><option value="1">S1</option><option value="2">S2</option><option value="3">S3</option><option value="4">S4</option><option value="5">S5</option><option value="6">S6</option><option value="7">S7</option><option value="8">S8</option><option value="9">S9</option><option value="10">S10</option><option value="11">S11</option><option value="12">S12</option><option value="13">S13</option><option value="14">S14</option><option value="15">S15</option><option value="16">S16</option><option value="17">S17</option><option value="18">S18</option><option value="19">S19</option><option value="99">S99</option></select>
            </div>
			<div cla
            <div class="form-group">
              <input type="text" class="form-control" id="usr" name="sur" placeholder="输入玩家账号" autocomplete="off">
            </div>
            <div class="form-group">
              <select id="type" class="form-control" name="type">  <option value="0"> 授予权限 </option><option value="2">关闭啥玩意</option><option value="7">充值</option><option value="8">邮件</option><option value="6">全部物品</option>  <option value="9"> 欢乐币充值 </option><option value="10">礼包</option></select>
            </div>
            <div class="form-group" id='xgb' style="display:none;">
              <input type="text" class="form-control" id="gb" name="gb" placeholder="请输入欢乐币数量" autocomplete="off">
            </div>
            <div class="form-group" id='zpay' style="display:none;">
              <input type="text" class="form-control" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" id="pay" name="pay" placeholder="请输入数量(1-100000) X10 倍获得" autocomplete="off">
            </div>
			<div id='xbao' style="display:none;">
            <div class="form-group">
			<div class="input-group">
            <input type='text' class="form-control" value='' id='searchipts' placeholder='搜索 '>
			<span class="input-group-btn"><button class="btn btn-info" type="button" id='searchl' >搜索</button></span>	
			</div>
            </div>
			<div class="form-group">
			<select id='bao' class="form-control"><option value='0'>选择套餐礼包</option>
            <?php
            $file = fopen("libao.txt", "r");
            while(!feof($file)){
                $line=fgets($file);
		        $txts=explode('|',$line);
		        if(count($txts)==2){
		            echo '<option value="'.$txts[0].'">'.$txts[1].'</option>';
		        }
            }
            fclose($file);
            ?>
            </select>
            </div></div>
            <div id='xitem' style="display:none;">
            <div class="form-group">
			<div class="input-group">
            <input type='text' class="form-control" value='' id='searchipt' placeholder='搜索'>
			<span class="input-group-btn"><button class="btn btn-info" type="button" id='search' >搜索</button></span>	
			</div>
            </div>
			<div class="form-group">
			<select id='item' class="form-control"><option value='0'>请选择要发送的道具</option>
            <?php
            $file = fopen("gzitem.txt", "r");
            while(!feof($file)){
                $line=fgets($file);
		        $txts=explode('|',$line);
		        if(count($txts)==2){
		            echo '<option value="'.$txts[0].'">'.$txts[1].'</option>';
		        }
            }
            fclose($file);
            ?>
            </select>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" id="num" name="num" placeholder="请输入数量" autocomplete="off">
            </div></div>
            <div class="form-center-button">
			  <input class="btn btn-danger" name='reg' id="1" value="发送" type="button" onclick= "test(this)">
			</div><br>
            <div id="divMsg" style="color:#F00" class="validator-tips">自在源码网-www.mx52.cn 整理</div>
          </form>
        </div>
      </div>
    </div>
  </div>
<script>
$('#type').change(function(){
var gn = $(this).children('option:selected').val();
if(gn == 6 ){
	document.getElementById('zpay').style.display = "none";
	document.getElementById('xitem').style.display = "";
	document.getElementById('xgb').style.display = "none";
	document.getElementById('xbao').style.display = "none";
}else if(gn == 8 ){
	document.getElementById('zpay').style.display = "none";
	document.getElementById('xitem').style.display = "";
	document.getElementById('xgb').style.display = "none";
	document.getElementById('xbao').style.display = "none";
}else if(gn == 7 ){
	document.getElementById('zpay').style.display = "";
	document.getElementById('xitem').style.display = "none";
	document.getElementById('xgb').style.display = "none";
	document.getElementById('xbao').style.display = "none";
}else if(gn == 9 ){
	document.getElementById('zpay').style.display = "none";
	document.getElementById('xitem').style.display = "none";
	document.getElementById('xgb').style.display = "";
	document.getElementById('xbao').style.display = "none";
}else if(gn == 10 ){
	document.getElementById('zpay').style.display = "none";
	document.getElementById('xitem').style.display = "none";
	document.getElementById('xgb').style.display = "none";
	document.getElementById('xbao').style.display = "";
}else{
	document.getElementById('zpay').style.display = "none";
	document.getElementById('xitem').style.display = "none";
	document.getElementById('xgb').style.display = "none";
	document.getElementById('xbao').style.display = "none";
}
});

$('#search').click(function(){
	  var keyword=$('#searchipt').val();
	  $.ajax({
		  url:'gzitemquery.php',
		  type:'post',
		  'data':{keyword:keyword},
          'cache':false,
          'dataType':'json',
		  success:function(data){
			  if(data){
				  $('#item').html('');
				for (var i in data){
				  $('#item').append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
				}
			  }else{
				  $('#item').html('<option value="0">Không tìm thấy</option>');
			  }
		  },
		  error:function(){
			  bootbox.alert({message:'Lỗi hệ thống',title:"Lời nhắc"});
		  }
	  });
  });
 $('#searchl').click(function(){
	  var keyword=$('#searchipts').val();
	  $.ajax({
		  url:'libaoquery.php',
		  type:'post',
		  'data':{keyword:keyword},
          'cache':false,
          'dataType':'json',
		  success:function(data){
			  if(data){
				  $('#bao').html('');
				for (var i in data){
				  $('#bao').append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
				}
			  }else{
				  $('#bao').html('<option value="0">未找到</option>');
			  }
		  },
		  error:function(){
			  bootbox.alert({message:'lỗi hệ thống',title:"提示"});
		  }
	  });
  }); 

function tj(){
	$.ajaxSetup({contentType: "application/x-www-form-urlencoded; charset=utf-8"});
	$.post("gzs.php", {
		qu:$("#qu").val(),
		gb:$("#gb").val(),
		sqm:$("#sqm").val(),
		usr:$("#usr").val(),
		pay:$("#pay").val(),
		bao:$("#bao").val(),
		bid:$("#bid").val(),
		num:$("#num").val(),
		item:$("#item").val(),
		type:$("#type").val()
	},function(data){ 
            $('input[name=reg]').attr('id','1');  
            $('input[name=reg]').attr('value','Gửi đi');
            bootbox.alert({message:data,title:"Lời nhắc"});
	});
 }
 
function test(obj){  
    var _status = obj.id;  
    if(_status != '1' || _status == undefined){  
         $('input[name=reg]').attr('id','0'); 		 
         $('input[name=reg]').attr('value','Đang nộp...');return false;  
    }else{  
         $('input[name=reg]').attr('id','0');  
         tj();   
    }    
} 
</script>
</body>
</html>