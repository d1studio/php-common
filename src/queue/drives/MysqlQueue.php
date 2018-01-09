<?php
/**
 * @author chengtao<751753158@qq.com>
 */
namespace d1studio\common\queue\drives;

use d1studio\common\queue\Message;
use PDO;

/**
 * 队列驱动-MySQL
 *
 * 说明 :
 * 1 mysql 驱动使用了mysql的自增ID作为消息存储的唯一标示,因此依赖mysql数据表的自增ID的最大值 4,294,967,295 42亿多
 * 2 如果消息预估大于这个mysql驱动不能够使用
 *
 * 使用场景:
 * 1 没有其他中间件
 * 2 数据量较小
 *
 * Interface TaskInterface
 */
class MysqlQueue extends BaseQueue{

    const STATUS_NATURE    = 0; //新建状态
    const STATUS_POP       = 1; //出队列状态
    const STATUS_SUCCESS   = 2; //成功状态
    const STATUS_ERROR     = 3; //失败状态

    /**
     * 数据库实例
     * @var null
     */
    private $db = null;
    /**
     * 队列对应的表名称
     * @var string
     */
    private $queue_table_name = '';
    /**
     * 默认配置
     * @var array
     */
    private $default_config = [
        'database_type' => 'mysql',
        'charset'       => 'utf8',
        'port'          => 3306,
    ];
    /**
     * @inheritdoc
     */
    public function init(){
        if(!$this->tableExist()){
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->queue_table_name}` (
                    `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
                    `priority` TINYINT(1) NOT NULL,
                    `data` TEXT NOT NULL,
                    `create_time` VARCHAR(45) NOT NULL DEFAULT 0,
                    `pop_time` int NOT NULL DEFAULT 0,
                    `ack_time` int NOT NULL DEFAULT 0,
                    `status` TINYINT(1) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE = InnoDB  DEFAULT CHARACTER SET = utf8";
            $this->db->exec($sql);
        }
    }
    /**
     * MysqlQueue constructor.
     * @param string $queue_name
     * @param array $config
     */
    public function __construct($queue_name, array $config){
        parent::__construct($queue_name, $config);
        $db_config = array_merge($this->default_config,$this->config);
        $this->db = new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s;port=%s;charset=%s;",
                $db_config['server'],
                $db_config['database_name'],
                $db_config['port'],
                $db_config['charset']
            ),
            $db_config['username'],
            $db_config['password']
        );
        $this->queue_table_name = 'queue_'.$queue_name;
    }
    /**
     * @inheritdoc
     */
    public function getLength(){
        if(!$this->tableExist()){
            throw new \Exception('table不存在'.$this->queue_table_name);
        }
        $sql   = "SELECT COUNT(*) as `count` FROM {$this->queue_table_name} where status = 0";
        $info  = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $info['count'];
    }
    /**
     * @inheritdoc
     */
    public function pop(){
        $result = false;
        $this->db->exec('begin');
        $sql_base = "SELECT * FROM %s WHERE status=0 ORDER BY id LIMIT 1 FOR UPDATE ";
        $sql = sprintf($sql_base,$this->queue_table_name);
        $row  = $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            $time = time();
            $sql = "UPDATE {$this->queue_table_name} set `status`=1 ,`pop_time`={$time} where id = {$row['id']}";
            $flag = $this->db->exec($sql);
            if ($flag) {
                $this->db->query('commit');
                $data = json_decode($row['data'],true);
                $result = new Message($data['data'],$data['ttl']);
                $result->setId($row['id']);
                $result->setQueue($this);
            } else {
                $this->db->exec('rollback');
            }
        } else {
            $this->db->exec('rollback');
        }
        return $result;
    }
    /**
     * 进入队列
     * @param string $data
     * @param int    $ttl
     * @throws \Exception
     */
    public function push($data,$ttl = 1 ){
        if(!is_string($data)){
            throw new \Exception("入队列必须是字符串");
        }
        if(!is_numeric($ttl)){
            throw new \Exception("TTL必须是数字");
        }
        if($ttl>=1){
            $sql = "INSERT INTO {$this->queue_table_name} (data,create_time,priority) VALUES (:data,:create_time,:priority)";
            $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY))->execute([
                ':data'        => json_encode(['data'=>$data,'ttl'=>$ttl],JSON_UNESCAPED_UNICODE),
                ':create_time' => time(),
                ':priority'    => 1,
            ]);
        }
    }
    public function ack($id,$flag = true){
        $time = time();
        if($flag){
            $status = 2;
        }else{
            $status = 3;
        }
        $sql = "UPDATE {$this->queue_table_name} set `status`=$status ,`ack_time`={$time} where id = {$id}";
        $this->db->exec($sql);
    }

    public function isEmpty(){
        return $this->getLength() == 0 ;
    }
    private function tableExist(){
        $sql   = "show tables like '{$this->queue_table_name}'";
        $list  = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return count($list) > 0;
    }
}