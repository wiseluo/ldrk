<?php
namespace crmeb\services\template\storage;

use app\services\other\TemplateMessageServices;
use crmeb\basic\BaseMessage;
use crmeb\services\WechatService;
use think\facade\Log;

class Wechat extends BaseMessage
{
    /**
     * 初始化
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config)
    {
        parent::initialize($config); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $templateId
     * @return mixed
     */
    public function getTempId(string $templateId)
    {
        /** @var TemplateMessageServices $services */
        $services = app()->make(TemplateMessageServices::class);
        return $services->getTempId($templateId, 1);
    }

    /**
     * 发送消息
     * @param string $templateId
     * @param array $data
     * @return bool|mixed
     */
    public function send(string $templateId, array $data = [])
    {
        $templateId = $this->getTemplateCode($templateId);
        if (!$templateId) {
            return $this->setError('Template number does not exist');
        }
        $tempid = $this->getTempId($templateId);
        if (!$tempid) {
            return $this->setError('Template ID does not exist');
        }
        if (!$this->openId) {
            return $this->setError('Openid does not exist');
        }
        try {
            $res = WechatService::sendTemplate($this->openId, $tempid, $data, $this->toUrl, $this->color);
            $this->clear();
            return $res;
        } catch (\Exception $e) {
            $this->isLog() && Log::error('发送给openid为:' . $this->openId . '微信模板消息失败,模板id为:' . $tempid . ';错误原因为:' . $e->getMessage());
            return $this->setError($e->getMessage());
        }
    }

    /**
     * 获取所有模板
     * @return \EasyWeChat\Support\Collection|mixed
     */
    public function list()
    {
        return WechatService::noticeService()->getPrivateTemplates();
    }

    /**
     * 添加模板消息
     * @param string $shortId
     * @return \EasyWeChat\Support\Collection|mixed
     */
    public function add(string $shortId)
    {
        return WechatService::noticeService()->addTemplate($shortId);
    }

    /**
     * 删除模板消息
     * @param string $templateId
     * @return \EasyWeChat\Support\Collection|mixed
     */
    public function delete(string $templateId)
    {
        return WechatService::noticeService()->deletePrivateTemplate($templateId);
    }

    /**
     * 返回所有支持的行业列表
     * @return \EasyWeChat\Support\Collection
     */
    public function getIndustry()
    {
        return WechatService::noticeService()->getIndustry();
    }
}
