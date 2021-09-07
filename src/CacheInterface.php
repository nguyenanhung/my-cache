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
    const VERSION               = '3.0.0';
    const LAST_MODIFIED         = '2021-09-07';
    const AUTHOR_NAME           = 'Hung Nguyen';
    const AUTHOR_WEB            = 'https://nguyenanhung.com/';
    const AUTHOR_EMAIL          = 'dev@nguyenanhung.com';
    const PROJECT_NAME          = 'My Cache by HungNG';
    const USE_BENCHMARK         = false;
    const DEFAULT_TTL           = 1800;
    const DEFAULT_DRIVERS       = 'files';
    const DEFAULT_CHMOD         = 0777;
    const DEFAULT_SECURITY_KEY  = 'eEcVrlXMKq3xqEuZg4bhuY295gSpCI3z';
    const IGNORE_SYMFONY_NOTICE = true;

    /**
     * Function getVersion
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 14:10
     */
    public function getVersion();

    /**
     * Function getProjectStatus
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 18:29
     */
    public function getProjectStatus();

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
    public function setDebugStatus($debugStatus = false);

    /**
     * Function getDebugStatus
     *
     * @return bool
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:02
     */
    public function getDebugStatus();

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
    public function setDebugLevel($debugLevel = false);

    /**
     * Function getDebugLevel
     *
     * @return bool|string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:07
     */
    public function getDebugLevel();

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
    public function setDebugLoggerPath($loggerPath = null);

    /**
     * Function getDebugLoggerPath
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:58
     */
    public function getDebugLoggerPath();

    /**
     * Function getCacheInstance - Hàm cấu hình cache Instance
     *
     * @return object|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 21:59
     */
    public function getCacheInstance();

    /**
     * Function getCacheHandle - Hàm lấy ra cấu hình cache
     *
     * @return array|mixed
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 23:45
     */
    public function getCacheHandle();

    /**
     * Function setCachePath - Cấu hình thư mục lưu trữ cache
     *
     * @param string|null $cachePath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:43
     */
    public function setCachePath($cachePath = null);

    /**
     * Function getCachePath - Hàm lấy ra thư mục lưu trữ file cache mặc định
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:15
     */
    public function getCachePath();

    /**
     * Function setCacheTtl - Cầu hình TTL cho file Cache
     *
     * @param null $cacheTtl
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:49
     *
     */
    public function setCacheTtl($cacheTtl = null);

    /**
     * Function getCacheTtl - Hàm lấy ra cấu hình thời hạn cache
     *
     * @return int
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:42
     */
    public function getCacheTtl();

    /**
     * Function setCacheDriver - Hàm cấu hình cache drivers
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
     * Function getCacheDriver - Hàm lấy ra cấu hình cache drivers
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:49
     */
    public function getCacheDriver();

    /**
     * Function setCacheSecurityKey - Hàm cấu hình mã an toàn khi encode cache
     *
     * @param string|null $cacheSecurityKey
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheSecurityKey($cacheSecurityKey = null);

    /**
     * Function getCacheSecurityKey - Hàm lấy ra cấu hình mã an toàn khi encode cache
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:58
     */
    public function getCacheSecurityKey();

    /**
     * Function setCacheDefaultChmod - Hàm cấu hình phân quyền file cache
     *
     * @param null|int|string $cacheDefaultChmod
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultChmod($cacheDefaultChmod = null);

    /**
     * Function getCacheDefaultChmod - Hàm lấy ra cấu hình phân quyền file cache
     *
     * @return int|string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 34:06
     */
    public function getCacheDefaultChmod();

    /**
     * Function setCacheDefaultKeyHashFunction - Hàm cấu hình thuật toán mã hóa ID Cache
     *
     * @param null|string $cacheDefaultKeyHashFunction
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction = null);

    /**
     * Function getCacheDefaultKeyHashFunction - Hàm lấy ra cấu hình thuật toán mã hóa ID Cache
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 34:11
     */
    public function getCacheDefaultKeyHashFunction();

    /**
     * Function has - Kiểm tra sự tồn tại dữ liệu cache
     *
     * @param string $key
     *
     * @return bool|string True if the request resulted in a cache hit. False otherwise.
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 18:10
     */
    public function has($key = '');

    /**
     * Function get - Hàm lấy dữ liệu cache
     *
     * @param string $key
     *
     * @return bool|mixed|string|null
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 18:09
     */
    public function get($key = '');

    /**
     * Function save - Hàm Save Cache
     *
     * @param string $key   Key Cache
     * @param string $value Dữ liệu cần cache
     *
     * @return mixed|string|array|object Dữ liệu đầu ra
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 14:37
     */
    public function save($key = '', $value = '');

    /**
     * Function delete - Hàm Delete Cache
     *
     * @param string $key
     *
     * @return bool|string|null  True if the request resulted in a cache hit. False otherwise.
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 03/18/2021 28:13
     */
    public function delete($key = '');

    /**
     * Function clean - Hàm Clean Cache
     *
     * @return null|string|array Trả về TRUE trong trường hợp thành công | Error String nếu có lỗi Exception
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 15:28
     */
    public function clean();
}
