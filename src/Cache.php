<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/12/18
 * Time: 11:42
 */

namespace nguyenanhung\MyCache;

use phpFastCache\CacheManager;
use phpFastCache\Core\phpFastCache;
use nguyenanhung\MyCache\Interfaces\ProjectInterface;
use nguyenanhung\MyCache\Interfaces\CacheInterface;

if (!interface_exists('nguyenanhung\MyCache\Interfaces\ProjectInterface')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'ProjectInterface.php';
}

/**
 * Class Cache
 *
 * @package   nguyenanhung\MyCache
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Cache implements ProjectInterface, CacheInterface
{
    /**
     * Cache constructor.
     */
    public function __construct()
    {
    }

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 11:47
     *
     * @return mixed|string
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}
