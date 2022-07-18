<?php

namespace app\model\system\admin;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;
use think\facade\Db;

class SystemAdmin extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_admin';

    protected $autoWriteTimestamp = true;

    // 追加属性
    protected $append = [
        'role_code', // 尽量不要用 append 追加，除特殊的
    ];
    /**
     * 权限数据
     * @param $value
     * @return false|string[]
     */
    public static function getRolesAttr($value)
    {
        return explode(',', $value);
    }

    public static function getRoleCodeAttr($value, $data)
    {
        if(isset($data['role_id']) && $data['role_id'] > 0){
            $role= Db::name('system_role')->where('id','=',$data['role_id'])->find();
            if($role){
                return $role['role_code'];
            }
        }
        return '';
    }

    /**
     * 管理员级别搜索器
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchLevelAttr($query, $value)
    {
        if (is_array($value)) {
            $query->where('level', $value[0], $value[1]);
        } else {
            $query->where('level', $value);
        }
    }

    /**
     * 管理员账号和姓名搜索器
     * @param Model $query
     * @param $value
     */
    public function searchAccountLikeAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('account|real_name', '%' . $value . '%');
        }
    }

    /**
     * 管理员账号搜索器
     * @param Model $query
     * @param $value
     */
    public function searchAccountAttr($query, $value)
    {
        if ($value) {
            $query->where('account', $value);
        }
    }
    
    /**
     * 管理员权限搜索器
     * @param Model $query
     * @param $roles
     */
    public function searchRolesAttr($query, $roles)
    {
        if ($roles) {
            $query->where("CONCAT(',',roles,',')  LIKE '%,$roles,%'");
        }
    }

}
