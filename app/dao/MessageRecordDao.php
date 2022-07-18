<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\MessageRecord;

/**
 * 短信发送记录
 * Class SmsRecordDao
 * @package app\dao\sms
 */
class MessageRecordDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    public function setModel(): string
    {
        return MessageRecord::class;
    }

    public function getMessageList($param, $id)
    {
        $where = [];
        $where[] = ['receive_id', '=', $id];
        if($param['keyword']) {
            $where[] = ['content', 'LIKE', '%'. $param['keyword'] .'%'];
        }
        if($param['status'] !== '') {
            $where[] = ['status', '=', $param['status']];
        }
        if($param['isread'] !== '') {
            $where[] = ['isread', '=', $param['isread']];
        }

        return $this->getModel()::where($where)
            ->append(['status_text', 'isread_text'])
            ->order('id desc')
            ->paginate($param['size'])
            ->toArray();
    }
    
    public function getFailedMessageList()
    {
        $where = [];
        $where[] = ['status', '=', 2];
        $where[] = ['send_count', '<', 3];

        return $this->getModel()::where($where)
            ->select()
            ->toArray();
    }
    
    public function getMissMessageList()
    {
        $time = time();
        $today_time = strtotime(date('Y-m-d'));
        $where = [];
        $where[] = ['status', '=', 0];
        $where[] = ['create_time', '<', $time-300];
        $where[] = ['create_time', '>', $today_time]; //今天零时到5分钟之前，漏发的消息

        return $this->getModel()::where($where)
            ->select()
            ->toArray();
    }

    public function getMessageQueryList($param)
    {
        $where = [];
        if($param['keyword']) {
            $where[] = ['phone|content', 'LIKE', '%'. $param['keyword'] .'%'];
        }
        if($param['status'] !== '') {
            $where[] = ['status', '=', $param['status']];
        }
        if($param['isread'] !== '') {
            $where[] = ['isread', '=', $param['isread']];
        }

        return $this->getModel()::where($where)
            ->append(['status_text', 'isread_text'])
            ->order('id desc')
            ->paginate($param['size'])
            ->toArray();
    }
    
    public function getUnSendMessageList($size)
    {
        $where = [];
        $where[] = ['status', '=', 0];
        $where[] = ['ismerge', '=', 1];

        return $this->getModel()::field('id,phone,content,send_count')
            ->where($where)
            ->limit($size)
            ->select()
            ->toArray();
    }
}
