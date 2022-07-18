<?php

namespace app\services;

use \behavior\RkkTool;
use \behavior\SgzxTool;
use app\dao\UserDao;

//数管中心接口服务
class SgzxServices
{
    //人口库查询
    public function rkkService($real_name, $id_card)
    {
        // return ['status' => 1,'msg'=>'暂时放行','data'=>[]];
        $rkkTool = new RkkTool();
        $hj_res = $rkkTool->getByYWHJRK($real_name, $id_card);
        if($hj_res['status'] == 1) {
            return $hj_res;
        }else{
            $sn_res = $rkkTool->getBySNRK($real_name, $id_card);
            if($sn_res['status'] == 1) {
                return $sn_res;
            }else{
                // $zz_res = $this->service->getByYWZZRK($real_name, $id_card);
                // if($zz_res['status']) {
                //     return ['status'=> 200, 'msg'=> $zz_res['msg'], 'data'=> $zz_res['data']];
                // }else{
                    return $rkkTool->getByQGRKK($real_name, $id_card);
                // }
            }
        }
    }

    //全省健康码基本信息查询
    public function qsjkmxxcx($id_card)
    {
        $szxTool = new SgzxTool();
        return $szxTool->getByqsjkmxxcx($id_card);
    }
    // {
    //     "sfzh": "身份证号",
    //     "id": "f1974518c8ab1c89270bc873e0755a94",
    //     "xm": "骆智超",
    //     "sjh": "手机号",
    //     "mffd": "金华市",
    //     "mlx": "本域内通行码",
    //     "mzt": "绿码",
    //     "hmcmyy": "",
    //     "scffsj": "2020-02-20T04:28:03.000+0000",
    //     "scsqsj": "2020-02-20T04:28:03.000+0000",
    //     "zjlx": "IDENTITY_CARD",
    //     "zjgxsj": "2021-11-09T09:04:54.000+0000",
    //     "source": "阿里自建alipay",
    //     "dbrsfzh": null
    // }

    //省新冠疫苗预防接种信息查询
    public function sxgymyfjzxxcx($id_card)
    {
        $szxTool = new SgzxTool();
        return $szxTool->getBysxgymyfjzxxcx($id_card);
    }
    // [{
    //     "caseCode": "3307823101198935151",
    //     "vaccinationUnit": "330782031001",
    //     "vaccinationTypeName": "非免疫规划疫苗",
    //     "vaccineProducer": "02",
    //     "vacNameCode": "5601",
    //     "birthDate": "2020-12-12 00:00:00",
    //     "genderCode": "1",
    //     "mobilePhone": "手机号",
    //     "vaccinationType": "04",
    //     "residentialAddress": "地址",
    //     "recipientName": "姓名",
    //     "vaccinationTime": 1,
    //     "vaccinationDateTime": "2021-09-12 00:00:00",
    //     "recipientIdNumber": "身份证号",
    //     "vaccineBatch": "2021061265",
    //     "tabuCode": null,
    //     "vacName": "新冠疫苗（Vero细胞）",
    //     "vaccineProducerName": "北京生物",
    //     "idTypeCode": "01",
    //     "vaccinationUnitName": "苏溪镇中心卫生院预防接种门诊"
    // },
    // {
    //     "caseCode": "3307823101198935152",
    //     "vaccinationUnit": "330782031001",
    //     "vaccinationTypeName": "非免疫规划疫苗",
    //     "vaccineProducer": "02",
    //     "vacNameCode": "5601",
    //     "birthDate": "1989-10-02 00:00:00",
    //     "genderCode": "1",
    //     "mobilePhone": "手机号",
    //     "vaccinationType": "04",
    //     "residentialAddress": "浙江省.金华市.义乌市.苏溪镇.蒋宅村",
    //     "recipientName": "姓名",
    //     "vaccinationTime": 2,
    //     "vaccinationDateTime": "2021-09-11 00:00:00",
    //     "recipientIdNumber": "身份证号",
    //     "vaccineBatch": "202107B1963",
    //     "tabuCode": null,
    //     "vacName": "新冠疫苗（Vero细胞）",
    //     "vaccineProducerName": null,
    //     "idTypeCode": "01",
    //     "vaccinationUnitName": "苏溪镇中心卫生院预防接种门诊"
    // }]

    //省核酸检测接口
    public function shsjcjk($real_name, $id_card)
    {
        $szxTool = new SgzxTool();
        return $szxTool->getByshsjcjk($real_name, $id_card);
    }
    // [{
    //     "jgmc": "机构名称",
    //     "reportid": "491156682",
    //     "checktime": "2021-10-16 14:25:35",
    //     "examinaim": "新型冠状病毒RNA检测",
    //     "sex": "1",
    //     "checkername": "检验人姓名",
    //     "patientname": "被检测人姓名",
    //     "result": "阴性",
    //     "idtype": "01",
    //     "sampledescribe": "鼻咽拭子",
    //     "sfzh": "身份证号",
    //     "ckfw": "阴性",
    //     "jgdm": "47177271X33078211C2101",
    //     "logdate": "2021-10-16 14:52:49",
    //     "requestername": "姓名",
    //     "receivetime": "2021-10-16 11:12:30"
    // }]
    
    //义乌核酸检测接口
    // public function ywhsjcjk($real_name, $id_card)
    // {
    //     $szxTool = new SgzxTool();
    //     // return $szxTool->getByywhsjcjk($real_name, $id_card);
    //     return $szxTool->getByshsjcjk($real_name, $id_card);
    // }

    //手机号查询健康码信息
    // public function phoneToJkm($phone){
    //     $szxTool = new SgzxTool();
    //     return $szxTool->phoneToJkm($phone);
    // }

    //手机号查询健康码信息v2
    // public function phoneToJkmV2($phone){
    //     $szxTool = new SgzxTool();
    //     return $szxTool->phoneToJkmV2($phone);
    // }
    
    //查询企业信息
    public function enterpriseInfo($credit_code)
    {
        $szxTool = new SgzxTool();
        return $szxTool->enterpriseInfo($credit_code);
    }
}
