<?php
namespace d1studio\php_helper;
/**
 * 字符串处理助手类
 * Class String
 * @package d1studio\php_helper
 */
class Str{
    /**
     * 字段文字内容隐藏处理方法
     * @param $string
     * @param int $type   1 身份证 2 手机号 3 银行卡
     * @return string
     */
    public static function hidePrivacyInfo($string,$type=1){
        if(empty($string)) {
            return $string;
        }
        if($type==1){
            $string = substr($string,0,3).str_repeat("*",12).substr($string,strlen($string)-4);//身份证
        }elseif($type==2){
            $string = substr($string,0,3).str_repeat("*",5).substr($string,strlen($string)-4);//手机号
        }elseif($type==3){
            $string = str_repeat("*",strlen($string)-4).substr($string,strlen($string)-4);//银行卡
        }
        return $string;
    }
}