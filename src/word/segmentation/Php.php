<?php
namespace d1studio\common\word\segmentation;
/**
 * PHP方式分词
 * @author chengtao<751753158@qq.com>
 */
class Php extends Base{
    /**
     * @inheritdoc
     */
    public function run($string){
        $ban_word_list = array_filter(explode("\n",file_get_contents(__DIR__.'/../../dist/ban_words.txt')));
        $result = [];
        foreach ($ban_word_list as $word){
            if(strpos($string,$word) !== false){
                $result[] = $word;
            }
        }
        return $result;
    }
}