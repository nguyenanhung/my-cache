<?php
/**
 * Project my-cache.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/7/18
 * Time: 20:27
 */

namespace nguyenanhung\MyCache;

/**
 * Interface ProjectInterface
 *
 * @category  Interface
 * @package   nguyenanhung\MyCache
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface ProjectInterface
{
    /**
     * Base version of Project
     */
    const VERSION       = '1.0.3';
    const LAST_MODIFIED = '2019-07-21';
    const AUTHOR_NAME   = 'Hung Nguyen';
    const AUTHOR_EMAIL  = 'dev@nguyenanhung.com';
    const PROJECT_NAME  = 'My Cache';
    const USE_BENCHMARK = FALSE;

    /**
     * Function getVersion
     *
     * @return mixed Current Version of Package
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 10/9/18 00:17
     *
     * @example 0.1.0
     */
    public function getVersion();
}
