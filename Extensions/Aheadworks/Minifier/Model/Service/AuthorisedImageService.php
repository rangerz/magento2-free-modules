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

use Aheadworks\Minifier\Api\AuthorisedImageManagementInterface;
use Aheadworks\Minifier\Api\ImageManagementInterface;
use Aheadworks\Minifier\Model\Authorisation\Validator;
use Magento\Framework\Webapi\Rest\Request as RestRequest;

/**
 * Class AuthorisedImageService
 * @package Aheadworks\Minifier\Model\Service
 */
class AuthorisedImageService implements AuthorisedImageManagementInterface
{
    /**
     * @var ImageManagementInterface
     */
    private $imageService;

    /**
     * @var RestRequest
     */
    private $request;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param ImageManagementInterface $imageService
     * @param RestRequest $request
     * @param Validator $validator
     */
    public function __construct(
        ImageManagementInterface $imageService,
        RestRequest $request,
        Validator $validator
    ) {
        $this->imageService = $imageService;
        $this->request = $request;
        $this->validator = $validator;
        $this->validator->validate($this->request);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsCount($searchCriteria)
    {
        return $this->imageService->getProductsCount($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getImagesCount()
    {
        return $this->imageService->getImagesCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts($searchCriteria)
    {
        return $this->imageService->getProducts($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadProductImage($productId, $image)
    {
        return $this->imageService->uploadProductImage($productId, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function clearProductsImagesCache()
    {
        return $this->imageService->clearProductsImagesCache();
    }
}
