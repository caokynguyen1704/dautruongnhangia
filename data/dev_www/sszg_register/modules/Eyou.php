<?php
/**
 * Created by PhpStorm.
 * User: weiming Email: 329403630@qq.com
 * Date: 2019/11/20
 * Time: 10:56
 * Eyou 渠道接口
 * 文档地址:http://developer.eyougame.com/php/sdk.html#aes_algor
 *
 * 泰文：http://cdn-shiyue-shanshuoth.365b.com/index.php/eyou/tgList
 */

class Eyou extends App {

    public function enList()
    {
        $gameId = Api::getInt('gameid');
        $region = Api::getInt('region');
        $type = Api::getInt('type');

        $platform = $data = [];
        //默认设置
        if(empty($region)) $region = 999;
        if(empty($type)) $type = 1;
        //校验
        if(!empty($gameId) && !in_array($gameId, [1079400, 1079440])) Api::outEYou(0, $data);
        if(!self::checkType($region, $type)) Api::outEYou(0, $data);

        switch ($region) {
            case 1://亚太
                $platform[] = 'eyouen';
                break;
            case 2://北美
                $platform[] = 'eyouen4';
                break;
            case 3://澳洲
                $platform[] = 'eyouen3';
                break;
            case 4://大洋洲
                $platform[] = 'eyouen2';
                break;

            default :
                $platform = ['eyouen', 'eyouen2', 'eyouen3', 'eyouen4'];
        }

        if(empty($platform)) Api::outEYou(0, $data);

        $ext = '';
        if((int)$type === 2) {
            $ep = ['eyouentest'];
            $ext = " or platform in('" . implode("','", $ep) . "') or ( platform = 'verifyios' and zone_id = 10003)";
        }
        $wheres = "  platform in('" . implode("','", $platform) . "')" . $ext;

        $data = self::getLists($wheres, $region);

        Api::outEYou(1, $data);
    }

    public function twList()
    {
        $gameId = Api::getInt('gameid');
        $region = Api::getInt('region');
        $type = Api::getInt('type');

        $platform = $data = [];
        //默认设置
        if(empty($region)) $region = 999;
        if(empty($type)) $type = 1;
        //校验
        if(!empty($gameId) && !in_array($gameId, [1079100, 1079140])) Api::outEYou(0, $data);
        if(!self::checkType($region, $type)) Api::outEYou(0, $data);

        $platform[] = 'tw';


        if(empty($platform)) Api::outEYou(0, $data);

        $ext = '';
        if((int)$type === 2) {
            $ep = ['twtest'];
            $ext = " or platform in('" . implode("','", $ep) . "') or ( platform = 'verifyios' and zone_id = 10004)";
        }
        $wheres = "  platform in('" . implode("','", $platform) . "')" . $ext;

        $data = self::getLists($wheres, $region);

        Api::outEYou(1, $data);
    }

    public function tgList()
    {
        $gameId = Api::getInt('gameid');
        $region = Api::getInt('region');
        $type = Api::getInt('type');

        $platform = $data = [];
        //默认设置
        if(empty($region)) $region = 999;
        if(empty($type)) $type = 1;
        //校验
        if(!empty($gameId) && !in_array($gameId, [1079700, 1079750])) Api::outEYou(0, $data);
        if(!self::checkType($region, $type)) Api::outEYou(0, $data);

        $platform[] = 'taiguo';

        if(empty($platform)) Api::outEYou(0, $data);

        $ext = '';
        if((int)$type === 2) {
            $ext = " or ( platform = 'verifyios' and zone_id = 10005)";
        }
        $wheres = "  platform in('" . implode("','", $platform) . "')" . $ext;

        $data = self::getLists($wheres, $region);

        Api::outEYou(1, $data);
    }

    public function kokrList()
    {
        $gameId = Api::getInt('gameid');
        $region = Api::getInt('region');
        $type = Api::getInt('type');

        $platform = $data = [];
        //默认设置
        if(empty($region)) $region = 999;
        if(empty($type)) $type = 1;
        //校验
        if(!empty($gameId) && !in_array($gameId, [8003100])) Api::outEYou(0, $data);
        if(!self::checkType($region, $type)) Api::outEYou(0, $data);

        $platform[] = 'kokr';

        if(empty($platform)) Api::outEYou(0, $data);

        $ext = '';
        if((int)$type === 2) {
            $ext = " or ( platform = 'verifyios' and zone_id = 10001)";
        }
        $wheres = "  platform in('" . implode("','", $platform) . "')" . $ext;

        $data = self::getLists($wheres, $region);

        Api::outEYou(1, $data);
    }

    private function checkType($region = 999, $type = 1)
    {
        if(!in_array($type, [1, 2]) || !in_array($region, [999, 1, 2, 3, 4])) return false;
        return true;
    }

    /**
     * 获取服务器列表
     */
    private function getLists($wheres, $region = 999)
    {
        //eYou服务器信息
        $ret = $this->db->getAll("select platform, zone_id, zone_name, open_time
            from game_servers 
            where {$wheres} order by open_time desc,zone_id desc");

        return self::setData($ret, $region);
    }

    private function setData($data, $region)
    {
        $lists = [];
        if(empty($data)) return $lists;

        foreach ($data as $k => $v) {
            //英文版特殊
            if (in_array($v['platform'], ['eyouen'])) $region = 1;
            if (in_array($v['platform'], ['eyouen4'])) $region = 2;
            if (in_array($v['platform'], ['eyouen3'])) $region = 3;
            if (in_array($v['platform'], ['eyouen2'])) $region = 4;

            $lists[] = [
                'Sid' =>  $v['platform'] .'_'. $v['zone_id'],
                'Sname' => 'S'.$v['zone_id'] .'-'.$v['zone_name'],
                'Sparentid' => $v['platform'] .'_'. $v['zone_id'],
                'Opentime' => date(DATE_ISO8601, $v['open_time']),
                'region' => $region,
            ];
        }

        return $lists;
    }
}