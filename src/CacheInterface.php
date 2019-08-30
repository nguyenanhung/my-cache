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
    const DEFAULT_TTL           = 500;
    const DEFAULT_DRIVERS       = 'files';
    const DEFAULT_SECURITY_KEY  = 'gZALHz7d5urLL3mDKUZHPzkaHxcDdCgn';
    const DEFAULT_CHMOD         = 0777;
    const IGNORE_SYMFONY_NOTICE = TRUE;

    /**
     * Function setDebugStatus
     *
     * @param bool|string|null $debugStatus
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 17:39
     */
    public function setDebugStatus($debugStatus = FALSE);

    /**
     * Function setDebugLevel
     *
     * @param bool|string|null $debugLevel
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:02
     */
    public function setDebugLevel($debugLevel = FALSE);

    /**
     * Function setDebugLoggerPath
     *
     * @param string|null $loggerPath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:21
     */
    public function setDebugLoggerPath($loggerPath = NULL);

    /**
     * Function setCachePath
     *
     * Cấu hình thư mục lưu trữ cache
     *
     * @param string|null $cachePath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:43
     */
    public function setCachePath($cachePath = NULL);

    /**
     * Function setCacheTtl
     *
     * Cầu hình TTL cho file Cache
     *
     * @param null $cacheTtl
     *
     * @return $this
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
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:01
     *
     */
    public function setCacheDriver($cacheDriver = '');

    /**
     * Function setCacheSecurityKey
     *
     * @param string|null $cacheSecurityKey
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheSecurityKey($cacheSecurityKey = NULL);

    /**
     * Function setCacheDefaultChmod
     *
     * @param null|int|string $cacheDefaultChmod
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultChmod($cacheDefaultChmod = NULL);

    /**
     * Function setCacheDefaultKeyHashFunction
     *
     * @param null|string $cacheDefaultKeyHashFunction
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction = NULL);

    /**
     * Function has
     *
     * Kiểm tra sự tồn tại dữ liệu cache
     *
     * @param string $key
     *
     * @return bool|string True if the request resulted in a cache hit. False otherwise.
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:10
     *
     */
    public function has($key = '');

    /**
     * Function get
     *
     * Hàm lấy dữ liệu cache
     *
     * @param string $key
     *
     * @return bool|mixed|string|null
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:09
     *
     */
    public function get($key = '');

    /**
     * Function save
     *
     * Hàm Save Cache
     *
     * @param string $key   Key Cache
     * @param string $value Dữ liệu cần cache
     *
     * @return mixed|string|array|object Dữ liệu đầu ra
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
     * Hàm Clean Cache
     *
     * @return bool|string|array
     * Trả về TRUE trong trường hợp thành công
     * Error String nếu có lỗi Exception
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:28
     *
     */
    public function clean();
}
