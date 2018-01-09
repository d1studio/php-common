<?php
namespace d1studio\common\util;
/**
 * 时间helper
 * @author chengtao<chengtao@51cto.com>
 */
class TimeUtil{
    /**
     * 获取时间,精度毫秒
     * @return float
     */
    public static function getMicroTime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}