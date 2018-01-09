<?php
namespace d1studio\common\util;
/**
 * 数字助手类
 * @author chengtao<751753158@qq.com>
 */
class NumberUtil{
    /**
     * 对字节格式化
     * @param  int    $byte
     * @return string
     */
    public static function formatByte($byte){
        $unit = ['B','KB','MB','GB','TB','PB'];
        $index = 0;
        while($byte>1024){
            $byte /= 1024;
            $index++;
        }
        return number_format($byte,2).$unit[$index];
    }
}
