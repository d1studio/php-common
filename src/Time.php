<?php
namespace d1studio\php_helper;
/**
 * 时间helper
 * @author chengtao<chengtao@51cto.com>
 */
class Time{
    /**
     * 获取时间,精度毫秒
     * @return float
     */
    public static function getMicroTime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}