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
namespace Aheadworks\Minifier\Model\ResourceModel\Product\Relation\MinifierOptions;

use Aheadworks\Minifier\Api\Data\MinifierProductAttributesInterface;
use Aheadworks\Minifier\Api\Data\MinifierProductAttributesInterfaceFactory;
use Aheadworks\Minifier\Model\ExtensionAttributes\Builder\Product\BuilderInterface;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 * @package Aheadworks\Minifier\Model\ResourceModel\Product\Relation\MinifierOptions
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var BuilderInterface
     */
    private $attributesBuilder;

    /**
     * @var ProductExtensionFactory
     */
    private $productExtensionFactory;

    /**
     * @var MinifierProductAttributesInterfaceFactory
     */
    private $minifierProductAttributesFactory;

    /**
     * @param BuilderInterface $attributesBuilder
     * @param ProductExtensionFactory $productExtensionFactory
     * @param MinifierProductAttributesInterfaceFactory $minifierProductAttributesFactory
     */
    public function __construct(
        BuilderInterface $attributesBuilder,
        ProductExtensionFactory $productExtensionFactory,
        MinifierProductAttributesInterfaceFactory $minifierProductAttributesFactory
    ) {
        $this->attributesBuilder = $attributesBuilder;
        $this->productExtensionFactory = $productExtensionFactory;
        $this->minifierProductAttributesFactory = $minifierProductAttributesFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        /** @var ProductExtensionInterface $extensionAttributes */
        $extensionAttributes = $entity->getExtensionAttributes()
            ? $entity->getExtensionAttributes()
            : $this->productExtensionFactory->create();

        /** @var  MinifierProductAttributesInterface $minifierProductAttributes */
        $minifierProductAttributes = $extensionAttributes->getAwMinifier()
            ? $extensionAttributes->getAwMinifier()
            : $this->minifierProductAttributesFactory->create();

        $this->attributesBuilder->build($entity, $minifierProductAttributes);

        $extensionAttributes->setAwMinifier($minifierProductAttributes);
        $entity->setExtensionAttributes($extensionAttributes);

        return $entity;
    }
}
