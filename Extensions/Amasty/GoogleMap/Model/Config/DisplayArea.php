<?php

namespace Amasty\GoogleMap\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class DisplayArea implements ArrayInterface
{
    public const TOP = 'top';

    public const BOTTOM = 'bottom';

    public const NONE = 'no';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TOP, 'label' => __('Top')],
            ['value' => self::BOTTOM, 'label' => __('Bottom')],
            ['value' => self::NONE, 'label' => __('Do not display on \'Contact Us\', enable as a widget')]
        ];
    }
}
