<?php
namespace app\controller\admin\system;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\system\SystemClearServices;
use app\services\admin\system\attachment\SystemAttachmentServices;


/**
 * 清除默认数据理控制器
 * Class SystemClearData
 * @package app\controller\admin\v1\system
 */
class SystemClearData extends AuthController
{
    /**
     * 构造方法
     * SystemClearData constructor.
     * @param App $app
     * @param SystemClearServices $services
     */
    public function __construct(App $app, SystemClearServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 清除方法入口
     * @param $type
     * @return mixed
     */
    public function index($type)
    {
        switch ($type) {
            case 'temp':
                return $this->userTemp();
                break;
            case 'attachment':
                return $this->attachmentData();
                break;
            case 'system':
                return $this->systemdata();
                break;
            case 'user':
                return $this->userRelevantData();
                break;
            default:
                return $this->fail('参数有误');
        }
    }

    /**
     * 清除用户生成的临时文件
     * @return mixed
     */
    public function userTemp()
    {
        /** @var SystemAttachmentServices $services */
        $services = app()->make(SystemAttachmentServices::class);
        $ids = implode(',', $services->getColumn(['module_type' => 2], 'att_id'));
        $services->del($ids);
        $services->delete(2, 'module_type');
        return $this->success('清除数据成功!');
    }

    /**
     * 清除用户数据
     * @return mixed
     */
    public function userRelevantData()
    {
        $this->services->clearData([
            'wechat_user','wechat_message','user_search','user_visit','user_sign','user_recharge','user_notice_see','user_notice',
            'user_level','user_label_relation','user_label','user_invoice','user_group','user_friends','user_extract','user_enter',
            'user_brokerage_frozen','user_bill','user_address','user','system_store_staff','store_visit','store_service_record',
            'store_service_speechcraft','store_service_record','store_service_log','store_service_feedback','store_service',
            'store_product_reply','store_product_relation','store_product_log','store_pink','store_order_status','store_order_invoice',
            'store_order_economize','store_order_cart_info','store_order','store_coupon_user','store_coupon_issue_user','store_cart',
            'store_bargain_user_help','store_bargain_user','sms_record','qrcode','other_order_status','other_order','member_card',
            'member_card_batch','delivery_service','auxiliary','queue_list'
        ], true);
        $this->services->delDirAndFile('./public/uploads/store/comment');
        return $this->success('清除数据成功!');
    }

    /**
     * 清除所有附件
     * @return mixed
     */
    public function attachmentData()
    {
        $this->services->clearData([
            'system_attachment', 'system_attachment_category'
        ], true);
        $this->services->delDirAndFile('./public/uploads/');
        return $this->success('清除上传文件成功!');
    }

    /**
     * 清楚内容数据
     * @return mixed
     */
    public function articledata()
    {
        $this->services->clearData([
            'article_category', 'article', 'article_content'
        ], true);
        return $this->success('清除数据成功!');
    }

    /**
     * 清楚系统记录
     * @return mixed
     */
    public function systemdata()
    {
        $this->services->clearData([
            'system_notice_admin', 'system_log'
        ], true);
        return $this->success('清除数据成功!');
    }

    /**
     * 替换域名方法
     * @return mixed
     */
    public function replaceSiteUrl()
    {
        list($url) = $this->request->postMore([
            ['url', '']
        ], true);
        if (!$url)
            return $this->fail('请输入需要更换的域名');
        if (!verify_domain($url))
            return $this->fail('域名不合法');
        $this->services->replaceSiteUrl($url);
        return $this->success('替换成功！');
    }
}
