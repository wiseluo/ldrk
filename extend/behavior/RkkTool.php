<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;

//人口库工具类
class RkkTool
{
    protected $nations = [
        '01'=> '汉族','02'=> '蒙古族','03'=> '回族','04'=> '藏族','05'=> '维吾尔族','06'=> '苗族','07'=> '彝族','08'=> '壮族','09'=> '布依族','10'=> '朝鲜族','11'=> '满族','12'=> '侗族','13'=> '瑶族','14'=> '白族','15'=> '土家族','16'=> '哈尼族','17'=> '哈萨克族','18'=> '傣族','19'=> '黎族','20'=> '傈僳族','21'=> '佤族','22'=> '畲族','23'=> '高山族','24'=> '拉祜族','25'=> '水族','26'=> '东乡族','27'=> '纳西族','28'=> '景颇族','29'=> '柯尔克孜族','30'=> '土族','31'=> '达斡尔族','32'=> '仫佬族','33'=> '羌族','34'=> '布朗族','35'=> '撒拉族','36'=> '毛难族','37'=> '仡佬族','38'=> '锡伯族','39'=> '阿昌族','40'=> '普米族','41'=> '塔吉克族','42'=> '怒族','43'=> '乌孜别克族','44'=> '俄罗斯族','45'=> '鄂温克族','46'=> '崩龙族','47'=> '保安族','48'=> '裕固族','49'=> '京族','50'=> '塔塔尔族','51'=> '独龙族','52'=> '鄂伦春族','53'=> '赫哲族','54'=> '门巴族','55'=> '珞巴族','56'=> '基诺族','97'=> '其他','98'=> '外国血统','99'=>'其他2'
    ];

    protected $genders = ['未知', '男', '女'];

