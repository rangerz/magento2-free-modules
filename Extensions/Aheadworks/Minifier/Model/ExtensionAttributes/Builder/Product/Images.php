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

use Aheadworks\Minifier\Api\Data\ImageInterface;
use Aheadworks\Minifier\Api\Data\ImageInterfaceFactory;
use Aheadworks\Minifier\Model\ImageFileManagement;
use Magento\Catalog\Model\Product\Media\Config as CatalogMediaConfig;

/**
 * Class Images
 * @package Aheadworks\Minifier\Model\ExtensionAttributes\Builder\Product
 */
class Images implements BuilderInterface
{
    /**
     * @var ImageInterfaceFactory
     */
    private $imageFactory;

    /**
     * @var ImageFileManagement
     */
    private $imageFileManager;

    /**
     * @var CatalogMediaConfig
     */
    private $catalogMediaConfig;

    /**
     * @param ImageInterfaceFactory $imageFactory
     * @param ImageFileManagement $imageFileManager
     * @param CatalogMediaConfig $catalogMediaConfig
     */
    public function __construct(
        ImageInterfaceFactory $imageFactory,
        ImageFileManagement $imageFileManager,
        CatalogMediaConfig $catalogMediaConfig
    ) {
        $this->imageFactory = $imageFactory;
        $this->imageFileManager = $imageFileManager;
        $this->catalogMediaConfig = $catalogMediaConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function build($product, $minifierAttributes)
    {
        $productImages = [];

        foreach ($product->getMediaGalleryEntries() as $mediaGalleryEntry) {
            $imageFile = $mediaGalleryEntry->getFile();
            $imageFileMimeType = $this->imageFileManager->getProductImageMimeType($imageFile);
            $imageUrl = $this->catalogMediaConfig->getMediaUrl($imageFile);
            //phpcs:ignore Magento2.Functions.DiscouragedFunction
            $fileName = basename($imageFile);

            if ($imageFile && $imageFileMimeType && $imageUrl) {
                /** @var ImageInterface $productImage */
                $productImage = $this->imageFactory->create();
                $productImage
                    ->setMediaGalleryId($mediaGalleryEntry->getId())
                    ->setUrl($imageUrl)
                    ->setType($imageFileMimeType)
                    ->setName($fileName)
                    ->setFile($imageFile);

                $productImages[] = $productImage;
            }
        }

        $minifierAttributes->setImages($productImages);
    }
}
