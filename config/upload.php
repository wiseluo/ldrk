<?php

use think\facade\Env;

return [
    //默认上传模式
    'default' => 'local',
    //上传文件大小
    'filesize' => 2097152,
    //上传文件后缀类型
    'fileExt' => ['jpg', 'jpeg', 'gif', 'png' , 'bmp', 'pem', 'doc','docx','key', 'xlsx', 'xls'], // 
    'at_server' => Env::get('upload.at_server', 'dev'),
    //上传文件类型
    'fileMime' => [
        'image/jpeg',
        'image/gif',
        'image/png',
        'text/plain',
        'audio/mpeg',
        'application/octet-stream',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-works',
        'application/vnd.ms-excel',
        'application/zip',
        'application/vnd.ms-excel',
        'application/vnd.ms-excel',
        'text/xml',
    ],
    //驱动模式
    'stores' => [
        //本地上传配置
        'local' => [],
        //七牛云上传配置
        'qiniu' => [],
        //oss上传配置
        'oss' => [],
        //cos上传配置
        'cos' => [],
    ]
];
