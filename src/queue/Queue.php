<?php
/**
 * @author chengtao<751753158@qq.com>
 */

namespace d1studio\common\queue;

use d1studio\php_queue\drives\MysqlQueue;
use d1studio\php_queue\drives\RedisQueue;

/**
 * 队列接口
 * Interface TaskInterface
 * @package d1studio\php_queue;
 */
class Queue {

    /**
     * @param string $queue_name 队列名称
     * @param array  $config     配置文件
     *
     * ========== MySQL ==========
     * [
     *     'drive_name' => 'Mysql',
     *     'options'    => [
     *        'host' ..
     *     ]
     * ]
     * ========== Redis ==========
     * [
     *     'drive_name' => 'Redis',
     *     'options'    => [
     *         'host'   => '', //主机
     *         'port'   => '', //端口
     *         'db'     => '', //redis list
     *         'prefix' => '', //list前缀
     *     ']
     * ]
     *
     * @return QueueInterface
     */
    public static function getQueue($queue_name ,$config){
        if($config['drive'] == 'Mysql'){
            $queue = new MysqlQueue($queue_name,$config['option']);
        }else{
            $queue = new RedisQueue($queue_name,$config['option']);
        }
        return $queue;
    }

}