<?php

namespace behavior;

//require '../../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Swoole\Coroutine as co;

class AwsTool
{
    /*
     * $filename 文件名
     * $base64_img base64编码图片
     */
    public function AWS_S3Client($filename, $base64_img)
    {
        $endpoint = 'http://192.168.216.51:8082';
        $accessKey = "XXW59B266AM5ZW4IREFZ"; 
        $accessSecret = "nTlrjl9kV9JJwBbyQHpWPaM2PsWWroMaEKkQuGNw";
        $bucket = 'swb211106oss1';

        $s3Client = new S3Client([
            'endpoint' => $endpoint,
            'version' => 'latest',
            'region' => 'us-west-2', //cn
            'credentials' => [
                'key'    => $accessKey,
                'secret' => $accessSecret
            ]
        ]);

        // Upload a publicly accessible file. The file size and type are determined by the SDK.
        // Body : Pass a string containing the body, a handle returned by fopen, or a Guzzle\Http\EntityBodyInterface object
        try {
            co::set(['hook_flags'=> SWOOLE_HOOK_ALL ^ SWOOLE_HOOK_CURL]);
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $filename,
                'Body'   => $base64_img,
                'ACL'    => 'public-read', // make file 'public'
            ]);
            return ['status'=> 1, 'msg'=> '', 'data'=> ['url'=> $result->get('ObjectURL')]];
        } catch (S3Exception $e) {
            return ['status'=> 0, 'msg'=> 'aws error:'. $e->getMessage()];
        }
    }
}