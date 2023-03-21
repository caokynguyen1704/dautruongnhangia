<?php

/*
 * Date: 2020/07/05
 * Time: 00:10
 * 登录器模块
 */
class Userlogin extends App
{

    public function userLogins()
    {
		
		$username = Api::getVar('username'); //角色账号
		//file_put_contents("/data/zone/sszg_register/modules/Result1.log",var_export($username,true));
        $password = Api::getVar('password'); //角色密码	
        $action   = Api::getVar('action'); 
        $acc_idfa = Api::getVar('acc_idfa');//设备id
        $data     = self::getuser($username);	
        if(empty($data))
	    {
		  $ret = '206';		 
		  Api::outlua(0, $ret);	
		}
		elseif(!empty($data) && $data[0]['password'] != $password)
		{
          $ret = '207';		 
		  Api::outlua(0, $ret);		 
	    }
		elseif(!empty($data) && $data[0]['fenghao'] != 0)
		{
          $ret = '208';		 
		  Api::outlua(0, $ret);		 
	    }		
		else
	    {		 
		  Api::outlua(0, $username);	
		}	

		
        return $user;		
	
	}

    public function userRegs()
    {
		
		
		$username = Api::getVar('username'); //角色账号
        $password = Api::getVar('password'); //角色密码	
        $action   = Api::getVar('action'); 
        $acc_idfa = Api::getVar('acc_idfa');//设备id
        if(empty($acc_idfa))
	    {
		  $ret = '199';		 
		  Api::outlua(0, $ret);	
		}		
		
        $data     = self::getuser($username);
		$ip       = getIP();
		$reg_Idfa  = self::getregIdfa($acc_idfa);
		$reg_ip    = self::getregip($ip);

        if(!preg_match("/^[0-9a-zA-Z]{6,12}$/",$username))
		{
           $ret = '203';		 
		   Api::outlua(0, $ret);	
        }
        if(!preg_match("/^[\x21-\x7e,A-Za-z0-9]{6,16}$/",$password))
		{
           $ret = '204';		 
		   Api::outlua(0, $ret);	
        }
		
        if(!empty($data))
	    {
		  $ret = '200';		 
		  Api::outlua(0, $ret);	
		}			
        elseif($reg_Idfa > 99)
		{
		  $ret = '201';
		  Api::outlua(0, $ret);		 	
        }
        elseif($reg_ip > 99)
		{
		  $ret = '202';
		  Api::outlua(0, $ret);
		 	
        }			
	    else
		{
         $sql="insert into game_account (username,password,acc_idfa,reg_ip) VALUES ('$username','$password','$acc_idfa','$ip')";
		 $this->db->query($sql);
		 $ret  = '205';
		 Api::outlua(0, $ret);
		 	
        } 			
        

		
	}
	
    /**
     * 获取账号
     */
    private function getuser($username)
    {
        $sql = "SELECT uid,username,password,fenghao FROM game_account where username = '$username' ";
        $user = $this->db->getAll($sql);

        return $user;
    }	

    /**
     * 获取设备id
    */
    public  function getregIdfa($acc_idfa)
    {
        $sql = "SELECT acc_idfa FROM game_account where acc_idfa = '$acc_idfa' ";
        $idfa = $this->db->getRowsNum($sql);
		return $idfa;
    }
    /**
     * 获取设备连接ip
    */
    public  function getregip($ip)
    {
        $sql = "SELECT reg_ip FROM game_account where reg_ip = '$ip' ";
        $ip = $this->db->getRowsNum($sql);
		return $ip;
    }	
 	
}	