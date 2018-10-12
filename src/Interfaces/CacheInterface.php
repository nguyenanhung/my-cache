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
    const DEFAULT_TTL           = 300;
    const DEFAULT_DRIVERS       = 'files';
    const DEFAULT_SECURITY_KEY  = 'gZALHz7d5urLL3mDKUZHPzkaHxcDdCgn';
    const DEFAULT_CHMOD         = 511;
    const IGNORE_SYMFONY_NOTICE = TRUE;


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
     * Function setCacheSecurityKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheSecurityKey
     */
    public function setCacheSecurityKey($cacheSecurityKey);

    /**
     * Function setCacheDefaultChmod
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheDefaultChmod
     */
    public function setCacheDefaultChmod($cacheDefaultChmod);

    /**
     * Function setCacheDefaultKeyHashFunction
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheDefaultKeyHashFunction
     */
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction);

    /**
     * Function has
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:10
     *
     * @param string $key
     *
     * @return bool|string
     */
    public function has($key = '');

    /**
     * Function get
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:09
     *
     * @param string $key
     *
     * @return bool|mixed|string
     */
    public function get($key = '');

    /**
     * Function save
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:37
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed|string
     */
    public function save($key = '', $value = '');

    /**
     * Function clean
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:28
     *
     * @return bool|string
     */
    public function clean();
}
