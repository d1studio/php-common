<?php
namespace d1studio\common\word\segmentation;
/**
 * PHP方式分词
 * @author chengtao<751753158@qq.com>
 */
abstract class Base{
    /**
     * 分词
     * @param $string
     * @return array
     */
    abstract function run($string);
}