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
 * Interface MinifierProductAttributesInterface
 * @package Aheadworks\Minifier\Api\Data
 */
interface MinifierProductAttributesInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const IMAGES = 'images';
    /**#@-*/

    /**
     * Get images
     *
     * @return \Aheadworks\Minifier\Api\Data\ImageInterface[]
     */
    public function getImages();

    /**
     * Set images
     *
     * @param \Aheadworks\Minifier\Api\Data\ImageInterface[] $images
     * @return $this
     */
    public function setImages($images);
}
