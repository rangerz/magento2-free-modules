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
namespace Aheadworks\Minifier\Api\Data;

/**
 * Interface ProductsResponseInterface
 * @package Aheadworks\Minifier\Api\Data
 */
interface ProductsResponseInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const PRODUCTS = 'products';
    const COUNT_IMAGES = 'count_images';
    /**#@-*/

    /**
     * Get products
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getProducts();

    /**
     * Set products
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface[] $products
     * @return $this
     */
    public function setProducts($products);

    /**
     * Get count images
     *
     * @return int
     */
    public function getCountImages();

    /**
     * Set count images
     *
     * @param int $countImages
     * @return $this
     */
    public function setCountImages($countImages);
}
