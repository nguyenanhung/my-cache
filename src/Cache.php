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
    private $benchmark;
    /** @var null|object */
    private $cacheInstance;
    /** @var array|mixed */
    private $cacheHandle;
    /** @var null|string */
    private $cacheDriver = NULL;
    /** @var null|string */
    private $cachePath = NULL;
    /** @var int */
    private $cacheTtl = 500;
    /** @var null|string */
    private $cacheSecurityKey;
    /** @var null|string|int */
    private $cacheDefaultChmod;
    /** @var null|string */
    private $cacheDefaultKeyHashFunction;
    /** @var object \nguyenanhung\MyDebug\Debug */
    private $logger;
    /** @var bool */
    private $debugStatus = FALSE;
    /** @var bool|string */
    private $debugLevel = FALSE;
    /** @var null|string */
    private $loggerPath = NULL;

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
        $this->cacheHandle = array(
            'path'                   => $this->cachePath,
            "itemDetailedDate"       => FALSE,
            'fallback'               => self::DEFAULT_DRIVERS,
            'defaultChmod'           => !empty($this->cacheDefaultChmod) ? $this->cacheDefaultChmod : 0777,
            'defaultKeyHashFunction' => !empty($this->cacheDefaultKeyHashFunction) ? $this->cacheDefaultKeyHashFunction : 'md5'
        );
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
            $this->logger->error(__FUNCTION__, "----------------------| Trace Error Log |----------------------\n" . $e->getTraceAsString());
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
     * @return mixed|string
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 11:47
     *
     */
    public function getVersion()
    {
        return self::VERSION;
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
     * Function getCacheInstance
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
     * Function getCacheHandle
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
    public function setCachePath($cachePath = NULL)
    {
        $this->cachePath = $cachePath;
        $this->logger->debug(__FUNCTION__, 'setCachePath: ', $this->cachePath);

        return $this;
    }

    /**
     * Function getCachePath
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
     * Function getCacheTtl
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
     * Function getCacheDriver
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
     * Function getCacheSecurityKey
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
     * Function getCacheDefaultChmod
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
     * Function getCacheDefaultKeyHashFunction
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
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /** @var object $cache */
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                $this->logger->debug(__FUNCTION__, 'Unavailable cacheInstance');

                return FALSE;
            }
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
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /**
                 * @var $cache object
                 */
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
                $this->logger->debug(__FUNCTION__, 'Unavailable cacheInstance');

                return NULL;
            }
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
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /** @var $cache object */
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
                $this->logger->debug(__FUNCTION__, 'Unavailable cacheInstance');

                return NULL;
            }
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
     * @param string|array $key
     *
     * @return null|string  True if the request resulted in a cache hit. False otherwise.
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 20:02
     */
    public function delete($key = '')
    {
        try {
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /** @var $cache object */
                if (is_array($key)) {
                    $result = $this->cacheInstance->deleteItems($key);
                } else {
                    $result = $this->cacheInstance->deleteItem($key);
                }
                $message = 'Final Delete Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->logger->debug(__FUNCTION__, $message);

                return $result;
            } else {
                $this->logger->debug(__FUNCTION__, 'Unavailable cacheInstance');

                return NULL;
            }
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
     * @return bool|string|array Trả về TRUE trong trường hợp thành công | Error String nếu có lỗi Exception
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 15:28
     */
    public function clean()
    {
        try {
            $result = array(
                'result'        => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->clear() : FALSE,
                'commit'        => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->commit() : NULL,
                'getDriverName' => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getDriverName() : NULL,
                'getStats'      => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getStats() : NULL,
                'getConfig'     => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getConfig() : NULL
            );
            $this->logger->debug(__FUNCTION__, 'Clear Cache from Handler: ' . json_encode($this->cacheHandle) . ' -> Result: ' . json_encode($result));

            return $result;
        }
        catch (Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            if (function_exists('log_message')) {
                log_message('error', 'Error Message: ' . $e->getMessage());
                log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
            }
            $this->logger->error(__FUNCTION__, 'Error Message: ' . $e->getMessage());
            $this->logger->error(__FUNCTION__, 'Error Trace As String: ' . $e->getTraceAsString());

            return $message;
        }
    }
}
