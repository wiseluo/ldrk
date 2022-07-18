<?php

namespace behavior;

class TextTool
{
    /**
     * 数据脱敏
     * @param $string 需要脱敏值
     * @param int $start 开始
     * @param int $length 结束
     * @param string $re 脱敏替代符号
     * @return bool|string
     * 例子:
     * dataDesensitization('18811113683', 3, 4); //188****3683
     * dataDesensitization('乐杨俊', 0, -1); //**俊
     */
    public static function textxx($string, $start = 0, $length = 0, $re = '*')
    {
        if (empty($string)){
            return false;
        }
        $strarr = array();
        $mb_strlen = mb_strlen($string);
        while ($mb_strlen) {//循环把字符串变为数组
            $strarr[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $mb_strlen, 'utf8');
            $mb_strlen = mb_strlen($string);
        }
        $strlen = count($strarr);
        $begin = $start >= 0 ? $start : ($strlen - abs($start));
        $end = $last = $strlen - 1;
        if ($length > 0) {
            $end = $begin + $length - 1;
        } elseif ($length < 0) {
            $end -= abs($length);
        }
        for ($i = $begin; $i <= $end; $i++) {
            $strarr[$i] = $re;
        }
        if ($begin >= $end || $begin >= $last || $end > $last) return false;
        return implode('', $strarr);
    }
}