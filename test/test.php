<?php
/**
 * Project my-cache
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/04/2020
 * Time: 20:57
 */
require_once __DIR__ . '/../vendor/autoload.php';

use nguyenanhung\MyCache\Cache;

if (!function_exists('testSendRequestOnCache')) {
    /**
     * Function testSendRequestOnCache
     *
     * @param string              $url
     * @param string|array|object $data
     * @param string              $method
     *
     * @return bool|string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 04/21/2020 17:40
     */
    function testSendRequestOnCache(string $url = '', $data = '', string $method = 'GET')
    {
        $endpoint = (!empty($data) && (is_array($data) || is_object($data))) ? $url . '?' . http_build_query($data) : $url;
        $method   = strtoupper($method);
        $curl     = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => "",
            CURLOPT_HTTPHEADER     => array(),
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        }

        return $response;
    }
}

$storagePath      = realpath(__DIR__ . '/storage');
$logsPath         = realpath(__DIR__ . '/storage/logs');
$cachePath        = realpath(__DIR__ . '/storage/cache');
$cacheSecurityKey = 'Web-Build';
$cacheChmod       = 0777;
$cacheKeyHash     = 'md5';
$cache            = new Cache();
$cache->setDebugStatus(true)
      ->setDebugLevel('info')
      ->setDebugLoggerPath($logsPath)
      ->setCachePath($cachePath)->setCacheTtl(500)
      ->setCacheDriver('files')->setCacheSecurityKey($cacheSecurityKey)
      ->setCacheDefaultChmod($cacheChmod)->setCacheDefaultKeyHashFunction($cacheKeyHash)
      ->__construct();

echo "<pre>";
print_r($cache->getDebugStatus());
echo "</pre>" . PHP_EOL;
echo "<pre>";
print_r($cache->getDebugLevel());
echo "</pre>" . PHP_EOL;
echo "<pre>";
print_r($cache->getDebugLoggerPath());
echo "</pre>" . PHP_EOL;
echo "<pre>";
print_r($cache->getCachePath());
echo "</pre>" . PHP_EOL;
echo "<pre>";
print_r($cache->getCacheDriver());
echo "</pre>" . PHP_EOL;
echo "<pre>";
print_r($cache->getCacheSecurityKey());
echo "</pre>" . PHP_EOL;


echo $storagePath . "\n";
echo $cachePath . "\n";
echo $cache->getVersion() . PHP_EOL;

$bloggerId  = '6346344800454653614';
$alt        = 'json';
$maxResults = 1;
$url        = 'https://www.blogger.com/feeds/' . trim($bloggerId) . '/posts/summary?alt=' . $alt . '&max-results=' . $maxResults;
$url3       = 'https://www.blogger.com/feeds/' . trim($bloggerId) . '/posts/summary?alt=' . $alt . '&max-results=2';
$cacheId    = md5($url);
$cacheId2   = md5($url3);

echo $cacheId . PHP_EOL;


echo "<pre>";
print_r($cache->has($cacheId));
echo "</pre>";

//Create Cache
if ($cache->has($cacheId)) {
    $data = $cache->get($cacheId);
} else {
    $data = testSendRequestOnCache($url);
    $cache->save($cacheId, $data);
}
echo $data;
//Create Cache
if ($cache->has($cacheId)) {
    $data2 = $cache->get($cacheId);
} else {
    $data2 = testSendRequestOnCache($url3);
    $cache->save($cacheId, $data2);
}
echo $data2;

// Delete Cache
//if ($cache->has($cacheId)) {
//    echo "<pre>";
//    print_r($cache->delete($cacheId));
//    echo "</pre>";
//}

// Clean Cache
//echo "<pre>";
//print_r($cache->clean());
//echo "</pre>";





