<?php
namespace d1studio\common\word;

use d1studio\common\word\segmentation\Php;
use d1studio\common\word\segmentation\Scws;
use d1studio\common\word\segmentation\TrieTree;

/**
 * PHP方式分词
 * @author chengtao<751753158@qq.com>
 */
class PhpBanWords{
    /**
     * 获取为违禁词列表
     * @param string $string
     * @param string $type    分词类型
     * @return array
     * @throws
     */
    public static function getBanListByString($string,$type='trie_tree'){
        include_once __DIR__ . '/segmentation/Base.php';
        include_once __DIR__ . '/segmentation/Php.php';
        include_once __DIR__ . '/segmentation/TrieTree.php';
        include_once __DIR__ . '/segmentation/Scws.php';
        /* @var $object \d1studio\common\word\segmentation\Base */
        if($type == 'trie_tree'){
            $object = new TrieTree();
        }elseif($type == 'php'){
            $object = new Php();
        }elseif($type == 'scws'){
            $object = new Scws();
        }else{
            throw new \Exception($type.'不存在');
        }
        return $object->run($string);
    }
}