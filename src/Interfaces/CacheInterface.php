<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/12/18
 * Time: 11:50
 */

namespace nguyenanhung\MyCache\Interfaces;

/**
 * Interface CacheInterface
 *
 * @package   nguyenanhung\MyCache\Interfaces
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface CacheInterface
{
    const DEFAULT_TTL     = 300;
    const DEFAULT_DRIVERS = 'files';

    /**
     * Function setCachePath
     *
     * Cấu hình thư mục lưu trữ cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:46
     *
     * @param null $cachePath
     */
    public function setCachePath($cachePath = NULL);

    /**
     * Function setCacheTtl
     *
     * Cầu hình TTL cho file Cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:49
     *
     * @param null $cacheTtl
     */
    public function setCacheTtl($cacheTtl = NULL);

    /**
     * Function setCacheDriver
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:01
     *
     * @param string $cacheDriver
     */
    public function setCacheDriver($cacheDriver = '');

    /**
     * Function setCacheHandle
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:24
     *
     * @param null $cacheHandle
     */
    public function setCacheHandle($cacheHandle = NULL);

    /**
     * Function simpleCache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:37
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed|string
     */
    public function simpleCache($key = '', $value = '');

    /**
     * Function cleanCache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:28
     *
     * @return bool|string
     */
    public function cleanCache();
}
