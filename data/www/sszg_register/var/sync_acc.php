<?php
$acc_list = require "white_account.cache.php";
$acc_str=implode("\",\n\"", $acc_list);
$acc_str = $acc_str == "" ? $acc_str : "\"$acc_str\"";
$acc_str = "[\n$acc_str\n].";
echo $acc_str;
file_put_contents("/data/zone/white_acc.cfg", $acc_str);
