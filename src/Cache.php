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
use RuntimeException;
use Phpfastcache\Drivers\Predis\Config as PRedisConfig;
use Phpfastcache\Drivers\Redis\Config as RedisConfig;
use Phpfastcache\Drivers\Memcache\Config as MemcacheConfig;
use Phpfastcache\Drivers\Memcached\Config as MemcachedConfig;
use Phpfastcache\Drivers\Cassandra\Config as CassandraConfig;
use Phpfastcache\Drivers\Ssdb\Config as SsdbConfig;
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use nguyenanhung\MyDebug\Logger;
use nguyenanhung\MyDebug\Benchmark;

/**
 * Class Cache
 *
 * Class Cache được customize từ PhpFastCache
 *
 * Support Driver: files, apcu, redis, predis, memcache, memcached, mongodb, cassandra, couchbase, couchdb, leveldb, ssdb, Zend Disk Cache, Zend Memory Cache, Cookie, SqlLite
 *
 * @package   nguyenanhung\MyCache
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Cache
{
    const VERSION = '3.0.9';
    const LAST_MODIFIED = '2024-05-01';
    const AUTHOR_NAME = 'Hung Nguyen';
    const AUTHOR_WEB = 'https://nguyenanhung.com/';
    const AUTHOR_EMAIL = 'dev@nguyenanhung.com';
    const PROJECT_NAME = 'My Cache by HungNG';
    const CLASS_NAME = 'Cache';
    const GITHUB_URL = 'https://github.com/nguyenanhung/my-cache';
    const PACKAGIST_URL = 'https://packagist.org/packages/nguyenanhung/my-cache';
    const USE_BENCHMARK = false;
    const DEFAULT_TTL = 1800;
    const DEFAULT_DRIVERS = 'files';
    const DEFAULT_CHMOD = 0777;
    const DEFAULT_SECURITY_KEY = 'eEcVrlXMKq3xqEuZg4bhuY295gSpCI3z';
    const IGNORE_SYMFONY_NOTICE = true;

    /** @var Benchmark $benchmark */
    protected $benchmark;

    /** @var null|object */
    protected $cacheInstance;

    /** @var array|mixed */
    protected $cacheHandle;

    /** @var string */
    protected $cacheDriver;

    /** @var array */
    protected $driverConfig;

    /** @var string */
    protected $cachePath;

    /** @var int */
    protected $cacheTtl = 900;

    /** @var string */
    protected $cacheSecurityKey;

    /** @var string|int */
    protected $cacheDefaultChmod;

    /** @var string */
    protected $cacheDefaultKeyHashFunction;

    /** @var Logger $logger */
    protected $logger;

    /** @var bool */
    protected $debugStatus = false;

    /** @var bool|string */
    protected $debugLevel = 'error';

    /** @var string */
    protected $loggerPath = '';

    /**
     * Cache constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        if (self::USE_BENCHMARK === true) {
            $this->benchmark = new Benchmark();
            $this->benchmark->mark('code_start');
        }
        $this->logger = new Logger();
        $this->logger->setLoggerSubPath(__CLASS__);
        $this->logger->setLoggerFilename('Log-' . date('Y-m-d') . '.log');
        if ($this->debugStatus === true && ! empty($this->loggerPath)) {
            $this->logger->setDebugStatus($this->debugStatus);
            $this->logger->setLoggerPath($this->loggerPath);
            if ( ! empty($this->debugLevel)) {
                $this->logger->setGlobalLoggerLevel($this->debugLevel);
            }
        }
    }

    /**
     * Cache destructor.
     */
    public function __destruct()
    {
        if (self::USE_BENCHMARK === true) {
            $this->benchmark->mark('code_end');
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __LINE__ . ' -> Elapsed Time: ===> ' . $this->benchmark->elapsed_time('code_start', 'code_end')
            );
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> Memory Usage: ===> ' . $this->benchmark->memory_usage()
            );
        }
    }

    /**
     * Function setupCacheHandle
     *
     * User: 713uk13m <dev@nguyenanhung.com>
     * Copyright: 713uk13m <dev@nguyenanhung.com>
     * @return void
     */
    public function setupCacheHandle()
    {
        $this->cacheHandle = array(
            //            'path' => $this->cachePath,
            "itemDetailedDate" => false,
            //'fallback'               => self::DEFAULT_DRIVERS,
            'defaultTtl' => self::DEFAULT_TTL,
            'defaultChmod' => ! empty($this->cacheDefaultChmod) ? $this->cacheDefaultChmod : 0777,
            'defaultKeyHashFunction' => ! empty($this->cacheDefaultKeyHashFunction) ? $this->cacheDefaultKeyHashFunction : 'md5'
        );
        if (version_compare(PHP_VERSION, '8.0', '>=')) {
            unset($this->cacheHandle['defaultChmod']);
        }
    }

    /**
     * Function setupCacheInstance
     *
     * User: 713uk13m <dev@nguyenanhung.com>
     * Copyright: 713uk13m <dev@nguyenanhung.com>
     * @return void
     */
    public function setupCacheInstance()
    {
        $this->setupCacheHandle();
        try {
            $handle = $this->cacheHandle;
            if ((empty($this->cacheDriver) || $this->cacheDriver === self::DEFAULT_DRIVERS) && ! empty($this->cachePath)) {
                $handle['path'] = $this->cachePath;
            }
            CacheManager::setDefaultConfig(new ConfigurationOption($handle));
            if ( ! empty($this->cacheDriver)) {
                if ( ! empty($this->driverConfig)) {
                    if (strtolower($this->cacheDriver) === 'redis') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new RedisConfig($this->driverConfig)
                        );
                    } elseif (strtolower($this->cacheDriver) === 'predis') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new PRedisConfig($this->driverConfig)
                        );
                    } elseif (strtolower($this->cacheDriver) === 'memcache') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new MemcacheConfig($this->driverConfig)
                        );
                    } elseif (strtolower($this->cacheDriver) === 'memcached') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new MemcachedConfig($this->driverConfig)
                        );
                    } elseif (strtolower($this->cacheDriver) === 'cassandra') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new CassandraConfig($this->driverConfig)
                        );
                    } elseif (strtolower($this->cacheDriver) === 'ssdb') {
                        $this->cacheInstance = CacheManager::getInstance(
                            $this->cacheDriver,
                            new SsdbConfig($this->driverConfig)
                        );
                    } else {
                        $this->cacheInstance = CacheManager::getInstance($this->cacheDriver);
                    }
                } else {
                    $this->cacheInstance = CacheManager::getInstance($this->cacheDriver);
                }
            } else {
                $this->cacheInstance = CacheManager::getInstance(self::DEFAULT_DRIVERS);
            }
        } catch (Exception $e) {
            $this->logger->error(self::CLASS_NAME . '.' . __FUNCTION__, $e->getMessage());
            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                "----------------------| Begin setupCacheInstance -> Trace Error Log |----------------------"
            );
            $this->logger->error(self::CLASS_NAME . '.' . __FUNCTION__, $e->getTraceAsString());
            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                "----------------------| End setupCacheInstance -> Trace Error Log |----------------------"
            );
            $this->cacheInstance = null;
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
    public function getVersion(): string
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
    public function getProjectStatus(): array
    {
        return array(
            'name' => self::PROJECT_NAME,
            'version' => self::VERSION,
            'lastModified' => self::LAST_MODIFIED,
            'defaultTtl' => self::DEFAULT_TTL,
            'defaultDriver' => self::DEFAULT_DRIVERS,
            'defaultPermission' => self::DEFAULT_CHMOD,
            'authorName' => self::AUTHOR_NAME,
            'authorWebsite' => self::AUTHOR_WEB,
            'authorEmail' => self::AUTHOR_EMAIL
        );
    }

    /**
     * Function setDebugStatus
     *
     * @param  bool|string|null  $debugStatus
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 17:39
     */
    public function setDebugStatus($debugStatus = false): Cache
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
    public function getDebugStatus(): bool
    {
        return $this->debugStatus;
    }

    /**
     * Function setDebugLevel
     *
     * @param  bool|string|null  $debugLevel
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:02
     */
    public function setDebugLevel($debugLevel = false): Cache
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
     * @param  string|null  $loggerPath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:21
     */
    public function setDebugLoggerPath(string $loggerPath = null): Cache
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
     * @param  string|null  $cachePath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 8/30/19 18:43
     */
    public function setCachePath(string $cachePath = null): Cache
    {
        $this->cachePath = trim($cachePath);
        $this->cacheHandle['path'] = $this->cachePath;
        $this->logger->debug(
            self::CLASS_NAME . '.' . __FUNCTION__,
            __FILE__ . ' line ' . __LINE__ . ' -> setCachePath: ' . $this->cachePath
        );

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
     * @param  null  $cacheTtl
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 12:49
     *
     */
    public function setCacheTtl($cacheTtl = null): Cache
    {
        if ( ! empty($cacheTtl)) {
            $this->cacheTtl = $cacheTtl;
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> setCacheTtl: ' . $this->cacheTtl
            );
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
    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    /**
     * Function setCacheDriver - Hàm cấu hình cache drivers
     *
     * @param  string  $cacheDriver
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:01
     *
     */
    public function setCacheDriver(string $cacheDriver = ''): Cache
    {
        if ($cacheDriver !== self::DEFAULT_DRIVERS && (extension_loaded($cacheDriver))) {
            $this->cacheDriver = $cacheDriver;
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> Set Cache Driver: ' . json_encode(
                    $cacheDriver
                ) . ' and server is supported'
            );
        } else {
            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> Set Cache Driver: ' . json_encode(
                    $cacheDriver
                ) . ' and server is not supported, user default driver: ' . self::DEFAULT_DRIVERS
            );
            $this->cacheDriver = self::DEFAULT_DRIVERS;
        }
        $this->logger->debug(
            self::CLASS_NAME . '.' . __FUNCTION__,
            __FILE__ . ' line ' . __LINE__ . ' -> setCacheDriver: ' . $this->cacheDriver
        );

        return $this;
    }

    /**
     * Function getCacheDriver - Hàm lấy ra cấu hình cache drivers
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/04/2020 33:49
     */
    public function getCacheDriver(): string
    {
        return $this->cacheDriver;
    }

    /**
     * Function setDriverConfigure
     *
     * @param  array  $driverConfig
     * User: 713uk13m <dev@nguyenanhung.com>
     * Copyright: 713uk13m <dev@nguyenanhung.com>
     * @return $this
     */
    public function setDriverConfigure(array $driverConfig = array()): Cache
    {
        $this->driverConfig = $driverConfig;
        return $this;
    }

    /**
     * Function getDriverConfigure
     *
     * User: 713uk13m <dev@nguyenanhung.com>
     * Copyright: 713uk13m <dev@nguyenanhung.com>
     * @return array
     */
    public function getDriverConfigure(): array
    {
        return $this->driverConfig;
    }

    /**
     * Function setCacheSecurityKey - Hàm cấu hình mã an toàn khi encode cache
     *
     * @param  string|null  $cacheSecurityKey
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheSecurityKey(string $cacheSecurityKey = null): Cache
    {
        if ( ! empty($cacheSecurityKey)) {
            $this->cacheSecurityKey = $cacheSecurityKey;
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> setCacheSecurityKey: ' . $this->cacheSecurityKey
            );
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
     * @param  null|int|string  $cacheDefaultChmod
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultChmod($cacheDefaultChmod = null): Cache
    {
        if ( ! empty($cacheDefaultChmod)) {
            $this->cacheDefaultChmod = $cacheDefaultChmod;
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> setCacheDefaultChmod: ' . $this->cacheDefaultChmod
            );
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
     * @param  string|null  $cacheDefaultKeyHashFunction
     *
     * @return $this
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     */
    public function setCacheDefaultKeyHashFunction(string $cacheDefaultKeyHashFunction = null): Cache
    {
        if ( ! empty($cacheDefaultKeyHashFunction)) {
            $this->cacheDefaultKeyHashFunction = $cacheDefaultKeyHashFunction;
            $this->logger->debug(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> setCacheDefaultKeyHashFunction: ' . $this->cacheDefaultKeyHashFunction
            );
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
     * @param  mixed  $key
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
            $this->setupCacheInstance();
            if (is_object($this->cacheInstance)) {
                $cache = $this->cacheInstance->getItem($key);
                if ( ! $cache->isHit()) {
                    return false;
                }

                return true;
            }
            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> cacheInstance is not available'
            );
            return false;
        } catch (Exception $e) {
            return $this->exceptionReport($e);
        }
    }

    /**
     * Function get - Hàm lấy dữ liệu cache
     *
     * @param  array|string  $key
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
            $this->setupCacheInstance();
            if (is_object($this->cacheInstance)) {
                if (is_array($key)) {
                    $cache = $this->cacheInstance->getItems($key);
                } else {
                    $cache = $this->cacheInstance->getItem($key);
                }
                if ( ! $cache->isHit()) {
                    $result = null;
                    $this->logger->debug(
                        self::CLASS_NAME . '.' . __FUNCTION__,
                        __FILE__ . ' line ' . __LINE__ . ' -> Unavailable Cache for Key: ' . $key
                    );
                } else {
                    $result = $cache->get();
                    $this->logger->debug(
                        self::CLASS_NAME . '.' . __FUNCTION__,
                        __FILE__ . ' line ' . __LINE__ . ' -> Get Cache from Key: ' . $key . ', result: ' . json_encode(
                            $result
                        )
                    );
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->logger->debug(
                    self::CLASS_NAME . '.' . __FUNCTION__,
                    __FILE__ . ' line ' . __LINE__ . ' -> ' . $message
                );

                return $result;
            }

            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> cacheInstance is not available'
            );

            return null;
        } catch (Exception $e) {
            return $this->exceptionReport($e);
        }
    }

    /**
     * Function save - Hàm Save Cache
     *
     * @param  array|string  $key  Key Cache
     * @param  mixed  $value  Dữ liệu cần cache
     *
     * @return mixed|string|array|object Dữ liệu đầu ra
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 14:37
     */
    public function save($key = '', $value = '')
    {
        try {
            $this->setupCacheInstance();
            if (is_object($this->cacheInstance)) {
                $cache = $this->cacheInstance->getItem($key);
                if ( ! $cache->isHit()) {
                    $cache->set($value)->expiresAfter($this->cacheTtl);//in seconds, also accepts Datetime
                    $this->cacheInstance->save(
                        $cache
                    ); // Save the cache item just like you do with doctrine and entities
                    $result = $cache->get();
                    $this->logger->debug(
                        self::CLASS_NAME . '.' . __FUNCTION__,
                        __FILE__ . ' line ' . __LINE__ . ' -> Save Cache Key: ' . $key . ' with Value: ' . json_encode(
                            $value
                        )
                    );
                } else {
                    $result = $cache->get();
                    $this->logger->debug(
                        self::CLASS_NAME . '.' . __FUNCTION__,
                        __FILE__ . ' line ' . __LINE__ . ' -> Get Cache from Key: ' . $key . ', result: ' . json_encode(
                            $result
                        )
                    );
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->logger->debug(
                    self::CLASS_NAME . '.' . __FUNCTION__,
                    __FILE__ . ' line ' . __LINE__ . ' -> ' . $message
                );

                return $result;
            }

            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> cacheInstance is not available'
            );

            return null;
        } catch (Exception $e) {
            return $this->exceptionReport($e);
        }
    }

    /**
     * Function delete - Hàm Delete Cache
     *
     * @param  array|string  $key
     *
     * @return bool|string|null  True if the request resulted in a cache hit. False otherwise.
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 03/18/2021 28:13
     */
    public function delete($key = '')
    {
        try {
            $this->setupCacheInstance();
            if (is_object($this->cacheInstance)) {
                $result = is_array($key) ? $this->cacheInstance->deleteItems($key) : $this->cacheInstance->deleteItem(
                    $key
                );
                $this->logger->debug(
                    self::CLASS_NAME . '.' . __FUNCTION__,
                    __FILE__ . ' line ' . __LINE__ . ' -> Final Delete Content from Key: ' . $key . ' => Output result: ' . json_encode(
                        $result
                    )
                );

                return $result;
            }

            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> cacheInstance is not available'
            );

            return null;
        } catch (Exception $e) {
            return $this->exceptionReport($e);
        }
    }

    /**
     * Function clean - Hàm Clean Cache
     *
     * @return null|string|array Trả về Array trong trường hợp thành công | Error String nếu có lỗi Exception
     *
     * @author    : 713uk13m <dev@nguyenanhung.com>
     * @copyright : 713uk13m <dev@nguyenanhung.com>
     * @time      : 10/12/18 15:28
     */
    public function clean()
    {
        try {
            $this->setupCacheInstance();
            if (is_object($this->cacheInstance)) {
                $result = array(
                    'result' => $this->cacheInstance->clear(),
                    'commit' => $this->cacheInstance->commit(),
                    'getDriverName' => $this->cacheInstance->getDriverName(),
                    'getStats' => $this->cacheInstance->getStats(),
                    'getConfig' => $this->cacheInstance->getConfig()
                );
                $this->logger->debug(
                    self::CLASS_NAME . '.' . __FUNCTION__,
                    __FILE__ . ' line ' . __LINE__ . ' line ' . __LINE__ . ' -> Clear Cache from Handler: ' . json_encode(
                        $this->cacheHandle
                    ) . ' -> Result: ' . json_encode(
                        $result
                    )
                );

                return $result;
            }

            $this->logger->error(
                self::CLASS_NAME . '.' . __FUNCTION__,
                __FILE__ . ' line ' . __LINE__ . ' -> cacheInstance is not available'
            );

            return null;
        } catch (Exception $e) {
            return $this->exceptionReport($e);
        }
    }

    /**
     * Function exceptionReport
     *
     * @param  Exception|RuntimeException  $e
     * User: 713uk13m <dev@nguyenanhung.com>
     * Copyright: 713uk13m <dev@nguyenanhung.com>
     * @return string
     */
    protected function exceptionReport($e): string
    {
        $message = 'Error File: ' . $e->getFile() .
            ' - Line: ' . $e->getLine() .
            ' - Code: ' . $e->getCode() .
            ' - Message: ' . $e->getMessage();

        if (function_exists('log_message')) {
            // Enabled logging for CodeIgniter Framework
            log_message('error', 'Error Message: ' . $message);
            log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
        }

        $this->logger->error(
            self::CLASS_NAME . '.' . __FUNCTION__,
            __FILE__ . ' line ' . __LINE__ . ' -> Error Message: ' . $e->getMessage()
        );
        $this->logger->error(
            self::CLASS_NAME . '.' . __FUNCTION__,
            __FILE__ . ' line ' . __LINE__ . ' -> Error Trace As String: ' . $e->getTraceAsString()
        );

        return $message;
    }
}
