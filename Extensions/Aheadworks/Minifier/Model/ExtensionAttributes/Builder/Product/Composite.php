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

/**
 * Class Composite
 * @package Aheadworks\Minifier\Model\ExtensionAttributes\Builder\Product
 */
class Composite implements BuilderInterface
{
    /**
     * @var BuilderInterface[]
     */
    private $builders;

    /**
     * @param array $builders
     */
    public function __construct(
        $builders = []
    ) {
        $this->builders = $builders;
    }

    /**
     * {@inheritdoc}
     */
    public function build($product, $minifierAttributes)
    {
        foreach ($this->builders as $builder) {
            $builder->build($product, $minifierAttributes);
        }
    }
}
