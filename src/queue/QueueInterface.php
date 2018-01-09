<?php
/**
 * @author chengtao<751753158@qq.com>
 */

namespace d1studio\common\queue;

/**
 *队列类积累
 * Interface TaskInterface
 * @package d1studio\php_queue;
 */
interface QueueInterface {
    /**
     * 初始化队列
     * @return void
     */
    public function init();
    /**
     * 出队
     * @return Message|false 队列数据
     */
    public function pop();
    /**
     * 入队
     * @param string $data 数据
     * @param int    $ttl  TTL
     * @throws \Exception
     */
    public function push($data,$ttl = 0);

    public function ack($id,$flag);

    /**
     * 队列是否为空
     * @return boolean
     * @throws \Exception
     */
    public function isEmpty();
    /**
     * 获取队列长度
     * @return int
     * @throws \Exception
     */
    public function getLength();
}