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

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Aheadworks\Minifier\Api\RequestManagementInterface;

/**
 * Class Panel
 * @package Aheadworks\Minifier\ViewModel\Minifier
 */
class Panel implements ArgumentInterface
{
    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @param RequestManagementInterface $requestManagement
     */
    public function __construct(
        RequestManagementInterface $requestManagement
    ) {
        $this->requestManagement = $requestManagement;
    }

    /**
     * Retrieve url for frame load
     *
     * @return string
     */
    public function getFrameUrl()
    {
        return $this->requestManagement->getFrameUrl();
    }
}
