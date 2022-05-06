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

use Aheadworks\Minifier\Api\Data\MinifierProductAttributesInterface;
use Magento\Framework\DataObject;

/**
 * Class MinifierProductData
 * @package Aheadworks\Minifier\Model
 */
class MinifierProductAttributes extends DataObject implements MinifierProductAttributesInterface
{
    /**
     * {@inheritdoc}
     */
    public function setImages($images)
    {
        return $this->setData(self::IMAGES, $images);
    }

    /**
     * {@inheritdoc}
     */
    public function getImages()
    {
        return $this->getData(self::IMAGES);
    }
}
