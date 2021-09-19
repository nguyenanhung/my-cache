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
use Phpfastcache\Drivers\Couchbase\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Couchbase
 *
 * A very high-performance NoSQL driver using a key-value pair system
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Couchbase extends Cache
{
    protected $driverConfig = [
        'host'       => '127.0.0.1',
        'port'       => '8091',
        'username'   => 'your-couchbase-username',
        'password'   => 'your-couchbase-password',
        'bucketName' => 'default' // The bucket name, generally "default" by default
    ];


    /**
     * Couchbase constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('couchbase', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Couchbase |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $bucketName
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 40:02
     */
    public function setDriverConfig(string $host = '127.0.0.1', string $port = '8091', string $username = '', string $password = '', string $bucketName = 'default'): Couchbase
    {
        $this->driverConfig = [
            'host'       => $host,
            'port'       => $port,
            'username'   => $username,
            'password'   => $password,
            'bucketName' => $bucketName
        ];

        return $this;
    }
}
