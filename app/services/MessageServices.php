<?php

namespace app\services;

use \behavior\SmsTool;
use think\facade\Log;
use app\services\admin\TemplateMessageServices;
use app\dao\MessageRecordDao;
use crmeb\services\SwooleTaskService;
use think\facade\Config;

//消息服务
class MessageServices
{
    
    /**
     * 异步发送消息
     * @param string $tempkey 模板key
     * @param array $message_data 消息内容数据
     * @param array $param_data 消息替换变量数据
     * @param string $ismerge 是否合并：0=否，1=是
     * @return bool
     */
    public function asyncMessage($tempkey, $message_data, $param_data, $ismerge = 0)
    {
        try {
            $template = app()->make(TemplateMessageServices::class)->get(['tempkey'=> $tempkey]);
            if($template == null) {
                return false;
            }
            $content = $template['content'];
            if($content == '') {
                return false;
            }
            foreach($param_data as $k => $v) {
                $content = str_replace('{{'. $k .'}}', $v, $content);
            }
            $record_data = [
                'tempkey'=> $tempkey,
                'receive_id'=> $message_data['receive_id'],
                'receive'=> $message_data['receive'],
                'phone'=> $message_data['phone'],
                'content'=> $content,
                'source_id'=> $message_data['source_id'],
                'ismerge'=> $ismerge,
            ];
            $message_record = app()->make(MessageRecordDao::class)->save($record_data);
            if($ismerge == 1) { //等待合并，暂时不发
                return true;
            }
            //测试不发
            // if(Config::get('app.app_host') == 'dev'){
            //     return true;
            // }
            $record_data['id'] = $message_record->id;
            $this->sendMessage($record_data);
            return true;
        }catch (\Throwable $e){
            test_log('asyncMessage发生错误:'. $e->getMessage());
            return false;
        }
    }

    //发送消息
    public function sendMessage($record_data)
    {
        SwooleTaskService::msg()->taskType('msg')->data(['action'=>'sms', 'param'=>['phone'=> $record_data['phone'], 'content'=> $record_data['content'], 'id'=> $record_data['id']]])->push();
        return true;
    }

    //发短信
    public function sms($data)
    {
        //test_log('执行发送sms');
        $smsTool = new SmsTool();
        $res = $smsTool->sendSms($data['phone'], $data['content']);
        if($res['status'] == 1) {
            //test_log('执行发送sms-成功');
            $messageRecordDao = app()->make(MessageRecordDao::class);
            $message_record = $messageRecordDao->get($data['id']);
            $message_record->save(['status'=> 1, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $message_record->send_count+1]);
            return true;
        }else{
            //test_log('执行发送sms-失败');
            $messageRecordDao = app()->make(MessageRecordDao::class);
            $message_record = $messageRecordDao->get($data['id']);
            $message_record->save(['status'=> 2, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $message_record->send_count+1]);
            return false;
        }
    }

    /**
     * 同步发送消息（异步任务里调用）
     * @param string $tempkey 模板key
     * @param array $message_data 消息内容数据
     * @param array $param_data 消息替换变量数据
     * @param string $ismerge 是否合并：0=否，1=是
     * @return bool
     */
    public function syncMessage($tempkey, $message_data, $param_data, $ismerge = 0)
    {
        try {
            $template = app()->make(TemplateMessageServices::class)->get(['tempkey'=> $tempkey]);
            if($template == null) {
                return false;
            }
            $content = $template['content'];
            if($content == '') {
                return false;
            }
            foreach($param_data as $k => $v) {
                $content = str_replace('{{'. $k .'}}', $v, $content);
            }
            $record_data = [
                'tempkey'=> $tempkey,
                'receive_id'=> $message_data['receive_id'],
                'receive'=> $message_data['receive'],
                'phone'=> $message_data['phone'],
                'content'=> $content,
                'source_id'=> $message_data['source_id'],
                'ismerge'=> $ismerge,
            ];
            $messageRecordDao = app()->make(MessageRecordDao::class);
            $message_record = $messageRecordDao->save($record_data);
            if($ismerge == 1) { //等待合并，暂时不发
                return true;
            }
            //测试不发
            // if(Config::get('app.app_host') == 'dev'){
            //     return true;
            // }
            $record_data['id'] = $message_record->id;
            $smsTool = new SmsTool();
            $res = $smsTool->sendSms($record_data['phone'], $record_data['content']);
            if($res['status'] == 1) {
                $messageRecordDao->update($record_data['id'], ['status'=> 1, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $message_record->send_count+1]);
            }else{
                $messageRecordDao->update($record_data['id'], ['status'=> 2, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $message_record->send_count+1]);
            }
            return true;
        }catch (\Throwable $e){
            test_log('syncMessage发生错误:'. $e->getMessage());
            return false;
        }
    }
}
