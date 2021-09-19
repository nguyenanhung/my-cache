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
use Phpfastcache\Drivers\Couchdb\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Couchdb
 *
 * A very high-performance NoSQL driver using a key-value pair system
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Couchdb extends Cache
{
    protected $driverConfig = [
        'host'     => '127.0.0.1',
        'port'     => 5984,
        'path'     => '/',
        'username' => 'your-couchdb-username',
        'password' => 'your-couchdb-password',
        'ssl'      => true,
        'timeout'  => 10,
    ];

    /**
     * Couchdb constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('couchdb', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Couchdb |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string $host
     * @param int    $port
     * @param string $path
     * @param string $username
     * @param string $password
     * @param bool   $ssl
     * @param int    $timeout
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 42:12
     */
    public function setDriverConfig(string $host = '127.0.0.1', int $port = 5984, string $path = '/', string $username = '', string $password = '', bool $ssl = true, int $timeout = 10): Couchdb
    {
        $this->driverConfig = [
            'host'     => $host,
            'port'     => $port,
            'part'     => $path,
            'username' => $username,
            'password' => $password,
            'ssl'      => $ssl,
            'timeout'  => $timeout
        ];

        return $this;
    }
}
