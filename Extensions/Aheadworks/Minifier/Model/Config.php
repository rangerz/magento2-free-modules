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
namespace Aheadworks\Minifier\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Config\Model\Config\Backend\Encrypted;

/**
 * @package Aheadworks\Minifier\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    const XML_PATH_PUBLIC_API_KEY = 'aw_minifier/aw_minifier_setting/public_api_key';
    const XML_PATH_PRIVATE_API_KEY = 'aw_minifier/aw_minifier_setting/private_api_key';
    const XML_PATH_ENABLE_PREFETCHING = 'aw_minifier/aw_minifier_setting/prefetching';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Encrypted
     */
    private $encryptor;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Encrypted $encryptor
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Encrypted $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * Retrieve public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPublicApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PUBLIC_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPrivateApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PRIVATE_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve decrypted  public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDecryptedPublicApiKey($storeId = null)
    {
        $value = $this->getPublicApiKey($storeId);

        return $this->encryptor->processValue($value);
    }

    /**
     * Retrieve decrypted public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDecryptedPrivateApiKey($storeId = null)
    {
        $value = $this->getPrivateApiKey($storeId);

        return $this->encryptor->processValue($value);
    }

    /**
     * Check is enable prefetching
     *
     * @return bool
     */
    public function isEnablePrefetching()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE_PREFETCHING
        );
    }
}