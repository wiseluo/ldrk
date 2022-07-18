<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
use app\dao\OwnDeclareDao;
use app\dao\OwnDeclareOcrDao;
use \behavior\AliyunTool;


class AliyunServices extends BaseServices
{
    public function imgOcrGeneral($param)
    {
        $ownDeclareDao = app()->make(OwnDeclareDao::class);
        if($param['travel_img'] == '') {
            $ownDeclareDao->update($param['declare_id'], ['ocr_result'=> 3]);
            return false;
        }
        $ownDeclareOcrDao = app()->make(OwnDeclareOcrDao::class);
        $ocr = $ownDeclareOcrDao->get(['declare_id'=> $param['declare_id']]);
        if($ocr) { //不重复识别，只重新匹配
            $this->matchOcrContent($param['declare_id'], $ocr['travel_content']);
            return true;
        }
        
        $aliyunTool = new AliyunTool();
        $img_path = $param['travel_img'];
        try{
            //$res = $aliyunTool->imgOcrGeneral(public_path().trim($img_path,'/'));
            $res = $aliyunTool->imgOcrGeneral($img_path);
            if($res['status'] == 1) {
                $ocr_res = $res['data'];
                if(!isset($ocr_res['ret'])) {
                    $ocr_data = [
                        'declare_id'=> $param['declare_id'],
                        'id_card'=> $param['id_card'],
                        'travel_img' => $param['travel_img'],
                        'remark' => '行程图识别成功-内容为空',
                    ];
                    $ownDeclareOcrDao->save($ocr_data);
                    $ownDeclareDao->update($param['declare_id'], ['ocr_result'=> 2]);
                    return false;
                }
                $content = '';
                foreach($ocr_res['ret'] as $n) {
                    $content .= $n['word'];
                }
                $ocr_data = [
                    'declare_id'=> $param['declare_id'],
                    'id_card'=> $param['id_card'],
                    'travel_text'=> json_encode($ocr_res),
                    'travel_content'=> $content,
                    'travel_img' => $param['travel_img'],
                ];
                $ownDeclareOcrDao->save($ocr_data);
                $this->matchOcrContent($param['declare_id'], $content);
                return true;
            }else{
                $ocr_data = [
                    'declare_id'=> $param['declare_id'],
                    'id_card'=> $param['id_card'],
                    'travel_img' => $param['travel_img'],
                    'remark' => '行程图识别失败-'. $res['msg'],
                ];
                $ownDeclareOcrDao->save($ocr_data);
                $ownDeclareDao->update($param['declare_id'], ['ocr_result'=> 2]);
                return false;
            }
        }catch(\Exception $e) {
            test_log('imgOcrGeneral error:'.$e->getMessage());
            $ocr_data = [
                'declare_id'=> $param['declare_id'],
                'id_card'=> $param['id_card'],
                'travel_img' => $param['travel_img'],
                'remark' => '行程图识别失败-'. $e->getMessage(),
            ];
            $ownDeclareOcrDao->save($ocr_data);
            $ownDeclareDao->update($param['declare_id'], ['ocr_result'=> 2]);
            return false;
        }
    }
    
