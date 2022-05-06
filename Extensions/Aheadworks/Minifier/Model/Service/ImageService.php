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
namespace Aheadworks\Minifier\Model\Service;

use Aheadworks\Minifier\Api\ImageManagementInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Aheadworks\Minifier\Api\Data\ProductsResponseInterface;
use Aheadworks\Minifier\Api\Data\ProductsResponseInterfaceFactory;
use Magento\Catalog\Model\Product\Image as CatalogProductImage;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Aheadworks\Minifier\Model\ProductImageUpdater;

/**
 * Class ImageService
 * @package Aheadworks\Minifier\Model\Service
 */
class ImageService implements ImageManagementInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ProductsResponseInterfaceFactory
     */
    private $productsResponseFactory;

    /**
     * @var CatalogProductImage
     */
    private $catalogProductImage;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var ProductImageUpdater
     */
    private $productImageUpdater;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductsResponseInterfaceFactory $productsResponseFactory
     * @param CatalogProductImage $catalogProductImage
     * @param EventManagerInterface $eventManager
     * @param ProductImageUpdater $productImageUpdater
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductsResponseInterfaceFactory $productsResponseFactory,
        CatalogProductImage $catalogProductImage,
        EventManagerInterface $eventManager,
        ProductImageUpdater $productImageUpdater
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productsResponseFactory = $productsResponseFactory;
        $this->catalogProductImage = $catalogProductImage;
        $this->eventManager = $eventManager;
        $this->productImageUpdater = $productImageUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsCount($searchCriteria)
    {
        /** @var SearchCriteria $searchCriteria */
        $searchCriteria->setPageSize(1);
        /** @var  ProductSearchResultsInterface $products */
        $products = $this->productRepository->getList($searchCriteria);
        $productsCount = $products->getTotalCount();

        return $productsCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getImagesCount()
    {
        $countImages = 0;

        /** @var SearchCriteria $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder->create();
        /** @var  ProductSearchResultsInterface $products */
        $products = $this->productRepository->getList($searchCriteria);
        foreach ($products->getItems() as $product) {
            $countImages += count($product->getMediaGalleryEntries());
        }

        return $countImages;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts($searchCriteria)
    {
        /** @var ProductInterface[] $products */
        $products = $this->productRepository->getList($searchCriteria)->getItems();
        $countImages = 0;
        foreach ($products as $product) {
            $countImages += count($product->getExtensionAttributes()->getAwMinifier()->getImages());
        }

        /** @var ProductsResponseInterface $productsResponse */
        $result = $this->productsResponseFactory->create();
        $result
            ->setProducts($products)
            ->setCountImages($countImages);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function uploadProductImage($productId, $image)
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->getById($productId);
        return $this->productImageUpdater->updateProductImage($product, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function clearProductsImagesCache()
    {
        $this->catalogProductImage->clearCache();
        $this->eventManager->dispatch('clean_catalog_images_cache_after');
        return true;
    }
}
