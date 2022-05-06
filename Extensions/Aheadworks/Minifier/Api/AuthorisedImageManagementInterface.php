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
namespace Aheadworks\Minifier\Api;

/**
 * Interface AuthorisedImageManagementInterface
 * @package Aheadworks\Minifier\Api
 */
interface AuthorisedImageManagementInterface
{
    /**
     * Return products count
     *
     * @return int
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function getProductsCount($searchCriteria);

    /**
     * Return images count
     *
     * @return int
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function getImagesCount();

    /**
     * Return products with images and with total images by page
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Minifier\Api\Data\ProductsResponseInterface;
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function getProducts($searchCriteria);

    /**
     * Upload product image
     *
     * @param int $productId
     * @param \Aheadworks\Minifier\Api\Data\ImageInterface $image
     * @return bool true on success
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Zend_Validate_Exception
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function uploadProductImage($productId, $image);

    /**
     * Clear products images cache
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function clearProductsImagesCache();
}
