<?php

namespace crmeb\exceptions;

use Throwable;

/**
 * User应用错误信息
 * @package crmeb\exceptions
 */
class UserException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (is_array($message)) {
            $errInfo = $message;
            $message = $errInfo[1] ?? '未知错误';
            if ($code === 0) {
                $code = $errInfo[0] ?? 400;
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
