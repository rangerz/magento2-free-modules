<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://ecommerce.aheadworks.com/end-user-license-agreement/
 *
 * @package    Minifier
 * @version    1.0.0
 * @copyright  Copyright (c) 2020 Aheadworks Inc. (http://www.aheadworks.com)
 * @license    https://ecommerce.aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Minifier\Api;

/**
 * Interface UrlManagementInterface
 * @package Aheadworks\Minifier\Api
 */
interface UrlManagementInterface
{
    /**
     * Build url by requested path and parameters
     *
     * @param string $routePath
     * @param array $routeParams
     * @param bool $addAdditional
     * @return string
     */
    public function getUrl($routePath, $routeParams = [], $addAdditional = true);

    /**
     * Retrieve frontend url
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function getFrontendUrl($path, $params = []);

    /**
     * Retrieve path from url
     *
     * @param string $url
     * @return string
     */
    public function getPath($url);
}
