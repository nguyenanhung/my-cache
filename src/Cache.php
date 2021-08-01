<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/12/18
 * Time: 11:42
 */

namespace nguyenanhung\MyCache;

error_reporting(~E_USER_NOTICE);

use Exception;
use Psr\Cache\InvalidArgumentException;
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use nguyenanhung\MyDebug\Debug;
use nguyenanhung\MyDebug\Benchmark;

/**
 * Class Cache
 *
 * @package   nguyenanhung\MyCache
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Cache implements CacheInterface
{
    /** @var object \nguyenanhung\MyDebug\Benchmark */
    protected $benchmark;
    /** @var null|object */
    protected $cacheInstance;
    /** @var array|mixed */
    protected $cacheHandle;
    /** @var null|string */
    protected $cacheDriver = NULL;
    /** @var null|string */
    protected $cachePath = NULL;
    /** @var int */
    protected $cacheTtl = 900;
    /** @var null|string */
    protected $cacheSecurityKey;
    /** @var null|string|int */
    protected $cacheDefaultChmod;
    /** @var null|string */
    protected $cacheDefaultKeyHashFunction;
    /** @var object \nguyenanhung\MyDebug\Debug */
    protected $logger;
    /** @var bool */
    protected $debugStatus = FALSE;
    /** @var bool|string */
    protected $debugLevel = FALSE;
    /** @var null|string */
    protected $loggerPath = NULL;

    /**
     * Cache constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        if (self::USE_BENCHMARK === TRUE) {
            $this->benchmark = new Benchmark();
            $this->benchmark->mark('code_start');
        }
        $this->logger = new Debug();
        $this->logger->setLoggerSubPath(__CLASS__);
        $this->logger->setLoggerFilename('Log-' . date('Y-m-d') . '.log');
        if ($this->debugStatus === TRUE && !empty($this->loggerPath)) {
            $this->logger->setDebugStatus($this->debugStatus);
            $this->logger->setLoggerPath($this->loggerPath);
            if (!empty($this->debugLevel)) {
                $this->logger->setGlobalLoggerLevel($this->debugLevel);
            }
        }
        $this->cacheHandle = [
            'path'                   => $this->cachePath,
            "itemDetailedDate"       => FALSE,
            //'fallback'               => self::DEFAULT_DRIVERS,
            'defaultTtl'             => self::DEFAULT_TTL,
            'defaultChmod'           => !empty($this->cacheDefaultChmod) ? $this->cacheDefaultChmod : 0777,
            'defaultKeyHashFunction' => !empty($this->cacheDefaultKeyHashFunction) ? $this->cacheDefaultKeyHashFunction : 'md5'
        ];
        try {
            CacheManager::setDefaultConfig(new ConfigurationOption($this->cacheHandle));
            if (!empty($this->cacheDriver)) {
                $this->cacheInstance = CacheManager::getInstance($this->cacheDriver);
            } else {
                $this->cacheInstance = CacheManager::getInstance(self::DEFAULT_DRIVERS);
            }
        }
        catch (Exception $e) {
            $this->logger->error(__FUNCTION__, $e->getMessage());
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log |----------------------");
            $this->logger->error(__FUNCTION__, $e->getTraceAsString());
            $this->cacheInstance = NULL;
        }
    }

    /**
     * Cache destructor.
     */
    public function __destruct()
    {
        if (self::USE_BENCHMARK === TRUE) {
            $this->benchmark->mark('code_end');
            $this->logger->debug(__FUNCTION__, 'Elapsed Time: ===> ' . $this->benchmark->elapsed_time('code_start', 'code_end'));
            $this->logger->debug(__FUNCTION__, 'Memory Usage: ===> ' . $this->benchmark->memory_usage());
        }
    }

    /**
     * Function getVersion
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 14:10
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Function getProjectStatus
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/01/2021 18:29
     */
    public function getProjectStatus()
    {
        return [
            'name'              => self::PROJECT_NAME,
            'version'           => self::VERSION,
            'lastModified'      => self::LAST_MODIFIED,
            'defaultTtl'        => self::DEFAULT_TTL,
            'defaultDriver'     => self::DEFAULT_DRIVERS,
            'defaultPermission' => self::DEFAULT_CHMOD,
            'authorName'        => self::AUTHOR_NAME,
            'authorWebsite'     => self::AUTHOR_WEB,
            'authorEmail'       => self::AUTHOR_EMAIL,
        ];
    }

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
    public function setDebugStatus($debugStatus = FALSE)
    {
        $this->debugStatus = $debugStatus;

        return $this;
    }

    /**
     * Function getDebugStatus
     *
     * @return bool
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:02
     */
    public function getDebugStatus()
    {
        return $this->debugStatus;
    }

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
    public function setDebugLevel($debugLevel = FALSE)
    {
        $this->debugLevel = $debugLevel;

        return $this;
    }

    /**
     * Function getDebugLevel
     *
     * @return bool|string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:07
     */
    public function getDebugLevel()
    {
        return $this->debugLevel;
    }

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
    public function setDebugLoggerPath($loggerPath = NULL)
    {
        $this->loggerPath = $loggerPath;

        return $this;
    }

    /**
     * Function getDebugLoggerPath
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 22:58
     */
    public function getDebugLoggerPath()
    {
        return $this->loggerPath;
    }

    /**
     * Function getCacheInstance - Hàm cấu hình cache Instance
     *
     * @return object|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 21:59
     */
    public function getCacheInstance()
    {
        return $this->cacheInstance;
    }

    /**
     * Function getCacheHandle - Hàm lấy ra cấu hình cache
     *
     * @return array|mixed
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 23:45
     */
    public function getCacheHandle()
    {
        return $this->cacheHandle;
    }

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
    public function setCachePath($cachePath = NULL)
    {
        $this->cachePath = $cachePath;
        $this->logger->debug(__FUNCTION__, 'setCachePath: ', $this->cachePath);

        return $this;
    }

    /**
     * Function getCachePath - Hàm lấy ra thư mục lưu trữ file cache mặc định
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:15
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

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
    public function setCacheTtl($cacheTtl = NULL)
    {
        if (!empty($cacheTtl)) {
            $this->cacheTtl = $cacheTtl;
            $this->logger->debug(__FUNCTION__, 'setCacheTtl: ', $this->cacheTtl);
        } else {
            $this->cacheTtl = self::DEFAULT_TTL;
        }

        return $this;
    }

    /**
     * Function getCacheTtl - Hàm lấy ra cấu hình thời hạn cache
     *
     * @return int
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:42
     */
    public function getCacheTtl()
    {
        return $this->cacheTtl;
    }

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
    public function setCacheDriver($cacheDriver = '')
    {
        if ($cacheDriver != self::DEFAULT_DRIVERS && (extension_loaded($cacheDriver))) {
            $this->cacheDriver = $cacheDriver;
            $this->logger->debug(__FUNCTION__, 'Set Cache Driver: ' . json_encode($cacheDriver) . ' and server is supported');
        } else {
            $this->logger->error(__FUNCTION__, 'Set Cache Driver: ' . json_encode($cacheDriver) . ' and server is not supported, user default driver: ' . self::DEFAULT_DRIVERS);
            $this->cacheDriver = self::DEFAULT_DRIVERS;
        }
        $this->logger->debug(__FUNCTION__, 'setCacheDriver: ', $this->cacheDriver);

        return $this;
    }

    /**
     * Function getCacheDriver - Hàm lấy ra cấu hình cache drivers
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:49
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

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
    public function setCacheSecurityKey($cacheSecurityKey = NULL)
    {
        if (!empty($cacheSecurityKey)) {
            $this->cacheSecurityKey = $cacheSecurityKey;
            $this->logger->debug(__FUNCTION__, 'setCacheSecurityKey: ', $this->cacheSecurityKey);
        } else {
            $this->cacheSecurityKey = self::DEFAULT_SECURITY_KEY;
        }

        return $this;
    }

    /**
     * Function getCacheSecurityKey - Hàm lấy ra cấu hình mã an toàn khi encode cache
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:58
     */
    public function getCacheSecurityKey()
    {
        return $this->cacheSecurityKey;
    }

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
    public function setCacheDefaultChmod($cacheDefaultChmod = NULL)
    {
        if (!empty($cacheDefaultChmod)) {
            $this->cacheDefaultChmod = $cacheDefaultChmod;
            $this->logger->debug(__FUNCTION__, 'setCacheDefaultChmod: ', $this->cacheDefaultChmod);
        } else {
            $this->cacheDefaultChmod = self::DEFAULT_CHMOD;
        }

        return $this;
    }

    /**
     * Function getCacheDefaultChmod - Hàm lấy ra cấu hình phân quyền file cache
     *
     * @return int|string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 34:06
     */
    public function getCacheDefaultChmod()
    {
        return $this->cacheDefaultChmod;
    }

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
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction = NULL)
    {
        if (!empty($cacheDefaultKeyHashFunction)) {
            $this->cacheDefaultKeyHashFunction = $cacheDefaultKeyHashFunction;
            $this->logger->debug(__FUNCTION__, 'setCacheDefaultKeyHashFunction: ', $this->cacheDefaultKeyHashFunction);
        } else {
            $this->cacheDefaultKeyHashFunction = '';
        }

        return $this;
    }

    /**
     * Function getCacheDefaultKeyHashFunction - Hàm lấy ra cấu hình thuật toán mã hóa ID Cache
     *
     * @return string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 34:11
     */
    public function getCacheDefaultKeyHashFunction()
    {
        return $this->cacheDefaultKeyHashFunction;
    }

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
    public function has($key = '')
    {
        try {
            if (is_object($this->cacheInstance)) {
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                $this->logger->error(__FUNCTION__, 'cacheInstance is not available');

                return FALSE;
            }
        }
        catch (InvalidArgumentException $exception) {
            $message = 'Error File: ' . $exception->getFile() . ' - Line: ' . $exception->getLine() . ' - Code: ' . $exception->getCode() . ' - Message: ' . $exception->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $exception->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $exception->getTraceAsString());

            return $message;
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }

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
    public function get($key = '')
    {
        try {
            if (is_object($this->cacheInstance)) {
                if (is_array($key)) {
                    $cache = $this->cacheInstance->getItems($key);
                } else {
                    $cache = $this->cacheInstance->getItem($key);
                }
                if (!$cache->isHit()) {
                    $result = NULL;
                    $this->logger->debug(__FUNCTION__, 'Unavailable Cache for Key: ' . $key);
                } else {
                    $result = $cache->get();
                    $this->logger->debug(__FUNCTION__, 'Get Cache from Key: ' . $key . ', result: ' . json_encode($result));
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->logger->debug(__FUNCTION__, $message);

                return $result;
            } else {
                $this->logger->error(__FUNCTION__, 'cacheInstance is not available');

                return NULL;
            }
        }
        catch (InvalidArgumentException $exception) {
            $message = 'Error File: ' . $exception->getFile() . ' - Line: ' . $exception->getLine() . ' - Code: ' . $exception->getCode() . ' - Message: ' . $exception->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $exception->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $exception->getTraceAsString());

            return $message;
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }

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
    public function save($key = '', $value = '')
    {
        try {
            if (is_object($this->cacheInstance)) {
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    $cache->set($value)->expiresAfter($this->cacheTtl);//in seconds, also accepts Datetime
                    $this->cacheInstance->save($cache); // Save the cache item just like you do with doctrine and entities
                    $result = $cache->get();
                    $this->logger->debug(__FUNCTION__, 'Save Cache Key: ' . $key . ' with Value: ' . json_encode($value));
                } else {
                    $result = $cache->get();
                    $this->logger->debug(__FUNCTION__, 'Get Cache from Key: ' . $key . ', result: ' . json_encode($result));
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->logger->debug(__FUNCTION__, $message);

                return $result;
            } else {
                $this->logger->error(__FUNCTION__, 'cacheInstance is not available');

                return NULL;
            }
        }
        catch (InvalidArgumentException $exception) {
            $message = 'Error File: ' . $exception->getFile() . ' - Line: ' . $exception->getLine() . ' - Code: ' . $exception->getCode() . ' - Message: ' . $exception->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $exception->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $exception->getTraceAsString());

            return $message;
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }

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
    public function delete($key = '')
    {
        try {
            if (is_object($this->cacheInstance)) {
                $result = is_array($key) ? $this->cacheInstance->deleteItems($key) : $this->cacheInstance->deleteItem($key);
                $this->logger->debug(__FUNCTION__, 'Final Delete Content from Key: ' . $key . ' => Output result: ' . json_encode($result));

                return $result;
            } else {
                $this->logger->error(__FUNCTION__, 'cacheInstance is not available');

                return NULL;
            }
        }
        catch (InvalidArgumentException $exception) {
            $message = 'Error File: ' . $exception->getFile() . ' - Line: ' . $exception->getLine() . ' - Code: ' . $exception->getCode() . ' - Message: ' . $exception->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $exception->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $exception->getTraceAsString());

            return $message;
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }

    /**
     * Function clean - Hàm Clean Cache
     *
     * @return null|string|array Trả về TRUE trong trường hợp thành công | Error String nếu có lỗi Exception
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 15:28
     */
    public function clean()
    {
        try {
            if (is_object($this->cacheInstance)) {
                $result = array(
                    'result'        => $this->cacheInstance->clear(),
                    'commit'        => $this->cacheInstance->commit(),
                    'getDriverName' => $this->cacheInstance->getDriverName(),
                    'getStats'      => $this->cacheInstance->getStats(),
                    'getConfig'     => $this->cacheInstance->getConfig()
                );
                $this->logger->debug(__FUNCTION__, 'Clear Cache from Handler: ' . json_encode($this->cacheHandle) . ' -> Result: ' . json_encode($result));

                return $result;
            } else {
                $this->logger->error(__FUNCTION__, 'cacheInstance is not available');

                return NULL;
            }
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            if (function_exists('log_message')) {
                // Enabled logging for CodeIgniter Framework
                log_message('error', 'Error Message: ' . $e->getMessage());
                log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
            }
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }
}
