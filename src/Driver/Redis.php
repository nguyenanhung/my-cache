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
use Phpfastcache\Drivers\Redis\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Redis
 *
 * A very high-performance memory driver using a in-memory data structure storage. More efficient than Predis driver as it is an compiled library
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Redis extends Cache
{
    protected $driverConfig = [
        'host'     => '127.0.0.1', //Default value
        'port'     => 6379, //Default value
        'password' => null, //Default value
        'database' => null, //Default value
    ];

    /**
     * Redis constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('redis', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Redis |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string      $host
     * @param int         $port
     * @param string|null $password
     * @param string|null $database
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 23:32
     */
    public function setDriverConfig(string $host = '127.0.0.1', int $port = 6379, string $password = null, string $database = null): Redis
    {
        $this->driverConfig = [
            'host'     => $host,
            'port'     => $port,
            'password' => $password,
            'database' => $database
        ];

        return $this;
    }
}