    //全国人口库查询
    public function getByQGRKK($real_name, $id_card)
    {
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/hz_qgrkkjzxx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/hz_qgrkkjzxx@1.0";
        }
        $curl->get($url, [
            'JLS' => '1',
            'QQRGMSFHM' => '330782198610275411',
            'QQRXM' => '金正平',
            'DATA' => '<DATA><RECORD no="1"><GMSFHM>'. $id_card .'</GMSFHM><XM>'. $real_name .'</XM></RECORD></DATA>',
            'QQRDWDM' => '12330782MB0485060X',
            'QQRDWMC' => '义乌市数据管理中心',
        ]);
        if ($curl->error) {
            test_log('getByQGRKK-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            //var_dump($res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            $user_data = [
                'id_card' => '',
                'nation' => '汉族',
                'gender' => 0,
                'permanent_address' => '',
            ];
            $datas = $result['datas'];
            $card_res = preg_match('/<GMSFHM&gt;(\d+\w?)<\/GMSFHM&gt;/', $datas, $card);
            if($card_res) {
                $user_data['id_card'] = $card[1];
            }else{
                return ['status'=> 0, 'msg'=> '未查询到该身份证（全国人口库目前只支持工作时间段的查询）'];
            }
            //姓名匹配度代码
            $xmppddm_res = preg_match('/<XM_PPDDM&gt;(\d+)<\/XM_PPDDM&gt;/', $datas, $xmppddm);
            if($xmppddm_res) {
                if($xmppddm[1] != 1) {
                    return ['status'=> -1, 'msg'=> '身份证与姓名不匹配'];
                }
            }else{
                return ['status'=> 0, 'msg'=> '未查询到该身份证'];
            }
            $mzdm_res = preg_match('/<MZDM&gt;(\d+)<\/MZDM&gt;/', $datas, $mzdm);
            if($mzdm_res) {
                $user_data['nation'] = $this->nations[$mzdm[1]];
            }
            $gender_res = preg_match('/<XBDM&gt;(\d+)<\/XBDM&gt;/', $datas, $gender);
            if($gender_res) {
                $user_data['gender'] = $this->genders[$gender[1]];
            }
            $birthday_res = preg_match('/<CSRQ&gt;(\d+)<\/CSRQ&gt;/', $datas, $birthday);
            if($birthday_res) {
                $user_data['birthday'] = $birthday[1];
            }
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $user_data];
        }else{
            return ['status'=> 0, 'msg'=> '外省人员非工作时间，请在早上9:00到下午5:00之间操作'];
        }
    }

    //义乌市公安局户籍人口信息查询
    public function getByYWHJRK($real_name, $id_card)
    {
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/yw_ywhjrkxx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/yw_ywhjrkxx@1.0";
        }
        $curl->get($url, [
            'T_YW_CZRK_SFZH' => $id_card,
        ]);
        if ($curl->error) {
            test_log('getByYWHJRK-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            if(count($result['datas']) == 0) {
                return ['status'=> 0, 'msg'=> '查询为空'];
            }
            $datas = $result['datas'][0];
            if($real_name != $datas['T_YW_CZRK_XM']) {
                return ['status'=> -1, 'msg'=> '身份证与姓名不匹配'];
            }
            $user_data = [
                'id_card' => $datas['T_YW_CZRK_SFZH'],
                'nation' => $datas['T_YW_CZRK_MZ'],
                'permanent_address' => $datas['T_YW_CZRK_JGSSXQ'],
                'birthday' => $datas['T_YW_CZRK_CSRQ'],
                'gender' => $datas['T_YW_CZRK_XB'],
            ];
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $user_data];
        }else{
            return ['status'=> 0, 'msg'=> $result['msg'] .'-户籍'];
        }
    }

    //义乌暂住人口信息查询
    public function getByYWZZRK($real_name, $id_card)
    {
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/yw_gajzzrkxx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/yw_gajzzrkxx@1.0";
        }
        $curl->get($url, [
            'access_token' => 'YjcwNjZkM2VhNmU1N2M5ZmQzYTU1NWQwODc0OGNlYjA=',
            'access' => 'DZZWBJRXT',
            'entityName' => 'T_YW_ZZRK',
            'privateKey' => 'MIIBSwIBADCCASwGByqGSM44BAEwggEfAoGBAP1_U4EddRIpUt9KnC7s5Of2EbdSPO9EAMMeP4C2USZpRV1AIlH7WT2NWPq_xfW6MPbLm1Vs14E7gB00b_JmYLdrmVClpJ-f6AR7ECLCT7up1_63xhv4O1fnxqimFQ8E-4P208UewwI1VBNaFpEy9nXzrith1yrv8iIDGZ3RSAHHAhUAl2BQjxUjC8yykrmCouuEC_BYHPUCgYEA9-GghdabPd7LvKtcNrhXuXmUr7v6OuqC-VdMCz0HgmdRWVeOutRZT-ZxBxCBgLRJFnEj6EwoFhO3zwkyjMim4TwWeotUfI0o4KOuHiuzpnWRbqN_C_ohNWLx-2J6ASQ7zKTxvqhRkImog9_hWuWfBpKLZl6Ae1UlZAFMO_7PSSoEFgIUKeTmaRtyLgE-VBG1ytesV_47_38',
            'SFZH' => $id_card,
            'pageIndex' => '1',
            'pageSize' => '10',
        ]);
        if ($curl->error) {
            test_log('getByYWZZRK-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
        }
        $curl->close();
        try{
            $xml_array = simplexml_load_string($res, null, LIBXML_NOCDATA);
            $json = json_encode($xml_array);
            $result = json_decode($json, true);
            if($result['status'] == "success" && count($result['records']) > 0) {
                $fields = $result['records']['record'][0]['fields'];
                //var_dump($fields);
                if($real_name != $fields['xm']) {
                    return ['status'=> -1, 'msg'=> '身份证与姓名不匹配'];
                }
                $user_data = [
                    'id_card' => $fields['sfzh'],
                    'nation' => $fields['mz'],
                    'permanent_address' => $fields['hksx'],
                    'birthday' => $fields['csrq'],
                    'gender' => $fields['xb'],
                ];
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $user_data];
            }else{
                return ['status'=> 0, 'msg'=> '获取失败-暂住人口'];
            }
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-暂住人口-'. $e->getMessage()];
        }
    }
    
    //省内人口信息查询
    public function getBySNRK($real_name, $id_card)
    {
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/hz_snrkxxcx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/hz_snrkxxcx@1.0";
        }
        $curl->get($url, [
            'cardId' => $id_card,
        ]);
        if ($curl->error) {
            test_log('getBySNRK-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            //var_dump($res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            //var_dump($result);
            if($result['datas'] == null || count($result['datas']) == 0) {
                return ['status'=> 0, 'msg'=> '查询为空'];
            }
            $datas = $result['datas'][0];
            if($real_name != $datas['name']) {
                return ['status'=> -1, 'msg'=> '身份证与姓名不匹配'];
            }
            $user_data = [
                'id_card' => $datas['cardId'],
                'nation' => $datas['nation'],
                'permanent_address' => $datas['birthAddr'],
                'birthday' => $datas['birthday'],
                'gender' => $datas['sex'],
            ];
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $user_data];
        }else{
            return ['status'=> 0, 'msg'=> $result['msg'] .'-省内'];
        }
    }

}
