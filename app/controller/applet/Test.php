<?php

namespace app\controller\applet;

use crmeb\basic\BaseController;
use app\services\ZzsbViewServices;

class Test extends BaseController
{
    public function csm_ryxx()
    {
        $id_card = request()->param('id_card');
        $data = app()->make(ZzsbViewServices::class)->csm_ryxx_db($id_card);
        return show(200,'ok',$data);
    }

}

