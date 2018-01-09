<?php
namespace d1studio\common\util;

class FileUtil{
    /**
     * 向文件追加一行的方法，需要锁定文件并且追加内容
     * @param $file_name string 文件名
     * @param $line      string 追加数据
     */
    public static function appendLine($file_name,$line){
        file_put_contents($file_name, $line."\n", FILE_APPEND | LOCK_EX);
    }
}
