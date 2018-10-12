<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/19/18
 * Time: 13:54
 */
/**
 * Function dump
 *
 * @author: 713uk13m <dev@nguyenanhung.com>
 * @time  : 10/7/18 04:36
 *
 * @param string $str
 */
function dump($str = '')
{
    echo "<pre>";
    var_dump($str);
    echo "</pre>" . PHP_EOL;
}

/**
 * Function testLogPath
 *
 * @author: 713uk13m <dev@nguyenanhung.com>
 * @time  : 10/7/18 04:36
 *
 * @return string
 */
function testLogPath()
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
}

/**
 * Function testCachePath
 *
 * @author: 713uk13m <dev@nguyenanhung.com>
 * @time  : 10/12/18 14:08
 *
 * @return string
 */
function testCachePath()
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
}

