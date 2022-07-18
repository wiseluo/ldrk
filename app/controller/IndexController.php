<?php

namespace app\controller;

use think\facade\Db;
use app\dao\OwnDeclareOcrDao;
use \behavior\SgzxTool;
use app\services\CarDeclareServices;
use think\facade\Config;
use EasyWeChat\Factory;
use app\services\ZzsbViewServices;
use app\services\applet\PlaceServices;
use \behavior\SsjptTool;
use \behavior\FaceTool;
use behavior\SsjptActualTool;
use Curl\Curl;

class IndexController
{

    public function testcar(){
        app()->make(CarDeclareServices::class)->getTravelFromApi(['id'=>30,'plate_number'=>'豫CR9816']);
    }



    public function xcxQrcode(){

		$file_dir_name = '1';
        $space_code = 'aabbcc';

		$file_dir = app()->getRootPath() .'public/appcode/'.$file_dir_name;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }
		$appcode_image = $file_dir.'/'.$space_code.'_qrcode.png';
		$appcode_image_url = 'appcode/'.$file_dir_name.'/'.$space_code.'_qrcode.png';
		if(!file_exists($appcode_image)){
			// $config = get_addon_config('wanlshop');

            $app_id = Config::get('wechat.applet_app_id');
            $appsecret = Config::get('wechat.applet_app_secret');

			$app = Factory::miniProgram(['app_id'=>$app_id,'secret'=>$appsecret]);
			$response = $app->app_code->get('pages/home/home?space_code='.$space_code);
			$filename = $response->saveAs($file_dir,$space_code.'_home_v3.png');

		}
    }

    public function findHsjc(){
        $param = request()->param();
        $real_name = $param['real_name'];
        $id_card = $param['id_card'];

        $szxTool = new SgzxTool();
        $data =  $szxTool->getByshsjcjk($real_name, $id_card);

        return show(200,'msg',$data);
        // dump($data);
    }

    public function test(){
        $data = Db::name('risk_district')->where('id','>',0)->select()->toArray();
        var_dump($data);
    }

    public function tmpBuildAppletCodeCmd(){
        $param = request()->param();
        $limit = isset($param['limit']) ? $param['limit'] : 1;
        $data = app()->make(PlaceServices::class)->buildAppletCodeAndGetImage($limit);
        var_dump($data);
    }

    public function zzsb_view()
    {
        $lastid = request()->param('lastid');
        $data = app()->make(ZzsbViewServices::class)->zzsb_view_db($lastid);
        return show(200,'ok',$data);
    }

    public function csm_ryxx()
    {
        $id_card = request()->param('id_card');
        $data = app()->make(ZzsbViewServices::class)->csm_ryxx_db($id_card);
        return show(200,'ok',$data);
    }

    public function proxyapi_jkmss(){
        $param = request()->param();
        $curl = new Curl();
        $url = 'https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/M3dfkN6d88mWekf4.htm';
        $curl->post($url, $param);
        $curl->close();
        return $curl->response;
    }

    public function sk_token()
    {
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->getSkRequestToken();
        if($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function token_actual()
    {
        $ssjptTool = new SsjptActualTool();
        $res =  $ssjptTool->getSkRequestToken();
        if($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function face_token()
    {
        $ssjptTool = new FaceTool();
        $res =  $ssjptTool->getFaceRequestToken();
        if($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function dddddd(){

        $list = app()->make(OwnDeclareOcrDao::class)->getUnrecognizedOrc();
        foreach($list as $k => $v) {
            if($v['travel_img'] != ''){
                $v['travel_content'] = str_replace('市','市||',$v['travel_content']);
                $v['travel_content'] = str_replace('市||，','市,',$v['travel_content']);
                $v['travel_content'] = str_replace('市||,','市,',$v['travel_content']);
                echo $v['travel_content'].'<br>';
                //echo "<img width='200px' src='https://yqfk.yw.gov.cn/".$v['travel_img']."' />";     
                $sss = $this->_dddd($v);
                $travel_time = $this->_time($v);
                echo "【【【【【【";
                echo $sss;
                echo "】】】】】】<br>";
                echo $travel_time;
                echo "】】】】】】<br>";
                
            }

            // SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleUnrecognizedOrcService', 'param'=> $v])->push();
        }
        // return show(200, '已触发'. count($list));


    }

    private function _dddd($data){
        $match = '/内[^：]+：(.*?)[|]{2}/';
        $route_res = preg_match($match, $data['travel_content'], $route);
        $travel_route = '';
        if($route_res) {
            $travel_route = $route[1];
        }
        return $travel_route;
    }

    private function _time($data)
    {
        //2021.11.0713：41：11
        $match = '/\d{4}\.\d{2}\.\d{4}：\d{2}：\d{2}/';
        $route_res = preg_match($match, $data['travel_content'], $route);
        $travel_time = '';
        if($route_res) {
            $travel_time = $route[0];
        }
        var_dump($travel_time);
        $travel_time = str_replace(".", "-", $travel_time);
        $travel_time = str_replace("：", ":", $travel_time);
        $travel_time = strtotime($travel_time);
        if($travel_time) {
            $travel_time = date('Y-m-d H:i:s', $travel_time);
        }
        return $travel_time;
    }

}