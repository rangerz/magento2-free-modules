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

use Aheadworks\Minifier\Api\RequestManagementInterface;
use Aheadworks\Minifier\Api\UrlManagementInterface;

/**
 * Class RequestService
 * @package Aheadworks\Minifier\Model\Service
 */
class RequestService implements RequestManagementInterface
{
    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @param UrlManagementInterface $urlManagement
     */
    public function __construct(
        UrlManagementInterface $urlManagement
    ) {
        $this->urlManagement = $urlManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrameUrl()
    {
        return $this->urlManagement->getUrl('auth');
    }
}
