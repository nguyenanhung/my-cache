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
use nguyenanhung\MyDebug\Debug;
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
     * @var object
     */
    private $cacheInstance;
    private $cacheHandle;
    private $cacheDriver = NULL;
    private $cachePath   = NULL;
    private $cacheTtl    = 500;
    private $cacheSecurityKey;
    private $cacheDefaultChmod;
    private $cacheDefaultKeyHashFunction;
    private $debug;
    private $debugStatus = FALSE;
    private $debugLevel  = FALSE;
    private $loggerPath  = NULL;

    /**
     * Cache constructor.
     */
    public function __construct()
    {
        $this->debug = new Debug();
        $this->debug->setLoggerSubPath(__CLASS__);
        $this->debug->setLoggerFilename('Log-' . date('Y-m-d') . '.log');
        if ($this->debugStatus === TRUE && !empty($this->loggerPath)) {
            $this->debug->setDebugStatus($this->debugStatus);
            $this->debug->setLoggerPath($this->loggerPath);
            if (!empty($this->debugLevel)) {
                $this->debug->setGlobalLoggerLevel($this->debugLevel);
            }
        }
        $this->debug->debug(__FUNCTION__, '/~~~~~~~~~~~~~~~~~~~> Class Cache - Version: ' . self::VERSION . ' - Last Modified: ' . self::LAST_MODIFIED . ' <~~~~~~~~~~~~~~~~~~~\\');
        $this->cacheHandle = [
            'path'                   => $this->cachePath,
            "itemDetailedDate"       => FALSE,
            'ignoreSymfonyNotice'    => self::IGNORE_SYMFONY_NOTICE,
            'securityKey'            => !empty($this->cacheSecurityKey) ? $this->cacheSecurityKey : 'Auto',
            'default_chmod'          => !empty($this->cacheDefaultChmod) ? $this->cacheDefaultChmod : 0777,
            'defaultKeyHashFunction' => !empty($this->cacheDefaultKeyHashFunction) ? $this->cacheDefaultKeyHashFunction : ''
        ];
        try {
            CacheManager::setDefaultConfig($this->cacheHandle);
            if (!empty($this->cacheDriver)) {
                $this->cacheInstance = CacheManager::getInstance($this->cacheDriver);
            } else {
                $this->cacheInstance = CacheManager::getInstance(self::DEFAULT_DRIVERS);
            }
        }
        catch (\Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->debug->error(__FUNCTION__, $message);
            $this->cacheInstance = NULL;
        }
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

    /**
     * Function setDebugStatus
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param bool $debugStatus
     */
    public function setDebugStatus($debugStatus = FALSE)
    {
        $this->debugStatus = $debugStatus;
    }

    /**
     * Function setDebugLevel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param bool|string|null $debugLevel
     */
    public function setDebugLevel($debugLevel = FALSE)
    {
        $this->debugLevel = $debugLevel;
    }

    /**
     * Function setDebugLoggerPath
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param null $loggerPath
     */
    public function setDebugLoggerPath($loggerPath = NULL)
    {
        $this->loggerPath = $loggerPath;
    }

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
    public function setCachePath($cachePath = NULL)
    {
        $this->cachePath = $cachePath;
        $this->debug->debug(__FUNCTION__, 'setCachePath: ', $this->cachePath);
    }

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
    public function setCacheTtl($cacheTtl = NULL)
    {
        $this->cacheTtl = $cacheTtl;
        $this->debug->debug(__FUNCTION__, 'setCacheTtl: ', $this->cacheTtl);
    }

    /**
     * Function setCacheDriver
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:01
     *
     * @param string $cacheDriver
     */
    public function setCacheDriver($cacheDriver = '')
    {
        if ($cacheDriver != self::DEFAULT_DRIVERS && (extension_loaded($cacheDriver))) {
            $this->cacheDriver = $cacheDriver;
            $this->debug->debug(__FUNCTION__, 'Set Cache Driver: ' . json_encode($cacheDriver) . ' and server is supported');
        } else {
            $this->debug->error(__FUNCTION__, 'Set Cache Driver: ' . json_encode($cacheDriver) . ' and server is not supported, user default driver: ' . self::DEFAULT_DRIVERS);
            $this->cacheDriver = self::DEFAULT_DRIVERS;
        }
        $this->debug->debug(__FUNCTION__, 'setCacheDriver: ', $this->cacheDriver);
    }

    /**
     * Function setCacheSecurityKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheSecurityKey
     */
    public function setCacheSecurityKey($cacheSecurityKey)
    {
        $this->cacheSecurityKey = $cacheSecurityKey;
        $this->debug->debug(__FUNCTION__, 'setCacheSecurityKey: ', $this->cacheSecurityKey);
    }

    /**
     * Function setCacheDefaultChmod
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheDefaultChmod
     */
    public function setCacheDefaultChmod($cacheDefaultChmod)
    {
        $this->cacheDefaultChmod = $cacheDefaultChmod;
        $this->debug->debug(__FUNCTION__, 'setCacheDefaultChmod: ', $this->cacheDefaultChmod);
    }

    /**
     * Function setCacheDefaultKeyHashFunction
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:56
     *
     * @param $cacheDefaultKeyHashFunction
     */
    public function setCacheDefaultKeyHashFunction($cacheDefaultKeyHashFunction)
    {
        $this->cacheDefaultKeyHashFunction = $cacheDefaultKeyHashFunction;
        $this->debug->debug(__FUNCTION__, 'setCacheDefaultKeyHashFunction: ', $this->cacheDefaultKeyHashFunction);
    }

    /**
     * Function has
     *
     * Kiểm tra sự tồn tại dữ liệu cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:10
     *
     * @param string $key
     *
     * @return bool|string True if the request resulted in a cache hit. False otherwise.
     */
    public function has($key = '')
    {
        try {
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /**
                 * @var $cache object
                 */
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    return FALSE;
                } else {
                    return FALSE;
                }
            } else {
                $this->debug->error(__FUNCTION__, 'Unavailable cacheInstance');

                return FALSE;
            }
        }
        catch (\Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->debug->error(__FUNCTION__, $message);

            return $message;
        }
    }

    /**
     * Function get
     *
     * Hàm lấy dữ liệu cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 18:09
     *
     * @param string $key
     *
     * @return bool|mixed|string|null
     */
    public function get($key = '')
    {
        try {
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /**
                 * @var $cache object
                 */
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    $result = NULL;
                    $this->debug->debug(__FUNCTION__, 'Unavailable Cache for Key: ' . $key);
                } else {
                    $result = $cache->get();
                    $this->debug->debug(__FUNCTION__, 'Get Cache from Key: ' . $key . ', result: ' . json_encode($result));
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->debug->info(__FUNCTION__, $message);

                return $result;
            } else {
                $this->debug->error(__FUNCTION__, 'Unavailable cacheInstance');

                return NULL;
            }
        }
        catch (\Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->debug->error(__FUNCTION__, $message);

            return $message;
        }
    }

    /**
     * Function save
     *
     * Hàm Save Cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:37
     *
     * @param string $key   Key Cache
     * @param string $value Dữ liệu cần cache
     *
     * @return mixed|string|array|object Dữ liệu đầu ra
     */
    public function save($key = '', $value = '')
    {
        try {
            if ($this->cacheInstance !== NULL && is_object($this->cacheInstance)) {
                /**
                 * @var $cache object
                 */
                $cache = $this->cacheInstance->getItem($key);
                if (!$cache->isHit()) {
                    $cache->set($value)->expiresAfter($this->cacheTtl);//in seconds, also accepts Datetime
                    $this->cacheInstance->save($cache); // Save the cache item just like you do with doctrine and entities
                    $result = $cache->get();
                    $this->debug->debug(__FUNCTION__, 'Save Cache Key: ' . $key . ' with Value: ' . json_encode($value));
                } else {
                    $result = $cache->get();
                    $this->debug->debug(__FUNCTION__, 'Get Cache from Key: ' . $key . ', result: ' . json_encode($result));
                }
                $message = 'Final get Content from Key: ' . $key . ' => Output result: ' . json_encode($result);
                $this->debug->info(__FUNCTION__, $message);

                return $result;
            } else {
                $this->debug->error(__FUNCTION__, 'Unavailable cacheInstance');

                return NULL;
            }
        }
        catch (\Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->debug->error(__FUNCTION__, $message);

            return $message;
        }
    }

    /**
     * Function clean
     *
     * Hàm Clean Cache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 15:28
     *
     * @return bool|string|array
     * Trả về TRUE trong trường hợp thành công
     * Error String nếu có lỗi Exception
     */
    public function clean()
    {
        try {
            $result = [
                'result'        => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->clear() : FALSE,
                'commit'        => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->commit() : NULL,
                'getDriverName' => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getDriverName() : NULL,
                'getStats'      => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getStats() : NULL,
                'getConfig'     => isset($this->cacheInstance) && is_object($this->cacheInstance) ? $this->cacheInstance->getConfig() : NULL,
            ];
            $this->debug->debug(__FUNCTION__, 'Clear Cache from Handler: ' . json_encode($this->cacheHandle) . ' -> Result: ' . json_encode($result));

            return $result;
        }
        catch (\Exception $e) {
            $message = 'Error File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Code: ' . $e->getCode() . ' - Message: ' . $e->getMessage();
            $this->debug->error(__FUNCTION__, $message);

            return $message;
        }
    }
}
