<?php
namespace app\controller\admin;

use app\services\admin\system\SystemMenusServices;
use app\services\admin\user\UserServices;
use app\model\Company;
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

    //增长率
    public function growth($nowValue, $lastValue)
    {
        if ($lastValue == 0 && $nowValue == 0) return 0;
        if ($lastValue == 0) return round($nowValue, 2);
        if ($nowValue == 0) return -round($lastValue, 2);
        return bcmul(bcdiv((bcsub($nowValue, $lastValue, 2)), $lastValue, 2), 100, 2);
    }

    /**
     * 用户图表
     */
    public function userChart()
    {
        /** @var UserServices $uServices */
        $uServices = app()->make(UserServices::class);
        $chartdata = $uServices->userChart();
        return $this->success($chartdata);
    }

    /**
     * 待办事统计
     * @return mixed
     */
    public function jnotice()
    {
        $data['ordernum'] = 0;
        $data['inventory'] = 0;//警戒库存
        $data['commentnum'] = 0;
        $data['reflectnum'] = 0;//提现
        $data['msgcount'] = 0;
        $value = [];
        if ($data['ordernum'] != 0) {
            $value[] = [
                'title' => "您有$data[ordernum]个待发货的订单",
                'type' => 'bulb',
                'url' => '/admin/order/list?status=1'
            ];
        }
        if ($data['inventory'] != 0) {
            $value[] = [
                'title' => "您有$data[inventory]个商品库存预警",
                'type' => 'information',
                'url' => '/admin/product/product_list?type=5',
            ];
        }
        if ($data['commentnum'] != 0) {
            $value[] = [
                'title' => "您有$data[commentnum]条评论待回复",
                'type' => 'bulb',
                'url' => '/admin/product/product_reply?is_reply=0'
            ];
        }
        if ($data['reflectnum'] != 0) {
            $value[] = [
                'title' => "您有$data[reflectnum]个提现申请待审核",
                'type' => 'bulb',
                'url' => '/admin/finance/user_extract/index?status=0',
            ];
        }
        return $this->success($this->noticeData($value));
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

    /**
     * 格式化菜单
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function menusList()
    {
        /** @var SystemMenusServices $menusServices */
        $menusServices = app()->make(SystemMenusServices::class);
        $list = $menusServices->getSearchList();
        $counts = $menusServices->getColumn([
            ['is_show', '=', 1],
            ['auth_type', '=', 1],
            ['is_del', '=', 0],
            ['is_show_path', '=', 0],
        ], 'pid');
        $data = [];
        foreach ($list as $key => $item) {
            $pid = $item->getData('pid');
            $data[$key] = json_decode($item, true);
            $data[$key]['pid'] = $pid;
            if (in_array($item->id, $counts)) {
                $data[$key]['type'] = 1;
            } else {
                $data[$key]['type'] = 0;
            }
        }
        return app('json')->success(sort_list_tier($data));
    }

    // public function companyClassifyList(){
    //     // $hasCache = Cache::get('companyClassifyList');
    //     // if($hasCache){
    //     //     return  show(200, '成功cache', $hasCache);
    //     // }
    //     $data = Company::getCompanyClassifyList();
    //     $new_data = [];
    //     foreach($data as $key => $value){
    //         array_push($new_data,['name'=>$value,'key'=>$key]);
    //     }
    //     // Cache::set('companyClassifyList',$new_data,36000);
    //     return show(200, '成功', $new_data);
    // }

}
