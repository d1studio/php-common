<?php
/**
 * @author chengtao<751753158@qq.com>
 */

namespace d1studio\common\queue\drives;

use d1studio\common\queue\QueueInterface;

/**
 * 队列驱动基类
 * Interface TaskInterface
 * @package ctod\core
 */
abstract  class BaseQueue implements QueueInterface{
    /**
     * 队列名称
     * @var string
     */
    protected $queue_name = '';
    /**
     * 配置信息
     * @var array
     */
    protected $config = [];
    /**
     * BaseQueue constructor
     * @param string $queue_name 队列名称
     * @param array  $config     配置
     */
    public function __construct($queue_name,array $config = []){
        $this->queue_name = $queue_name;
        $this->config     = $config;
    }
}