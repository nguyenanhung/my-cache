<?php
/**
 * Project my-cache
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/01/2021
 * Time: 15:11
 */

namespace nguyenanhung\MyCache\Driver;

use Exception;
use Phpfastcache\CacheManager;
use Phpfastcache\Drivers\Cassandra\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Cassandra
 *
 * A very high-performance NoSQL driver using a key-value pair system. Please note that the Driver rely on php's Datastax extension: https://github.com/datastax/php-driver
 *
 * @see       https://github.com/datastax/php-driver
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Cassandra extends Cache
{
    protected $driverConfig = [
        'host'       => '127.0.0.1',
        'port'       => 9042,
        'username'   => '',
        'password'   => '',
        'timeout'    => 2,
        'sslEnabled' => false,
        'sslVerify'  => false
    ];


    /**
     * Mongodb constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('cassandra', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Cassandra |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string $host
     * @param int    $port
     * @param string $username
     * @param string $password
     * @param int    $timeout
     * @param false  $sslEnabled
     * @param false  $sslVerify
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 36:12
     */
    public function setDriverConfig(string $host = '127.0.0.1', int $port = 27017, string $username = '', string $password = '', int $timeout = 2, bool $sslEnabled = false, bool $sslVerify = false): Cassandra
    {
        $this->driverConfig = [
            'host'     => $host,
            'port'     => $port,
            'username' => $username,
            'password' => $password,
            'timeout'  => $timeout,

            'sslEnabled' => $sslEnabled,
            'sslVerify'  => $sslVerify
        ];

        return $this;
    }
}
