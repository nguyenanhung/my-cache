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
use Phpfastcache\Drivers\Mongodb\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Mongodb
 *
 * A very high-performance NoSQL driver using a key-value pair system
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Mongodb extends Cache
{
    protected $driverConfig = [
        'host'     => '127.0.0.1',
        'port'     => 27017,
        'username' => '',
        'password' => '',
        'timeout'  => 1,
        /**
         * These ones are
         * totally optional
         */
        // 'collectionName' => 'Cache',
        // 'databaseName' => 'phpFastCache'
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
            $this->cacheInstance = CacheManager::getInstance('mongodb', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for MongoDB |----------------------");
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
     * @param string $collectionName
     * @param string $databaseName
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 31:37
     */
    public function setDriverConfig(string $host = '127.0.0.1', int $port = 27017, string $username = '', string $password = '', int $timeout = 1, string $collectionName = 'Cache', string $databaseName = 'phpFastCache'): Mongodb
    {
        $this->driverConfig = [
            'host'           => $host,
            'port'           => $port,
            'username'       => $username,
            'password'       => $password,
            'timeout'        => $timeout,
            /**
             * These ones are
             * totally optional
             */
            'collectionName' => $collectionName,
            'databaseName'   => $databaseName
        ];

        return $this;
    }
}
