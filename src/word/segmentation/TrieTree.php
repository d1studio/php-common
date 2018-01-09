<?php
namespace d1studio\common\word\segmentation;
/**
 * PHP方式分词
 * @author chengtao<751753158@qq.com>
 */
class TrieTree extends Base{

    /**
     * 字典树缓存
     * @var array
     */
    private static  $map = [];

    /**
     * @inheritdoc
     */
    public function run($string){
        $map = $this->getTrieTree();
        $result = $this->longStrInMap($string,$map);
        return $result;
    }

    private function getTrieTree(){
        if(!self::$map){
            $ban_word_list = explode("\n",file_get_contents(__DIR__.'/../../dist/ban_words.txt'));
            $map = array();
            foreach($ban_word_list as $key=> $word) {
                $wordArray = $this->stringSplitUtf8(trim($word));
                $this->mkMapSub($wordArray, $map);
            }
            self::$map = $map;
        }
        return self::$map;
    }


    /**
     * @param $str
     * @return array
     */
    function stringSplitUtf8($str){
        $split = 1;
        $array = array();
        $str_len = strlen($str);
        for ($i = 0; $i < $str_len;) {
            $value = ord($str[$i]);
            if ($value > 127) {
                if ($value >= 192 && $value <= 223) {
                    $split = 2;
                } elseif ($value >= 224 && $value <= 239) {
                    $split = 3;
                } elseif ($value >= 240 && $value <= 247) {
                    $split = 4;
                }
            } else {
                $split = 1;
            }
            $key = null;
            for ($j = 0; $j < $split; $j++, $i++) {
                $key .= $str[$i];
            }
            array_push($array, $key);
        }
        return $array;
    }
    /*
        递归的将str_split_utf8生成的数组查到字典树中
    */
    function mkMapSub($wordArray, &$mapSubArray)
    {
        $length = count($wordArray);
        if ($length > 0) {
            // 取出第一个字
            $word = $wordArray[0];
            // 查看字典树中是否匹配第一个字,没有的话就插入这个字
            if (!isset($mapSubArray[$wordArray[0]])) {
                $mapSubArray[$wordArray[0]] = array();
            }
            if($length == 1)
            {// 只有一个字，就插入一个结束标识
                $mapSubArray[$wordArray[0] . "end"] = 1;
            }
            // 已经插入的字出栈
            array_shift($wordArray);
            // 递归插入
            $this->mkMapSub($wordArray, $mapSubArray[$word]);
        }
    }

    /*
        递归查询词是否在字典树中
    */
    function wordInMap($str, $map){
        while(true){
            if (count($str) == 1) {
                if (isset($map[$str[0]]) && $map[$str[0]] == array()){
                    return true;
                } else if(isset($map[$str[0] . "end"]) && $map[$str[0] . "end"] == 1 ) {
                    return true;
                }else{
                    return false;
                }
            } else {
                // 按字递归查找
                $word = array_shift($str);
                if (isset($map[$word])) {
                    $map =  $map[$word];
                } else {
                    return false;
                }
            }
        }
    }
    /**
     *  查找一个句子中是否在字典树中有词命中
     */
    function longStrInMap($str, $map){
        if($this->wordInMap($str,$map)) {
            return array($str);
        }
        $strArray = $this->stringSplitUtf8($str);
        $length = count($strArray);
        $hasList = array();
        foreach ($strArray as $index => $word){
            if (isset($map[$word])) {
                //尝试测试子数组
                for ($subIndex = $index; $subIndex <= $length; $subIndex++) {
                    $subStr = array_slice($strArray,$index,$subIndex-$index);
                    if($this->wordInMap($subStr,$map)) {
                        $hasList[] = $subStr;
                    }
                }
            }
        }
        $retArray = array();
        foreach($hasList as $v) {
            $retArray[] = implode("",$v);
        }
        return $retArray;
    }
}