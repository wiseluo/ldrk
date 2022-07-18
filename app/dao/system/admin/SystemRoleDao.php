<?php

namespace app\dao\system\admin;

use app\dao\BaseDao;
use app\dao\gov\GovDao;
use app\model\system\admin\SystemRole;

/**
 * Class SystemRoleDao
 * @package app\dao\system\admin
 */
class SystemRoleDao extends BaseDao
{
    /**
     * 设置模型名
     * @return string
     */
    protected function setModel(): string
    {
        return SystemRole::class;
    }

    /**
     * 获取权限
     * @param string $field
     * @param string $key
     * @return mixed
     */
    public function getRoule(array $where = [], ?string $field = null, ?string $key = null)
    {
        return $this->search($where)->column($field ?: 'role_name', $key ?: 'id');
    }

    /**
     * 获取身份列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRouleList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->select()->toArray();
    }

    public function getRoleSelectList($param=[])
    {
        $where = [];
        
        if(isset($param['gov_id']) && $param['gov_id'] > 0){
            $govDao = app()->make(GovDao::class);
            $gov = $govDao->get($param['gov_id']);
            if($gov['level'] == 1){
                if( $gov['role_codes'] != '' ){
                    $role_codes_arr = explode(',',$gov['role_codes']);
                    // 卫健局 todo 及下面的。可能要上正式后具体确定
                    $where[] = ['role_code','in',$role_codes_arr];
                }else{
                    // 局级别
                    $where[] = ['role_code','in',['ju_fuze','ju_ganbu']];
                }
            }
            if($gov['level'] == 2){
                if( $gov['role_codes'] != '' ){
                    $role_codes_arr = explode(',',$gov['role_codes']);
                    // 卫健局 todo 及下面的。可能要上正式后具体确定
                    $where[] = ['role_code','in',$role_codes_arr];
                }else{
                    // 所级别
                    $where[] = ['role_code','in',['suo_fuze','suo_ganbu']];
                }
            }
        }

        return $this->getModel()::where('isadmin', 1)
            ->field('id,role_name,level')
            ->where($where)
            ->select()
            ->toArray();
    }
}
