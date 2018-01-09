<?php

use d1studio\common\word\PhpBanWords;
use d1studio\common\command\widget\TableRender;
use d1studio\common\util\TimeUtil;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/word/PhpBanWords.php';

$string = "

工作职责：
-斗米兼职后端业务功能设计及开发 
-负责新项目架构设计和优化
-负责模块结构和流程逻辑的设计和优化 

岗位要求：
-精通lnmp编程，有5年以上项目经验；
-较强的数据库规划和优化能力；
-熟悉Linux操作系统及常用Shell命令，能够在Linux下进行故障排查以及一些数据分析；
-熟练掌握前端技术html/css/js,有html5开发经验者更佳；
-有项目lead经验的优先,大型网站研发经验者优先；
-具备良好的团队协作开发经验，能很好的与PM进行需求沟通，良好的进度控制能力。

毒品 文凭 文凭 文凭 文凭";

$type_list = [
    'trie_tree',
    'php',
    'scws'
];
try {
    $data = [];
    foreach ($type_list as $type) {
        $total_time = 0;
        $count = 3;
        for ($i = 0; $i <= $count; $i++) {
            $start_time = TimeUtil::getMicroTime();
            $list = PhpBanWords::getBanListByString($string, $type);
            $end_time = TimeUtil::getMicroTime();
            $total_time += ($end_time - $start_time);
        }
        $tmp = [
            $type,
            $total_time / $count,
        ];
        $data[] = $tmp;
    }
    $table_render = new TableRender();
    $table_render->header = [
        'type',
        'use_time',
    ];
    $table_render->data = $data;
    $table_render->render();
}catch (Exception $e){
    echo $e;
}


