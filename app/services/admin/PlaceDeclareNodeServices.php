<?php

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\PlaceDeclareNodeDao;

class PlaceDeclareNodeServices extends BaseServices
{
    public function __construct(PlaceDeclareNodeDao $dao)
    {
        $this->dao = $dao;
    }

    public function indexService()
    {
        $list = $this->dao->getList();
        $first[] = [
            'id'=> 0,
            'describe'=> '最新',
        ];
        return array_merge($first, $list);
    }

}
