<?php 

$projectId = "1111564305";
//项目密钥
$projectSecret = "55ea54dac3f497c43344a9904f4aa1ae";
//根据ssotoken获取法人信息地址
$apiUrl = "http://essotest.zjzwfw.gov.cn:8080/rest/user/query"; //文档中的测试url
//$apiUrl = "https://118.178.119.84:443/rest/user/query";//demo中的测试url
//$apiUrl = "https://ssoapi.zjzwfw.gov.cn/rest/user/query";

header("location:/enterpriseadmin/login?redirect=%2Fenterpriseadmin%2Fhome%2F%3Fauth_code%3D".$_POST['ssotoken']);
echo "- - - - 获取ssotoken  - - - - <br />";
$ssotoken = $_POST['ssotoken'];
echo "ssotoken：".$ssotoken."<br/>";
//请求body
$param = array(
    'token' => $ssotoken
);
$date = json_encode($param);
//计算请求签名值
$signature = getSignature($date,$projectSecret);
echo "signature:".$signature."<br/>";
echo "请求地址：".$apiUrl."<br/>";
echo "请求参数：".$date."<br/>";
$array = http_post_data($apiUrl, $date, $projectId, $signature);
print_r($array);

//模拟POST请求根据ssotoken获取法人信息
function http_post_data($url, $data, $projectId, $signature) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("x-esso-project-id:" . $projectId, "x-esso-signature:" . $signature,"Content-Type:application/json"));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return array($return_code, $return_content);

}

//计算请求签名值
function getSignature($message, $projectSecret) {
    $signature = hash_hmac('sha256', $message, $projectSecret, FALSE);
    return $signature;
}

