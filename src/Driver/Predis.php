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
use Phpfastcache\Drivers\Predis\Config;
use nguyenanhung\MyCache\Cache;

/**
 * Class Predis
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Predis extends Cache
{
    protected $driverConfig = [
        'host'     => '127.0.0.1', //Default value
        'port'     => 6379, //Default value
        'password' => NULL, //Default value
        'database' => NULL, //Default value
    ];

    /**
     * Predis constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('predis', new Config($this->driverConfig));
        }
        catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Predis |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = NULL;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @param string $host
     * @param int    $port
     * @param null   $password
     * @param null   $database
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 23:32
     */
    public function setDriverConfig($host = '127.0.0.1', $port = 6379, $password = NULL, $database = NULL)
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