<?php
/**
 * Created by PhpStorm.
 * User: chengtao
 * Date: 2018/1/9
 * Time: 下午7:39
 */

namespace d1studio\common\command\widget;

/**
 * Class TableRender
 * @package d1studio\memcache_tools
 * @author chengtao<751753158@qq.com>
 */
class TableRender{
    /**
     * 表头
     * @var array
     */
    public  $header = [];
    /**
     * 表数据
     * @var array
     */
    public  $data = [];
    /**
     * 列的个数
     * @var int
     */
    private $column_count = 0;
    /**
     * 列的宽度
     * @var array
     */
    private $max_width = [];
    /**
     * 渲染表的分割线
     */
    public function renderLine(){
        for($i=0;$i<$this->column_count;$i++){
            echo '+';
            echo str_pad('',$this->max_width[$i]+2,'-');
        }
        echo "+\n";
    }
    /**
     * 渲染表格
     */
    public function render(){
        $list         = array_merge([$this->header],$this->data);
        $this->column_count = count($this->header);
        $this->max_width    = array_fill(0, $this->column_count, 0);
        for($i=0;$i<$this->column_count;$i++){
            foreach($list as $v){
                $this->max_width[$i] = max($this->max_width[$i],strlen($v[$i]));
            }
        }
        $this->renderLine();
        foreach($list as $k => $item){
            echo '|';
            for($i=0;$i<$this->column_count;$i++) {
                echo str_pad(str_pad($item[$i], $this->max_width[$i], ' '),$this->max_width[$i]+2,' ',STR_PAD_BOTH);
                echo "|";
            }
            echo "\n";
            if($k == 0){
                $this->renderLine();
            }
        }
        $this->renderLine();
    }
}