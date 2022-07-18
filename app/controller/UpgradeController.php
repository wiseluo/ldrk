<?php

namespace app\controller;

use app\Request;
use think\facade\Db;

class UpgradeController
{
    /**
     * 获取当前版本号
     * @return array
     */
    public function getversion($str)
    {
        $version_arr = [];
        $curent_version = @file(app()->getRootPath() . $str);

        foreach ($curent_version as $val) {
            list($k, $v) = explode('=', $val);
            $version_arr[$k] = $v;
        }
        return $version_arr;
    }

    public function index(Request $request)
    {
        $data = $this->upData();
        $Title = "CRMEB升级程序";
        $Powered = "Powered by CRMEB";

        //获取当前版本号
        $version_now = $this->getversion('.version')['version'];
        $version_new = $data['new_version'];
        $isUpgrade = true;
        $executeIng = false;

        return view('/upgrade/step1', [
            'title' => $Title,
            'powered' => $Powered,
            'version_now' => $version_now,
            'version_new' => $version_new,
            'isUpgrade' => json_encode($isUpgrade),
            'executeIng' => json_encode($executeIng),
            'next' => 1,
            'action' => 'upgrade'
        ]);

    }

    public function upgrade(Request $request)
    {
        list($sleep, $page, $prefix) = $request->getMore([
            ['sleep', 0],
            ['page', 1],
            ['prefix', 'eb_'],
        ], true);
        $data = $this->upData();
        $code_now = $this->getversion('.version')['version_code'];
        $sql_arr = [];
        foreach ($data['update_sql'] as $items) {
            if ($items['code'] > $code_now) {
                $sql_arr[] = $items;
            }
        }
        if(!isset($sql_arr[$sleep])){
            file_put_contents(app()->getRootPath() . '.version',"version=".$data['new_version']."\nversion_code=".$data['new_code']);
            return app('json')->successful(['sleep'=>-1]);
        }
        $sql = $sql_arr[$sleep];
        Db::startTrans();
        try {
            if ($sql['type'] == 1) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $findTable;
                        $item['status'] = 1;
                        $item['error'] = $findTable . '表已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    Db::execute($upSql);
                    $item['table'] = $findTable;
                    $item['status'] = 1;
                    $item['error'] = $findTable.'表添加成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->successful($item);
                }
            } elseif ($sql['type'] == 2) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $findTable;
                        $item['status'] = 1;
                        $item['error'] = $findTable . '表不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@findTable', $findTable, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $findTable;
                    $item['status'] = 1;
                    $item['error'] = $findTable.'表删除成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->successful($item);
                }
            } elseif ($sql['type'] == 3) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $findTable;
                        $item['status'] = 1;
                        $item['error'] = $findTable . '表中'.$sql['field'].'已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@findTable', $findTable, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $findTable;
                    $item['status'] = 1;
                    $item['error'] = $findTable.'表中'.$sql['field'].'字段添加成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->successful($item);
                }
            } elseif ($sql['type'] == 4) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $findTable;
                        $item['status'] = 1;
                        $item['error'] = $findTable . '表中'.$sql['field'].'不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@findTable', $findTable, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $findTable;
                    $item['status'] = 1;
                    $item['error'] = $findTable.'表中'.$sql['field'].'字段修改成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->successful($item);
                }
            } elseif ($sql['type'] == 5) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $findTable;
                        $item['status'] = 1;
                        $item['error'] = $findTable . '表中'.$sql['field'].'不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@findTable', $findTable, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $findTable;
                    $item['status'] = 1;
                    $item['error'] = $findTable.'表中'.$sql['field'].'字段删除成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->successful($item);
                }
            } elseif ($sql['type'] == 6) {
                if (isset($sql['findSql'])) {
                    $findTable = $prefix . $sql['findTable'];
                    $findSql = str_replace('@findTable', $findTable, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $prefix . $sql['table'];
                        $item['status'] = 1;
                        $item['error'] = $table . '表中此数据已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
                $table = $prefix . $sql['table'];
                if (isset($sql['sql'])) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    if (isset($sql['whereSql'])) {
                        $whereSql = str_replace('@table', $table, $sql['whereSql']);
                        $tabId = Db::query($whereSql)[0]['tabId'];
                        $upSql = str_replace('@tabId', $tabId, $upSql);
                    }
                    if (Db::execute($upSql)) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = '数据添加成功';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->successful($item);
                    }
                }
            } elseif ($sql['type'] == 7) {

            } elseif ($sql['type'] == 8) {

            }
        } catch (\Throwable $e) {
            $item['table'] = $prefix . $sql['findTable'];
            $item['status'] = 0;
            $item['sleep'] = $sleep + 1;
            $item['add_time'] = date('Y-m-d H:i:s', time());
            $item['error'] = $e->getMessage();
            Db::rollBack();
            return app('json')->successful($item);
        }
    }

    public function upData()
    {
        $data['new_version'] = 'CRMEB-PRO v1.2.2';
        $data['new_code'] = 122;
        $data['update_sql'] = [
            [
                'code' => 121,
                'type' => 1,
                'findTable' => "wechat_keys",
                'findSql' => "select * from information_schema.tables where table_name ='@findTable'",
                'sql' => "CREATE TABLE IF NOT EXISTS `eb_wechat_keys` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `reply_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '回复内容id',
  `keys` varchar(64) NOT NULL DEFAULT '' COMMENT '关键词',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信回复关键词辅助表'"
            ],
            [
                'code' => 121,
                'type' => 2,
                'findTable' => "wechat_keys",
                'findSql' => "select * from information_schema.tables where table_name ='@findTable'",
                'sql' => "DROP TABLE @findTable"
            ],
            [
                'code' => 121,
                'type' => 3,
                'findTable' => "wechat_keys",
                'field' => "aaa",
                'findSql' => "show columns from `@findTable` like 'aaa'",
                'sql' => "ALTER TABLE `@findTable` ADD `aaa` tinyint(1) COMMENT '购物车商品状态'"
            ],
            [
                'code' => 121,
                'type' => 4,
                'findTable' => "wechat_keys",
                'field' => "aaa",
                'findSql' => "show columns from `@findTable` like 'aaa'",
                'sql' => "ALTER TABLE `@findTable` MODIFY COLUMN aaa decimal(10,1) DEFAULT NULL COMMENT '注释'"
            ],
            [
                'code' => 121,
                'type' => 5,
                'findTable' => "wechat_keys",
                'field' => "aaa",
                'findSql' => "show columns from `@findTable` like 'aaa'",
                'sql' => "ALTER TABLE `@findTable` drop COLUMN `aaa`"
            ],
            [
                'code' => 121,
                'type' => 6,
                'findTable' => "wechat_keys",
                'table' => "wechat_keys",
                'findSql' => "select id from @findTable where reply_id = '2'",
                // 'whereSql' => "select id as tabId from @table where reply_id = '1' limit 1",
                'sql' => "INSERT INTO `@table` VALUES (null, 2, 1)"
            ],
        ];
        return $data;
    }

}