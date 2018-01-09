<?php
namespace d1studio\common\word\segmentation;
/**
 * SCWS分词
 * @author chengtao<751753158@qq.com>
 */
class Scws{
    /**
     * @inheritdoc
     */
    public function run($string){
        $scws = \scws_new();
        $scws->set_charset('utf8');
        $scws->send_text($string);
        $scws->set_ignore(true);
        $scws->set_dict(__DIR__.'/../../dist/ban_words.utf8.xdb',SCWS_XDICT_XDB);
        $result = $scws->get_words('ban');
        $list = [];
        if($result){
            foreach($result as $item){
                $list[] = $item['word'];
            }
        }
        return $list;
    }
}