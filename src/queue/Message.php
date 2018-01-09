<?php
/**
 * @author chengtao<751753158@qq.com>
 */

namespace d1studio\common\queue;
/**
 * 队列消息
 * @package d1studio\php_queue
 */
class Message{
    /**
     * 消息的ID 用于ack
     * @var String
     */
    private $id ;

    /**
     * Queue的引用
     * @var QueueInterface
     */
    private $queue ;
    /**
     * 消息内容
     * @var null|string
     */
    private $data     = null;
    /**
     * 优先级
     * @var int
     */
    private $priority = 0;
    /**
     * 生命周期(retry 机制)
     * @var int
     */
    private $ttl      = 0;
    /**
     * Message constructor.
     * @param string $data 消息数据
     */
    public function __construct($data,$ttl = 0){
        $this->data = $data;
        $this->ttl  = $ttl;
    }
    /**
     * 设置消息ID
     * @param $id
     */
    public function setId($id){
        $this->id = $id;
    }
    /**
     * 设置消息ID
     * @param $queue QueueInterface
     */
    public function setQueue($queue){
        $this->queue = $queue;
    }
    /**
     * 返回消息ID
     * @return String
     */
    public function getId(){
        return $this->id;
    }
    /**
     * 设置ttl
     */
    public function getTtl(){
        return $this->ttl;
    }
    /**
     * @return string
     */
    public function getData(){
        return $this->data;
    }
    /**
     * @param boolean $flag  是否成功
     */
    public function ack($flag = true){
        if($this->id && $this->queue){
            $this->queue->ack($this->id,$flag);
        }
    }
}