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

use Magento\Catalog\Model\Product\Gallery\GalleryManagement;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;

/**
 * Class ProductImageUpdater
 * @package Aheadworks\Minifier\Model
 */
class ProductImageUpdater
{
    /**
     * @var GalleryManagement
     */
    private $galleryManager;

    /**
     * @var AbstractValidator
     */
    private $imageValidator;

    /**
     * @var ImageFileManagement
     */
    private $imageFileManager;

    /**
     * @param GalleryManagement $galleryManager
     * @param AbstractValidator $imageValidator
     * @param ImageFileManagement $imageFileManager
     */
    public function __construct(
        GalleryManagement $galleryManager,
        AbstractValidator $imageValidator,
        ImageFileManagement $imageFileManager
    )
    {
        $this->galleryManager = $galleryManager;
        $this->imageValidator = $imageValidator;
        $this->imageFileManager = $imageFileManager;
    }

    /**
     * {@inheritDoc}
     */
    public function updateProductImage($product, $image)
    {
        if (!$this->imageValidator->isValid($image)) {
            $messagesArray = $this->imageValidator->getMessages();
            $message = reset($messagesArray);
            throw new InputException($message);
        }

        $imageFound = false;
        foreach ($product->getMediaGalleryEntries() as $mediaGalleryEntry) {
            if ($mediaGalleryEntry->getId() == $image->getMediaGalleryId()) {
                $imageFound = true;
                $imageFile = $image->getFile();

                $mediaGalleryEntry->setContent($image);
                $this->imageFileManager->backupProductImage($imageFile);
                $this->imageFileManager->deleteProductImage($imageFile);
                try {
                    $this->galleryManager->update($product->getSku(), $mediaGalleryEntry);
                } catch (\Exception $exception) {
                    $this->imageFileManager->restoreProductImage($imageFile);
                    throw new LocalizedException(__('Product image cannot be updated'));
                }
                $this->imageFileManager->deleteProductImageFromBackup($imageFile);

                break;
            }
        }

        if (!$imageFound) {
            throw new LocalizedException(__('Media gallery entry not found'));
        }

        return true;
    }
}
