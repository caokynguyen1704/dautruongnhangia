<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Đấu Trường Nhẫn Giả</title>
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
              <select id="qu" class="form-control" name="qu"><option value="0">Máy Chủ</option><option value="1">GM1</option><option value="2">GM2</option><option value="3">GM3</option><option value="4">GM4</option></select>
            </div>
			<div class="form-group">
			  <input type="text" class="form-control" id="usr" name="usr" placeholder="Tài khoản" autocomplete="off">
            </div>
            <div class="form-group">
              <select id="gnxz" class="form-control" name="gnxz">
			  <option value="0">Chọn công năng</option>
			  <option value="1">Nạp Vàng</option>
			  <option value="2">Gói nạp</option>
			  <option value="3">Gửi Thư (Vật phẩm)</option>
			  <option value="4">Xoá Vật Phẩm</option>
			  </select>
            </div>
			            <div class="form-group" id='xgb' style="display:none;">
              <input type="text" class="form-control" id="gb" name="gb" placeholder="Vui lòng nhập số xu Hạnh phúc" autocomplete="off">
            </div>
            <div class="form-group" id='xpay' style="display:none;">
              <input type="text" class="form-control" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" id="pay" name="pay" placeholder="Số lượng nạp" autocomplete="off">
            </div>
            <div id='xbao' style="display:none;">
            <div class="form-group">
			<div class="input-group">
            <input type='text' class="form-control" value='' id='searchipts' placeholder='Tìm gói'>
			<span class="input-group-btn"><button class="btn btn-info" type="button" id='searchl' >Tìm</button></span>	
			</div>
            </div>
			<div class="form-group">
			<select id='bao' class="form-control"><option value='0'>Chọn gói nạp</option>
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
            <input type='text' class="form-control" value='' id='searchipt' placeholder='Tìm item'>
			<span class="input-group-btn"><button class="btn btn-info" type="button" id='search' >Tìm</button></span>	
			</div>
            </div>
			<div class="form-group">
			<select id='item' class="form-control"><option value='0'>Lựa chọn vật phẩm </option>
            <?php
            $file = fopen("item.txt", "r");
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
				<div id="divMsg" style="color:#F00" class="validator-tips">Lấy đủ dùng lỗi acc không hỗ trợ(Đặc biệt thần trang)</div>
              <input type="text" class="form-control" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" id="num" name="num" placeholder="Xin điền vào số lượng" autocomplete="off">
            </div></div>

            <div id='xitem2' style="display:none;">
            <div class="form-group">
			<div class="input-group">
            <input type='text' class="form-control" value='' id='searchipt' placeholder='Tìm item'>
			<span class="input-group-btn"><button class="btn btn-info" type="button" id='search' >Tìm</button></span>	
			</div>
            </div>
			<div class="form-group">
			<select id='item2' class="form-control"><option value='0'>Lựa chọn vật phẩm </option>
            <?php
            $file = fopen("item2.txt", "r");
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
				<div id="divMsg" style="color:#F00" class="validator-tips">Lấy đủ dùng lỗi acc không hỗ trợ(Đặc biệt thần trang)</div>
              <input type="text" class="form-control" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" id="num" name="num" placeholder="Xin điền vào số lượng" autocomplete="off">
            </div></div>



       
			





            <div class="form-center-button">
			  <input class="btn btn-danger" name='reg' id="1" value="Gửi" type="button" onclick= "test(this)">
			    <input class="btn btn-danger" name='reg' id="1" value="Kích hoạt Tool" type="button" onclick= "window.open('https://www.facebook.com/dtkgam')">
			</div><br>
            <div id="divMsg" style="color:#F00" class="validator-tips">DTK GAM</div>
			<div id="divMsg" style="color:#F00" class="validator-tips">Kích hoạt tool để sự dụng</div>
          </form>
        </div>
      </div>
    </div>
  </div>
<script>
$('#gnxz').change(function(){
var gn = $(this).children('option:selected').val();
if(gn == 1 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "none";
}else if(gn == 2 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "";
	document.getElementById('xitem').style.display = "none";
}else if(gn == 3 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "";
}else if(gn == 4 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "";
}else if(gn == 5 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "";
}else if(gn == 7 ){
	
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem2').style.display = "";
		document.getElementById('xitem').style.display = "none";
}else if(gn == 6 ){
			document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "none";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "";
}else if(gn == 9 ){
		document.getElementById('xitem2').style.display = "none";
	document.getElementById('xpay').style.display = "";
	document.getElementById('xbao').style.display = "none";
	document.getElementById('xitem').style.display = "none";
}
});

$('#search').click(function(){
	  var keyword=$('#searchipt').val();
	  $.ajax({
		  url:'itemquery.php',
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
				  $('#item').html('<option value="0">Không tìm được</option>');
			  }
		  },
		  error:function(){
			  bootbox.alert({message:'Thao tác thất bại',title:"Nhắc nhở"});
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
				  $('#bao').html('<option value="0">Không tìm được</option>');
			  }
		  },
		  error:function(){
			  bootbox.alert({message:'Thao tác thất bại',title:"Nhắc nhở"});
		  }
	  });
  });
 $('#search2').click(function(){
	  var keyword=$('#searchipts2').val();
	  $.ajax({
		  url:'itemquery.php',
		  type:'post',
		  'data':{keyword:keyword},
          'cache':false,
          'dataType':'json',
		  success:function(data){
			  if(data){
				  $('#item2').html('');
				for (var i in data){
				  $('#item2').append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
				}
			  }else{
				  $('#item2').html('<option value="0">Không tìm được</option>');
			  }
		  },
		  error:function(){
			  bootbox.alert({message:'Thao tác thất bại',title:"Nhắc nhở"});
		  }
	  });
  }); 
function api(){
	$.ajaxSetup({contentType: "application/x-www-form-urlencoded; charset=utf-8"});
	$.post("api.php", {
		qu:$("#qu").val(),
				gb:$("#gb").val(),
		usr:$("#usr").val(),
		pay:$("#pay").val(),
		bao:$("#bao").val(),
		num:$("#num").val(),
		item:$("#item").val(),
		item2:$("#item2").val(),
		type:$("#type").val(),
		bid:$("#bid").val(),
		lv:$("#lv").val(),
		blv:$("#blv").val(),
		star:$("#star").val(),
		gnxz:$("#gnxz").val()

	},function(data){ 
            $('input[name=reg]').attr('id','1');  
            $('input[name=reg]').attr('value','Gửi đi');
            bootbox.alert({message:data,title:"Lời nhắc"});
	});
 }
 
function test(obj){  
    var _status = obj.id;  
    if(_status != '1'){  
         $('input[name=reg]').attr('id','0'); 		 
         $('input[name=reg]').attr('value','Đang nộp......');
		 return false;  
    }else{  
         $('input[name=reg]').attr('id','0');  
         $('input[name=reg]').attr('value','Đang nộp......');
         api();   
    }    
};

document.onkeydown = function(event) {
	var target, code, tag;
	if (!event) {
		event = window.event; //针对ie浏览器
		target = event.srcElement;
		code = event.keyCode;
		if (code == 13) {
			tag = target.tagName;
			if (tag == "TEXTAREA") { return true; }
			else { return false; }
		}
	}else {
		target = event.target; //针对遵循w3c标准的浏览器，如Firefox
		code = event.keyCode;
		if (code == 13) {
			tag = target.tagName;
			if (tag == "INPUT") { return false; }
			else { return true; }
		}
	}
};
</script>
</body>
</html>