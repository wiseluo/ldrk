<?php

namespace app\dao\system\admin;


use app\dao\BaseDao;
use app\model\system\admin\SystemAdmin;

/**
 * admin授权dao
 * Class AdminAuthDao
 * @package app\dao\system\admin
 */
class AdminAuthDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemAdmin::class;
    }

    /*protected function getAdmin($id) {
        // $data = $this->getModel()->where(['id'=>$id])->with(['userGroup','userCategory'])->;
        $data = $this->getModel()->get($id,['*'],['admintoju','admintosuo']);
        return $data;
    }*/

}
