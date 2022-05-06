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
namespace Aheadworks\Minifier\Model\Authorisation;

use Aheadworks\Minifier\Model\Config;

/**
 * Class HashManagement
 * @package Aheadworks\Minifier\Model\Authorisation
 */
class HashManagement
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Return client auth hash
     *
     * @return string|null
     */
    public function getClientAuthHash()
    {
        $result = null;

        $publicKey = $this->config->getDecryptedPublicApiKey();
        $privateKey = $this->config->getDecryptedPrivateApiKey();

        if ($publicKey && $privateKey) {
            $result = hash_hmac('sha256', $publicKey, $privateKey);
        }

        return $result;
    }
}
