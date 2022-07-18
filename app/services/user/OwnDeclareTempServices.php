<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
use app\dao\OwnDeclareTempDao;
use crmeb\services\SwooleTaskService;
use think\facade\Log;

class OwnDeclareTempServices extends BaseServices
{
    public function __construct(OwnDeclareTempDao $dao)
    {
        $this->dao = $dao;
    }

    public function setDeclareTempService()
    {
        try{
            $uuid = randomCode(6);
            $this->dao->updateLimit(['uuid'=> $uuid], 200);
            $list = $this->dao->getTempList(['uuid'=> $uuid]);
            foreach($list as $v) {
                SwooleTaskService::user()->taskType('user')->data(['action'=>'saveUserByDeclareService','param'=> $v])->push();
                if($v['travel_img'] != '') {
                    SwooleTaskService::aliyun()->taskType('aliyun')->data(['action'=>'imgOcrGeneral','param'=> ['declare_id'=> $v['declare_id'], 'id_card'=> $v['id_card'], 'travel_img'=> $v['travel_img']]])->push();
                }
                $this->dao->delete($v['id']);
            }
            return ['count'=>count($list)];
        } catch (\Exception $e){
            test_log('setDeclareTempService error:'.$e->getMessage());
        }
    }
    
}
