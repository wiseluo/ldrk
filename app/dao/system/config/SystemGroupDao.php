<?php

namespace app\dao\system\config;

use app\dao\BaseDao;
use app\model\system\config\SystemGroup;

/**
 * Class SystemGroupDao
 * @package app\dao\system\config
 */
class SystemGroupDao extends BaseDao
{
    /**
     * @return string
     */
    protected function setModel(): string
    {
        return SystemGroup::class;
    }

    /**
     * 获取组合数据分页列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupList(array $where, array $field = ['*'], int $page = 0, int $limit = 0)
    {
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->select()->toArray();
    }

    /**
     * 根据配置名称获取配置id
     * @param string $configName
     * @return mixed
     */
    public function getConfigNameId(string $configName)
    {
        return $this->value(['config_name' => $configName]);
    }
}
