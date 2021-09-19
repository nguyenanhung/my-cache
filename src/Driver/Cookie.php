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
 * Class Cookie
 *
 * A cookie driver to store non-sensitive scalar (only) data. Limited storage up to 4Ko.
 *
 * @package   nguyenanhung\MyCache\Driver
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Cookie extends Cache
{
    protected $driverConfig = [];

    /**
     * Cookie constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger->setLoggerSubPath(__CLASS__);
        try {
            $this->cacheInstance = CacheManager::getInstance('cookie', new Config($this->driverConfig));
        } catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log for Cookie Cache |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = null;
        }
    }

    /**
     * Function setDriverConfig
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/19/2021 20:45
     */
    public function setDriverConfig(): Cookie
    {
        $this->driverConfig = [];

        return $this;
    }
}
