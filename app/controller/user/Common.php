<?php

namespace app\controller\user;
use app\services\CommonServices;
/**
 * 公共接口基类 主要存放公共接口
 * Class Common
 * @package app\controller\admin
 */
class Common extends AuthController
{
    /**
     * 获取logo
     * @return mixed
     */
    public function getLogo()
    {
        return $this->success([
            'logo' => sys_config('site_logo'),
            'logo_square' => sys_config('site_logo_square'),
            'site_name' => sys_config('site_name')
        ]);
    }

    /**
     * @return mixed
     */
    public function check_auth()
    {
        return $this->success('succes', ['auth' => true]);
    }

    /**
     * @return mixed
     */
    public function auth()
    {
        return $this->success("ok!");
        // return $this->getAuth();
    }

    /**
     * 首页头部统计数据
     * @return mixed
     */
    public function homeStatics()
    {
        return $this->success();
    }

    /**
     * 消息返回格式
     * @param array $data
     * @return array
     */
    public function noticeData(array $data): array
    {
        // 消息图标
        $iconColor = [
            // 邮件 消息
            'mail' => [
                'icon' => 'md-mail',
                'color' => '#3391e5'
            ],
            // 普通 消息
            'bulb' => [
                'icon' => 'md-bulb',
                'color' => '#87d068'
            ],
            // 警告 消息
            'information' => [
                'icon' => 'md-information',
                'color' => '#fe5c57'
            ],
            // 关注 消息
            'star' => [
                'icon' => 'md-star',
                'color' => '#ff9900'
            ],
            // 申请 消息
            'people' => [
                'icon' => 'md-people',
                'color' => '#f06292'
            ],
        ];
        // 消息类型
        $type = array_keys($iconColor);
        // 默认数据格式
        $default = [
            'icon' => 'md-bulb',
            'iconColor' => '#87d068',
            'title' => '',
            'url' => '',
            'type' => 'bulb',
            'read' => 0,
            'time' => 0
        ];
        $value = [];
        foreach ($data as $item) {
            $val = array_merge($default, $item);
            if (isset($item['type']) && in_array($item['type'], $type)) {
                $val['type'] = $item['type'];
                $val['iconColor'] = $iconColor[$item['type']]['color'] ?? '';
                $val['icon'] = $iconColor[$item['type']]['icon'] ?? '';
            }
            $value[] = $val;
        }
        return $value;
    }

    // 首页面板的统计数据
    // 本周期总预约，已检测，未检测
    // 上周期总预约，已检测，未检测
    // 总管辖人数，未审核，未预约，未预约自然人
    public function indexSubscribeSumData(){
        $param = $this->request->param();
        $CommonServer = app()->make(CommonServices::class);
        $res = $CommonServer->indexSubscribeSumData([],$this->userInfo);
        if($res['status']){
            return show(200,$res['msg'],$res['data']);
        }else{
            return show(400,$res['msg']);
        }
    }

}
