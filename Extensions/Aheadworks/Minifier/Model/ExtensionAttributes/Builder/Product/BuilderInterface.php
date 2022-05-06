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
namespace Aheadworks\Minifier\Model\ExtensionAttributes\Builder\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Minifier\Api\Data\MinifierProductAttributesInterface;

interface BuilderInterface
{
    /**
     * Build product extension attributes
     *
     * @param ProductInterface $product
     * @param MinifierProductAttributesInterface $minifierAttributes
     * @return void
     */
    public function build($product, $minifierAttributes);
}
