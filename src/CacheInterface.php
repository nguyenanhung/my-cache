<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/12/18
 * Time: 11:50
 */

namespace nguyenanhung\MyCache;

/**
 * Interface CacheInterface
 *
 * @package   nguyenanhung\MyCache
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface CacheInterface
{
    const DEFAULT_TTL           = 300;
    const DEFAULT_DRIVERS       = 'files';
    const DEFAULT_SECURITY_KEY  = 'gZALHz7d5urLL3mDKUZHPzkaHxcDdCgn';
    const DEFAULT_CHMOD         = 511;
    const IGNORE_SYMFONY_NOTICE = TRUE;

    /**
     * Function setDebugStatus
     *
     * @param bool $debugStatus
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     */
    public function setDebugStatus($debugStatus = FALSE);

    /**
     * Function setDebugLevel
     *
     * @param bool|string|null $debugLevel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     */
    public function setDebugLevel($debugLevel = FALSE);

    /**
     * Function setDebugLoggerPath
     *
     * @param null $loggerPath
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     */
    public function setDebugLoggerPath($loggerPath = NULL);

    /**
     * Function setCachePath
     *
     * Cấu hình thư mục lưu trữ cache
     *
     * @param null $cachePath
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:46
     *
     */
    public function setCachePath($cachePath = NULL);

    /**
     * Function setCacheTtl
     *
     * Cầu hình TTL cho file Cache
     *
     * @param null $cacheTtl
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:49
     *
     */
    public function setCacheTtl($cacheTtl = NULL);

    /**
     * Function setCacheDriver
     *
     * @param string $cacheDriver
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:01
     *
     */
    public function setCacheDriver($cacheDriver = '');

    /**
     * Function setCacheSecurityKey
     *
     * @param $cacheSecurityKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheSecurityKey($cacheSecurityKey);

    /**
     * Function setCacheDefaultChmod
     *
     * @param $cacheDefaultChmod
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultChmod($cacheDefaultChmod);

    /**
     * Function setCacheDefaultKeyHashFunction
     *
     * @param $cacheDefaultKeyHashFunction
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction);

    /**
     * Function has
     *
     * @param string $key
     *
     * @return bool|string
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:10
     *
     */
    public function has($key = '');

    /**
     * Function get
     *
     * @param string $key
     *
     * @return bool|mixed|string
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:09
     *
     */
    public function get($key = '');

    /**
     * Function save
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed|string
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:37
     *
     */
    public function save($key = '', $value = '');

    /**
     * Function delete
     *
     * @param string|array $key
     *
     * @return null|string  True if the request resulted in a cache hit. False otherwise.
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 20:02
     *
     */
    public function delete($key = '');

    /**
     * Function clean
     *
     * @return bool|string
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:28
     *
     */
    public function clean();
}
