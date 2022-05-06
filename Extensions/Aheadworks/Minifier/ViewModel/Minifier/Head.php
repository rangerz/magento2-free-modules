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
namespace Aheadworks\Minifier\ViewModel\Minifier;

use Aheadworks\Minifier\Model\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class Head
 * @package Aheadworks\Minifier\ViewModel\Minifier
 */
class Head implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Check is enable prefetching
     *
     * @return bool
     */
    public function isEnablePrefetching()
    {
        return $this->config->isEnablePrefetching();
    }
}