    //匹配ocr内容
    public function matchOcrContent($declare_id, $content)
    {
        $ownDeclareOcrDao = app()->make(OwnDeclareOcrDao::class);
        $ownDeclareDao = app()->make(OwnDeclareDao::class);
        // $content = str_replace('市','市||', $content);
        // $content = str_replace('市||，','市,', $content);
        // $content = str_replace('市||,','市,', $content);
        // $content = str_replace('市||*，','市,', $content);
        // $content = str_replace('市||*,','市,', $content);
        // //$match = '/(到述或途经|到达或途经|到达或途|到达或途圣|到达或途径)：(\S+)(洁果包含|吉果包含|结果包含|果包含|一证通查来了|一证通查询来了|这些中高风险地区。)/';
        // $match = '/(到|内){1}[^：]+：(.*?)[|]{2}/';
        // $route_res = preg_match($match, $content, $route);
        // if($route_res) {
        //     $travel_route = $route[2];
        //     $ocr_result = 1;
        //     $match_result = 1;
        // }else{
        //     $travel_route = '';
        //     $ocr_result = 2;
        //     $match_result = 2;
        // }
        
        $grep = ['市','区','自治州','自治县','盟'];
        $grep_r = ['市||','区||','自治州||','自治县||','盟||'];
        $content = str_replace($grep, $grep_r, $content);
        $content = str_replace('内城市||','城市', $content);
        $content = str_replace('风险地区||','风险地区', $content);
        $content = str_replace('(地区||','(地区', $content);
        $match = '/(到|内){1}[^：]+：(.*[|]{2}\*?)/';
        $route_res = preg_match($match, $content, $route);
        if($route_res) {
            $travel_route = str_replace('||', '', $route[2]);
            $ocr_result = 1;
            $match_result = 1;
        }else{
            $travel_route = '';
            $ocr_result = 2;
            $match_result = 2;
        }

        $travel_route = $this->_removeBadStr($travel_route);

        //设置来义申报的系统预警
        $declare = $ownDeclareDao->get($declare_id);
        if($declare['declare_type'] == 'come') {
            $sys_warning = $this->getDeclareSysWarning($travel_route);
        }else{
            $sys_warning = 0;
        }
        
        $time_res = preg_match('/\d{4}\.\d{2}\.\d{4}：\d{2}：\d{2}/', $content, $time);
        if($time_res) {
            $travel_time = $time[0];
            $travel_time = str_replace(".", "-", $travel_time);
            $travel_time = str_replace("：", ":", $travel_time);
            $travel_time = strtotime($travel_time);
            if($travel_time) {
                $travel_time = date('Y-m-d H:i:s', $travel_time);
            }else{
                $travel_time = null;
            }
        }else{
            $travel_time = null;
        }
        $ocr_data = [
            'travel_route' => $travel_route,
            'travel_time' => $travel_time,
            'match_result' => $match_result,
        ];
        $ownDeclareOcrDao->update(['declare_id'=> $declare_id], $ocr_data);
        $declare_data = [
            'ocr_result'=> $ocr_result,
            'travel_route'=> $travel_route,
            'travel_time' => $travel_time,
            'sys_warning' => $sys_warning,
        ];
        if($sys_warning == 1) {
            $declare_data['is_warning'] = 1;
        }
        if(strstr($travel_route, '*')) { //行程码是否带星号
            $declare_data['isasterisk'] = 1;
        }
        $ownDeclareDao->update($declare_id, $declare_data);
        return true;
    }

    public function contentToRoute($content)
    {
        // $content = str_replace('市','市||', $content);
        // $content = str_replace('市||，','市,', $content);
        // $content = str_replace('市||,','市,', $content);
        // $content = str_replace('市||*，','市,', $content);
        // $content = str_replace('市||*,','市,', $content);
        // //$match = '/(到述或途经|到达或途经|到达或途|到达或途圣|到达或途径)：(\S+)(洁果包含|吉果包含|结果包含|果包含|一证通查来了|一证通查询来了|这些中高风险地区。)/';
        // $match = '/(到|内){1}[^：]+：(.*?)[|]{2}/';
        // $route_res = preg_match($match, $content, $route);
        // if($route_res) {
        //     $travel_route = $route[2];
        // }else{
        //     $travel_route = '';
        // }
        // return $travel_route;
        
        $grep = ['市','区','自治州','自治县','盟'];
        $grep_r = ['市||','区||','自治州||','自治县||','盟||'];
        $content = str_replace($grep, $grep_r, $content);
        $content = str_replace('内城市||','城市', $content);
        $content = str_replace('风险地区||','风险地区', $content);
        $content = str_replace('(地区||','(地区', $content);
        $match = '/(到|内){1}[^：]+：(.*[|]{2}\*?)/';
        $route_res = preg_match($match, $content, $route);
        if($route_res) {
            $travel_route = str_replace('||', '', $route[2]);
        }else{
            $travel_route = '';
        }
        return $travel_route;
    }
        
        

    private function _removeBadStr($travel_route){
        $travel_route = str_replace('安安徽','安徽',$travel_route);
        $travel_route = str_replace('号贵州','贵州',$travel_route);
        $travel_route = str_replace('厂广','广',$travel_route);
        $travel_route = str_replace('区四川','四川',$travel_route);
        $travel_route = str_replace('云云南','云南',$travel_route);
        $travel_route = str_replace('二上海','上海',$travel_route);
        $travel_route = str_replace('上上海','上海',$travel_route);
        $travel_route = str_replace('市结','市',$travel_route);
        $travel_route = str_replace('：','',$travel_route);
        $travel_route = str_replace('」','',$travel_route);
        $travel_route = str_replace('※','',$travel_route);
        $travel_route = str_replace('_','',$travel_route);
        $travel_route = str_replace('-','',$travel_route);
        $travel_route = preg_replace('/[a-zA-Z]/','',$travel_route);
        return $travel_route;
    }

    public function getDeclareSysWarning($travel_route)
    {
        $risk_list = getRiskDistrictListCommon();
        foreach($risk_list as $v) {
            if(strstr($v['province'], '香港')) {
                if(strstr($travel_route, '香港')) {
                    return 1;
                }
            }else if(strstr($v['province'], '澳门')) {
                if(strstr($travel_route, '澳门')) {
                    return 1;
                }
            }else {
                if(strstr($travel_route, $v['city'])) {
                    return 1;
                }
            }
        }
        return 0;
    }
}
