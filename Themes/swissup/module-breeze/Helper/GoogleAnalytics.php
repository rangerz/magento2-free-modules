<?php

namespace Swissup\Breeze\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class GoogleAnalytics extends AbstractHelper
{
    /**
     * @param Context $context
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        Config $configHelper
    ) {
        parent::__construct($context);

        $this->configHelper = $configHelper;
    }

    private function isEnabled()
    {
        return $this->configHelper->isEnabled('google/analytics/active');
    }

    private function getType()
    {
        return $this->configHelper->getValue('google/analytics/type');
    }

    public function isUniversalAnalyticsEnabled()
    {
        $type = $this->getType();

        return $this->isEnabled()
            && (!$type || $type === 'universal')
            && $this->configHelper->isEnabled('google/analytics/account');
    }

    public function isTagManagerAnalyticsEnabled()
    {
        return $this->isEnabled()
            && $this->getType() === 'tag_manager'
            && $this->configHelper->isEnabled('google/analytics/container_id');
    }
}
