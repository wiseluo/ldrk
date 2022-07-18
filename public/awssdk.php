<?php
require '../vendor/autoload.php';

use Aws\S3\S3Client;  
use Aws\S3\Exception\S3Exception;

//天翼云的API服务器
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

$file_Path = './new.txt';
$key = basename($file_Path);

// Upload a publicly accessible file. The file size and type are determined by the SDK.
try {
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'Body'   => fopen($file_Path, 'r'),
        'ACL'    => 'public-read', // make file 'public'
    ]);
    echo "Image uploaded successfully. Image path is: ". $result->get('ObjectURL');
} catch (S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e->getMessage();
}

