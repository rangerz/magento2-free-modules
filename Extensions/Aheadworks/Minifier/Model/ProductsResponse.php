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

use Aheadworks\Minifier\Api\Data\ProductsResponseInterface;
use Magento\Framework\DataObject;

/**
 * Class ProductsResponse
 * @package Aheadworks\Minifier\Model
 */
class ProductsResponse extends DataObject implements ProductsResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProducts()
    {
        return $this->getData(self::PRODUCTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setProducts($products)
    {
        return $this->setData(self::PRODUCTS, $products);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountImages()
    {
        return $this->getData(self::COUNT_IMAGES);
    }

    /**
     * {@inheritdoc}
     */
    public function setCountImages($countImages)
    {
        return $this->setData(self::COUNT_IMAGES, $countImages);
    }
}
