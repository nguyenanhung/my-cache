<?php
/**
 * Project my-cache
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/28/18
 * Time: 14:47
 */

namespace nguyenanhung\MyCache\Repository;

use nguyenanhung\MyCache\Interfaces\ProjectInterface;

/**
 * Class DataRepository
 *
 * @category  Repository
 * @package   nguyenanhung\MyCache\Repository
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class DataRepository implements ProjectInterface
{
    /**
     * Config Path
     */
    const CONFIG_PATH = 'config';
    /**
     * File config extension
     */
    const CONFIG_EXT = '.php';

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/7/18 01:07
     *
     * @return mixed|string Current Version of Package
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Function getData
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/7/18 01:07
     *
     * @param $configName  file config from /config path
     *
     * @return array|mixed Array content config if available, empty array if unavailable
     */
    public static function getData($configName)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . self::CONFIG_PATH . DIRECTORY_SEPARATOR . $configName . self::CONFIG_EXT;
        if (is_file($path) && file_exists($path)) {
            return require($path);
        }

        return [];
    }
}
