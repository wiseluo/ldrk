<?php

namespace app\services\ainat;

use app\dao\AinatCompareDao;
use app\services\ainat\BaseServices;
use crmeb\services\SwooleTaskService;
use behavior\SsjptActualTool;

class NatCompareServices extends BaseServices
{
    public function __construct(AinatCompareDao $dao)
    {
        $this->dao = $dao;
    }

    public function indexService($param)
    {
        return $this->dao->getList($param);
    }

    public function compareService($param)
    {
        $total = $this->dao->getCompareTotalBySign($param['sign']);
        $size = 200;
        $last_page = ceil(bcdiv($total, $size, 2)); //上取整
        for($i=1; $i<=$last_page; $i++) { //批量生成一批异步任务，同时处理比对数据
            SwooleTaskService::ainat()->taskType('ainat')->data(['action'=> 'syncAinatCompare', 'param'=> ['sign'=> $param['sign'], 'page'=> $i, 'size'=> $size]])->push();
        }
        return ['status'=> 1, 'msg'=> '处理中，请稍等'];
    }

    public function compareProgressService($param)
    {
        $data = $this->dao->getCompareProgress($param);
        return ['progress'=> bcmul(bcdiv($data[0]['cur'],$data[0]['max'],2), 100) ];
    }

    public function actualHsjcService($param)
    {
        $ssjptTool = new SsjptActualTool();
        $res = $ssjptTool->skGetHsjcCollectTime($param['id_card'], 0);
        if($res['status'] == 0) {
            return ['status'=> 0, 'msg'=> '查询失败-'. $res['msg']];
        }else{
            if(isset($res['data'][0])) {
                $data = $res['data'][0];
                $time = strtotime($res['data'][0]['collectTime']);
                $data['collect_time'] = date('Y-m-d H:i:s', $time);
                $data['hours_ago'] = bcdiv(time() - $time, 3600);
                return ['status'=> 1, 'msg'=> '查询成功', 'data'=> $data];
            }else{
                return ['status'=> 0, 'msg'=> '查询成功，数据为空'];
            }
        }
    }

}
