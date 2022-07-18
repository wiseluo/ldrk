<?php
namespace crmeb\services;

class QrcodeService
{

    public static function getQrcodeBase64($codeUrl, $colorCode='')
    {
        return UtilService::getQrcodeBase64($codeUrl, $colorCode);
    }

}
