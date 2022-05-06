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
namespace Aheadworks\Minifier\Model\Service;

use Aheadworks\Minifier\Api\UrlManagementInterface;
use Aheadworks\Minifier\Model\VersionChecker;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;
use Aheadworks\Minifier\Model\Authorisation\HashManagement;

/**
 * Class UrlService
 * @package Aheadworks\Minifier\Model\Service
 */
class UrlService implements UrlManagementInterface
{
    /**
     * Api base url
     */
    const API_BASE_URL = 'https://app.minifier.aheadworks.com';

    /**
     * platform name
     */
    const PLATFORM = 'magento';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Url
     */
    private $frontendUrl;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var VersionChecker
     */
    private $versionChecker;

    /**
     * @var HashManagement
     */
    private $hashManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Url $frontendUrl
     * @param ScopeConfigInterface $scopeConfig
     * @param VersionChecker $versionChecker
     * @param HashManagement $hashManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Url $frontendUrl,
        ScopeConfigInterface $scopeConfig,
        VersionChecker $versionChecker,
        HashManagement $hashManager
    ) {
        $this->storeManager = $storeManager;
        $this->frontendUrl = $frontendUrl;
        $this->scopeConfig = $scopeConfig;
        $this->versionChecker = $versionChecker;
        $this->hashManager = $hashManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($routePath, $routeParams = [], $addAdditional = true)
    {
        if ($addAdditional) {
            $routeParams = array_merge(
                $routeParams,
                [
                    'platform' => self::PLATFORM,
                    'shop' => $this->getStoreUrl(),
                    'hmac' => $this->hashManager->getClientAuthHash(),
                    'version' => $this->versionChecker->getModuleVersion()
                ]
            );
            $routeParams = '?' . http_build_query($routeParams);
            $routePath = '/' . $routePath;
        } else {
            $routePath = $routeParams = '';
        }

        return self::API_BASE_URL . $routePath . $routeParams;
    }

    /**
     * {@inheritDoc}
     */
    public function getFrontendUrl($path, $params = [])
    {
        return $this->frontendUrl->getUrl($path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($url)
    {
        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Retrieve store url
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStoreUrl()
    {
        $store = $this->storeManager->getStore();
        $isSecure = $this->scopeConfig->isSetFlag(
            'web/secure/use_in_frontend',
            ScopeInterface::SCOPE_STORE,
            $store
        );

        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        return parse_url(rtrim($store->getBaseUrl('link', $isSecure), '/'), PHP_URL_HOST);
    }
}
