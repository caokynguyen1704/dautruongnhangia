<?php
error_reporting(0);
if($_POST){
	$key=trim($_POST['keyword']);
	$return=array(array('key'=>0,'val'=>'Mời lựa chọn'));
    $file = fopen("gzitem.txt", "r");
	if($key==''){
		while(!feof($file))
		{
			$line=fgets($file);
			$txts=explode('|',$line);
			if(count($txts)==2){
				$tmp=array(
					'key'=>$txts[0],
					'val'=>$txts[1]
				);
				array_push($return,$tmp);
			}
		}
	}else{
		while(!feof($file))
		{
			$line=fgets($file);
			$pos=strpos($line,$key);
			if($pos){
				$txts=explode('|',$line);
				if(count($txts)==2){
					$tmp=array(
						'key'=>$txts[0],
						'val'=>$txts[1]
					);
					array_push($return,$tmp);
				}
			}
		}		
	}
    fclose($file);
	echo(json_encode($return));
}else{
	$return=array(array('key'=>0,'val'=>'Mời lựa chọn'));
	echo(json_encode($return));
}