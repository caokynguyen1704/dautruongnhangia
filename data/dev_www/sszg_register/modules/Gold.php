<?php
/**----------------------------------------------------+
 * 元宝处理
 * @author whjing2012@gmail.com
 +-----------------------------------------------------*/
class Gold extends App
{
    // 元宝返还处理
    public function retback(){
        $account = Api::getVar('account');
        $acc_group = Api::getVar('acc_group');
        $reg_channel = Api::getVar('reg_channel');
        $srv_id = Api::getVar('srv_id');
        $rid = Api::getVar('rid');
        $row = $this->db->getRow("select sum(money) money, max(days) days, max(ctime) ctime from acc_charge where account = '{$account}' and acc_group = '{$acc_group}' and channel_reg = '{$reg_channel}'");
        if(!$row) exit("ok,[]"); // 没有充值
        if($row['ctime'] == null || $row['ctime'] > 0) exit("ok,[]");
        $this->db->update('acc_charge', array("new_rid" => $rid, "ctime" => time(), 'new_srv_id'=>$srv_id), array("account" => $account, "acc_group" => $acc_group, "channel_reg" => $reg_channel));
        $days = $row['days'];
        $money = $row['money'];
        exit("ok,[{money,$money},{days,$days}]");
    }

    // 指定账号是否有未领取充值
    public static function accountCharge($db, $account, $acc_group, $reg_channel){
        $row = $db->getRow("select sum(money) money, max(days) days, max(ctime) ctime from acc_charge where account = '{$account}' and acc_group = '{$acc_group}' and channel_reg = '{$reg_channel}'");
        if(!$row) return ''; // 没有充值
        if($row['ctime'] > 0) return '';
        if($row['money'] < 1) return '';
        $days = $row['days'];
        $money = $row['money'];
        $gold1 = $money * 10;
        $gold = $money * 14;
        $ratio = "1.4";
        if($days >= 7){
            $ratio = "2";
            $gold = $money * 20;
        }elseif($days >= 5){
            $ratio = "1.8";
            $gold = $money * 18;
        }elseif($days >= 3){
            $ratio = "1.7";
            $gold = $money * 17;
        }elseif($days >= 2){
            $ratio = "1.6";
            $gold = $money * 16;
        }
        return "尊敬的冒险者，由于您曾在删档计费测试期间累计充值".$gold1."钻石，当您的角色达到10级时，将会收到邮件发放的".$ratio."倍钻石返利，共计".$gold."。\n注：仅首个达到10级的角色可领取";
    }
}
