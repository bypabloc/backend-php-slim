<?php

namespace App\Services;

class Logger
{
    private static $file_name;

    private static function init(
        $file_name = 'log',
    ){
        self::$file_name = $file_name;
    }

    public static function info(
        $message = '',
        $file = '',
    ) : void
    {
        self::init();

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtrace = $backtrace[0];

        if(empty($file)){
            $file = self::$file_name;
        }
        $file_name = $file . '.log';

        $path_main = str_replace("src/Services", "", __DIR__);

        $file_path = $path_main . 'Storage/logs/';
        if (!file_exists($file_path)) {
            mkdir($file_path, 0777, true);
        }
        $file_path = $file_path . $file_name;

        $file = str_replace($path_main, "", $backtrace['file']);
        
        $content = '';
        $content .= '[' . date('Y-m-d H:i:s') . '] ';
        $content .= '[info] ';
        $content .= '[' . $_SERVER['REMOTE_ADDR'] . '] ';
        $content .= '[' . $file . '] ';
        $content .= '[' . $backtrace['line'] . '] ';

        $content .= '[' . $message . '] ';

        // $content .= "\n";

        try {
            $file_content = file_get_contents($file_path);

            $file_content = $content . PHP_EOL . $file_content;
            
            file_put_contents($file_path, $file_content, LOCK_EX);

        } catch (\Throwable $th) {
            echo $th->getMessage();
            echo $th->getFile();
            echo $th->getLine();
        }
    }
}
