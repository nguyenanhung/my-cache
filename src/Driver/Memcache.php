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
use Phpfastcache\Drivers\Memcache\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Memcache
 *
 * The Memcache driver. A memory cache for regular performances. Do not cross this driver with Memcached driver
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Memcache extends Cache
{
    protected $driverConfig = [
        'host' => '127.0.0.1',
        'port' => 11211,
        // 'sasl_user' => false, // optional
        // 'sasl_password' => false // optional
    ];

    /**
     * Memcache constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('memcache', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Memcache |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string $host
     * @param int    $port
     * @param bool   $saslUser
     * @param bool   $saslPassword
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 27:01
     */
    public function setDriverConfig(string $host = '127.0.0.1', int $port = 11211, bool $saslUser = false, bool $saslPassword = false): Memcache
    {
        $this->driverConfig = [
            'host'          => $host,
            'port'          => $port,
            'sasl_user'     => $saslUser,
            'sasl_password' => $saslPassword
        ];

        return $this;
    }
}
