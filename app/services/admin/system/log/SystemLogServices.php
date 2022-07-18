<?php

namespace app\services\admin\system\log;


use app\dao\system\log\SystemLogDao;
use app\services\admin\BaseServices;
use app\services\admin\system\admin\SystemAdminServices;
use app\services\admin\system\SystemMenusServices;

/**
 * 系统日志
 * Class SystemLogServices
 * @package app\services\system\log
 * @method deleteLog() 定期删除日志
 */
class SystemLogServices extends BaseServices
{

    /**
     * 构造方法
     * SystemLogServices constructor.
     * @param SystemLogDao $dao
     */
    public function __construct(SystemLogDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 记录访问日志
     * @param int $adminId
     * @param string $adminName
     * @param string $type
     * @return bool
     */
    public function recordAdminLog(int $adminId, string $adminName, string $type)
    {
        $request = app()->request;
        $module = app('http')->getName();
        $rule = trim(strtolower($request->rule()->getRule()));

        /** @var SystemMenusServices $service */
        $service = app()->make(SystemMenusServices::class);
        $data = [
            'method' => $module,
            'admin_id' => $adminId,
            'add_time' => time(),
            'admin_name' => $adminName,
            'path' => $rule,
            'page' => $service->getVisitName($rule) ?: '未知',
            'ip' => $request->ip(),
            'type' => $type
        ];
        if ($this->dao->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取系统日志列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLogList(array $param, int $level)
    {
        $where = [];
        [$page, $limit] = $this->getPageValue();
 
        if(isset($param['admin_id']) && $param['admin_id']) {
            $where[] = ['admin_id','=',$param['admin_id']];
        }
        if(isset($param['path']) && $param['path']) {
            $where[] = ['path','like',"%".$param['path']."%"];
        }
        if(isset($param['ip']) && $param['ip']) {
            $where[] = ['ip','like',"%".$param['ip']."%"];
        }
        if(isset($param['data']) && $param['data']){
            $today=mktime(0,0,0,date('m'),date('d'),date('Y'));
            $now = time();
            switch($param['data']) {
                case 'today':
                    $where[] = ['add_time','between',[$today,$now]];
                    break;
                case 'yesterday':
                    $yesterday = strtotime(date("Y-m-d",strtotime("-1 day")));
                    $where[] = ['add_time','between',[$yesterday,$today]];
                    break;
                case 'lately7':
                    $week = strtotime(date("Y-m-d",strtotime("-1 week")));
                    $where[] = ['add_time','between',[$week,$now]];
                    break;
                case 'lately30':
                    $month = strtotime(date("Y-m-d",strtotime("last month")));
                    $where[] = ['add_time','between',[$month,$now]];
                    break;
                case 'month':
                    $thismonth = strtotime(date("Y-m-01",time()));
                    $where[] = ['add_time','between',[$thismonth,$now]];
                    break;
                case 'year':
                    $thisyear = strtotime(date("Y-01-01",time()));
                    $where[] = ['add_time','between',[$thisyear,$now]];
                    break;
            }
        }
        $list = $this->dao->getLogList($where, $page, $limit);
        $count = $this->dao->getCount($where);
        return compact('list', 'count');
    }
}
