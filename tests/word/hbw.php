<?php
/**
 * PHP CLI 词库管理脚本
 */
$dist_base64_file     = __DIR__.'/../dist/ban_words_base64.txt';
$dist_file            = __DIR__.'/../dist/ban_words.txt';
$dist_xdb_tmp_file    = __DIR__.'/../dist/ban_words.tmp.txt';
$dist_xdb_file        = __DIR__.'/../dist/ban_words.utf8.xdb';

if(is_file($dist_file)){
    unlink($dist_file);
}
if(is_file($dist_xdb_file)){
    unlink($dist_xdb_file);
}
if(is_file($dist_xdb_tmp_file)){
    unlink($dist_xdb_tmp_file);
}

$file = new SplFileObject($dist_base64_file);
while (!$file->eof()) {
    $str = trim(base64_decode($file->fgets()));
    file_put_contents($dist_file, $str.PHP_EOL, FILE_APPEND | LOCK_EX);
    file_put_contents($dist_xdb_tmp_file, $str."\t1.0\t1.0\tban\n", FILE_APPEND | LOCK_EX);
}

exec("/usr/local/scws/bin/scws-gen-dict -c utf8 -i {$dist_xdb_tmp_file} -o {$dist_xdb_file} ", $output, $return_val);

//if(is_file($dist_xdb_tmp_file)){
//    unlink($dist_xdb_tmp_file);
//}