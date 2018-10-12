<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/7/18
 * Time: 20:27
 */

namespace nguyenanhung\MyCache\Interfaces;
/**
 * Interface ProjectInterface
 *
 * @category  Interface
 * @package   nguyenanhung\MyCache\Interfaces
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface ProjectInterface
{
    /**
     * Base version of Project
     */
    const VERSION       = '0.1.3';
    const LAST_MODIFIED = '2018-10-12';
    const AUTHOR_NAME   = 'Hung Nguyen';
    const AUTHOR_EMAIL  = 'dev@nguyenanhung.com';
    const PROJECT_NAME  = 'My Cache';

    /**
     * Function getVersion
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 10/9/18 00:17
     *
     * @return mixed Current Version of Package
     * @example 0.1.0
     */
    public function getVersion();

    /**
     * Function setDebugStatus
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param bool $debugStatus
     */
    public function setDebugStatus($debugStatus = FALSE);

    /**
     * Function setDebugLevel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param bool|string|null $debugLevel
     */
    public function setDebugLevel($debugLevel = FALSE);

    /**
     * Function setDebugLoggerPath
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/12/18 14:30
     *
     * @param null $loggerPath
     */
    public function setDebugLoggerPath($loggerPath = NULL);
}
