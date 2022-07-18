<?php


$MQG        = get_magic_quotes_gpc();
// $reurl		= $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$_GET 		= daddslashes($_GET, $MQG);
$_POST 		= daddslashes($_POST, $MQG);
$_COOKIE 	= daddslashes($_COOKIE, $MQG);
// var_dump($_POST);


var_dump($_SERVER);
var_dump($_GET);



//转义
function daddslashes($string, $force = 0, $strip = FALSE) {
    //如果魔术引用未开启 或 $force不为0
    if($force==0) {
        if(is_array($string)) { //如果其为一个数组则循环执行此函数
            foreach($string as $key => $val) {
                $string[$key] = daddslashes($val, $force);
            }
        } else {
        //这里为什么要将＄string先去掉反斜线再进行转义呢，因为有的时候$string有可能有两个反斜线，stripslashes是将多余的反斜线过滤掉
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}
