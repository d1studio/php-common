<?php
/**
 * @author chengtao<751753158@qq.com>
 */
namespace d1studio\common\queue\drives;

use d1studio\common\queue\Message;
use PDO;

/**
 * 队列驱动-Redis
 *
 * 说明 :
 * 1 使用redis的list作为队列的中间件，不支持ack
 *
 * 使用场景:
 * 1 环境中有redis，业务不需要ack
 *
 * Interface TaskInterface
 */
class RedisQueue extends BaseQueue{
    /**
     * @var \Redis
     */
    private $redis;
    /**
     * RedisQueue constructor.
     * @param string $queue_name
     * @param array $config
     */
    public function __construct($queue_name, array $config = []){
        parent::__construct($queue_name, $config);
        $this->redis = new \Redis();
        $this->redis->connect($config['host'],$config['port']);
        $this->queue_name = $config['prefix'].'_'.$queue_name;
    }
    /**
     * 初始化队列
     * @return void
     */
    public function init(){

    }
    /**
     * 出队
     * @return Message|false 队列数据
     */
    public function pop(){
        $data = $this->redis->rPop($this->queue_name);
        if($data){
            $data = json_decode($data,true);
            $result = new Message($data['data'],$data['ttl']);
        }else{
            $result = false;
        }
        return $result;
    }
    /**
     * 入队
     * @param string $data 数据
     * @param int    $ttl  TTL
     * @throws \Exception
     */
    public function push($data,$ttl = 0){
        $push_data = json_encode(['data'=>$data,'ttl'=>$ttl],JSON_UNESCAPED_UNICODE);
        $this->redis->lPush($this->queue_name,$push_data);
    }

    /**
     * 队列是否为空
     * @return boolean
     * @throws \Exception
     */
    public function isEmpty(){
        return $this->getLength() > 0;
    }
    /**
     * 获取队列长度
     * @return int
     * @throws \Exception
     */
    public function getLength(){
        return $this->redis->lLen($this->queue_name);
    }
    public function ack($id,$flag){

    }
}