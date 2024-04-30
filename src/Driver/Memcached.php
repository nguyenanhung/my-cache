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
use Phpfastcache\Drivers\Memcached\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Memcached
 *
 * The Memcached driver. A memory cache for regular performances. Do not cross this driver with Memcache driver
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Memcached extends Cache
{
    const CLASS_NAME = 'MemcachedCache';
    protected $driverConfig = [
        'host' => '127.0.0.1',
        'port' => 11211,
        // 'sasl_user' => false, // optional
        // 'sasl_password' => false // optional
    ];

    /**
     * Memcached constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('memcached', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(
                __FUNCTION__,
                "----------------------| Trace Error Log for Memcached |----------------------"
            );
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param  string  $host
     * @param  int  $port
     * @param  bool  $saslUser
     * @param  bool  $saslPassword
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 27:01
     */
    public function setDriverConfig(
        string $host = '127.0.0.1',
        int $port = 11211,
        bool $saslUser = false,
        bool $saslPassword = false
    ): Memcached {
        $this->driverConfig = [
            'host' => $host,
            'port' => $port,
            'sasl_user' => $saslUser,
            'sasl_password' => $saslPassword
        ];

        return $this;
    }
}
