<?php

namespace app;

 use Spatie\Macroable\Macroable;

/**
 * Class Request
 * @package app
 * @method tokenData() 获取token信息
 * @method user(string $key = null) 获取用户信息
 * @method id() 获取用户id
 * @method isAdminLogin() 后台登陆状态
 * @method adminId() 后台管理员id
 * @method adminInfo() 后台管理信息
 * @method kefuId() 客服id
 * @method kefuInfo() 客服信息
 */
class Request extends \think\Request
{
     use Macroable;

    /**
     * 获取请求的数据
     * @param array $params
     * @param bool $suffix
     * @return array
     */
    public function more(array $params, bool $suffix = false): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $this->param($param);
            } else {
                if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';
                if (is_array($param[0])) {
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }
                $p[$suffix == true ? $i++ : (isset($param[3]) ? $param[3] : $keyName)] = $this->param($name, $param[1], $param[2]);
            }
        }
        return $p;
    }

    /**
     * 获取get参数
     * @param array $params
     * @param bool $suffix
     * @return array
     */
    public function getMore(array $params, bool $suffix = false): array
    {
        return $this->more($params, $suffix);
    }

    /**
     * 获取post参数
     * @param array $params
     * @param bool $suffix
     * @return array
     */
    public function postMore(array $params, bool $suffix = false): array
    {
        return $this->more($params, $suffix);
    }

    /**
     * 获取用户访问端
     * @return array|string|null
     */
    public function getFromType()
    {
        return $this->header('Form-type', '');
    }

    /**
     * 当前访问端
     * @param string $terminal
     * @return bool
     */
    public function isTerminal(string $terminal)
    {
        return strtolower($this->getFromType()) === $terminal;
    }

    /**
     * 是否是H5端
     * @return bool
     */
    public function isH5()
    {
        return $this->isTerminal('h5');
    }

    /**
     * 是否是微信端
     * @return bool
     */
    public function isWechat()
    {
        return $this->isTerminal('wechat');
    }

    /**
     * 是否是小程序端
     * @return bool
     */
    public function isRoutine()
    {
        return $this->isTerminal('routine');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isApp()
    {
        return $this->isTerminal('app');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isPc()
    {
        return $this->isTerminal('pc');
    }

    /**
     * 获取ip
     * @return string
     */
    public function ip(): string
    {
        if ($this->server('HTTP_CLIENT_IP', '')) {
            $ip = $this->server('HTTP_CLIENT_IP', '');
        } elseif ($this->server('HTTP_X_REAL_IP', '')) {
            $ip = $this->server('HTTP_X_REAL_IP', '');
        } elseif ($this->server('HTTP_X_FORWARDED_FOR', '')) {
            $ip = $this->server('HTTP_X_FORWARDED_FOR', '');
            $ips = explode(',', $ip);
            $ip = $ips[0];
        } elseif ($this->server('REMOTE_ADDR', '')) {
            $ip = $this->server('REMOTE_ADDR', '');
        } else {
            $ip = '0.0.0.0';
        }
        return $ip;
    }
}
