<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../classmap.php';
require_once __DIR__ . '/../functions.php';
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/12/18
 * Time: 13:42
 */
$cache = new \nguyenanhung\MyCache\Cache();
$cache->setDebugStatus(TRUE);
$cache->setDebugLevel(NULL);
$cache->setDebugLoggerPath(testLogPath());
$cache->__construct();
$cache->setCachePath(testCachePath());
$cache->setCacheTtl(3600);
$cache->setCacheDriver('apcu');
$key_test   = 'test-key';
$value_test = [
    'status' => 0,
    'desc'   => 'Test',
    'data'   => "a"
];
dump($cache->simpleCache($key_test, $value_test));
//dump($cache->cleanCache());