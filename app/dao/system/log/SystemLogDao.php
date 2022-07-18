<?php

namespace app\dao\system\log;


use app\dao\BaseDao;
use app\model\system\log\SystemLog;

/**
 * 系统日志
 * Class SystemLogDao
 * @package app\dao\system\log
 */
class SystemLogDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemLog::class;
    }

    /**
     * 删除过期日志
     * @throws \Exception
     */
    public function deleteLog()
    {
        $this->getModel()->where('add_time', '<', time() - 7776000)->delete();
    }

    /**
     * 获取系统日志列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLogList(array $where, int $page, int $limit)
    {
        return $this->getModel()::where($where)->page($page, $limit)->order('add_time DESC')->select()->toArray();
    }

    public function getCount(array $where, bool $is_list = false)
    {
        if ($is_list)
            return $this->getModel()->where($where)->group('uid')->fetchSql(true)->column('count(*) as user_count', 'uid');
        else{
            $res = $this->getModel()->where($where)->count();
            return $res;
        }
    }

}
