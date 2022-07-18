<?php


namespace crmeb\services;


use Lizhichao\Word\VicWord;

class VicWordService extends VicWord
{

    private static $instance = null;


    public function __construct($dictPath = '')
    {
        parent::__construct($dictPath);
    }

    private function __clone()
    {
    }

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getWord($str)
    {
        $words = $this->getAutoWord($str);
        $data = [];
        $replace_data = ["\r\n", "\r", "\n", "/", "<", ">", "=", " "];
        foreach ($words as $item) {
            if ($item[2] === '0' || is_numeric($item[0])) {
                continue;
            }
            $word = str_replace($replace_data, '', $item[0]);
            if (!$word) continue;
            $data[] = $word;
        }
        $data[] = $str;
        return $data;
    }
}