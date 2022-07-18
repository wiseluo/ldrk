<?php

namespace crmeb\basic;
use app\Request;
use think\Model;
use think\helper\Str;

class BaseAuth extends BaseStorage
{
    const AUTH_CRMEB = 'CRMEB';

    public function __construct()
    {
        parent::__construct('BaseAuth');
    }
    /**
     * 初始化
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config)
    {
        // $this->templateIds = Config::get($this->configFile . '.stores.' . $this->name . '.template_id', []);
    }

    public function getSearchData(array $withSearch,$model){
        $with = [];
        $whereKey = [];
        $respones = new \ReflectionClass($model);
        foreach ($withSearch as $fieldName) {
            $method = 'search' . Str::studly($fieldName) . 'Attr';
            if ($respones->hasMethod($method)) {
                $with[] = $fieldName;
            } else {
                $whereKey[] = $fieldName;
            }
        }
        return [$with, $whereKey];
    }
    /**
     * 获取当前句柄名
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    // /**
    //  * 登陆
    //  * @param Request $request
    //  * @return mixed
    //  */
    // abstract public function login(Request $request);

    // /**
    //  * 退出登陆
    //  * @param Request $request
    //  * @return mixed
    //  */
    // abstract public function logout(Request $request);

}
