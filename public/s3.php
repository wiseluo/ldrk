<?php
 
require 'aws.phar'; // 包含AWS SDK文件 aws.phar(只能使用V2版的SDK)
use Aws\S3\S3Client;   //声明使用Aws命名空间中的S3Client类



//天翼云的API服务器
$endpoint = 'http://192.168.216.51:8082';//'http://oos.ctyunapi.cn'; 
 
//Access Key 在天翼云门户网站-帐户管理-API密钥管理中获取
$accessKey = "XXW59B266AM5ZW4IREFZ"; 
 
//Access Secret 在天翼云门户网站-帐户管理-API密钥管理中获取
$accessSecret = "nTlrjl9kV9JJwBbyQHpWPaM2PsWWroMaEKkQuGNw";
 
//创建S3 client 对象
$client = S3Client::factory([
	'endpoint' => $endpoint,  //声明使用指定的endpoint
	'key'      => $accessKey,
	'secret'   => $accessSecret
]);
 
header('content-type:text/plain');
echo 11;
 
//列出所有buckets
$result = $client->listBuckets(array());
foreach ($result['Buckets'] as $bucket) {
	// Each Bucket value will contain a Name and CreationDate
	echo "{$bucket['Name']} - {$bucket['CreationDate']}\n";
}
 
echo "\n\n";
 
//列出指定bucket下所有的object
$bucket = 'swb211106oss1';//'cwz-public';
$result = $client->listObjects(array(
		'Bucket' => $bucket
));
foreach ($result['Contents'] as $object) {
	// Each Bucket value will contain a Name and CreationDate
	echo "{$object['Key']} - {$object['Size']}\n";
}
 
echo "\n\n";
 
//上传一个object
$bucket = 'swb211106oss1'; //'my-public';
$key = 'new.txt';
$body = file_get_contents('new.txt');
$client->upload($bucket, $key, $body);
 
//下载一个object
$result = $client->getObject(array(
		'Bucket' => $bucket,
		'Key'    => 'new.txt'
));
echo $result['Body']; //显示文件对象的内容
