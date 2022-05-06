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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\Product\Media\Config as CatalogMediaConfig;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;

/**
 * Class ImageFileManagement
 * @package Aheadworks\Minifier\Model
 */
class ImageFileManagement
{
    CONST BACKUP_FILE_SUFFIX = '_aw_minifier_backup';

    /**
     * @var CatalogMediaConfig
     */
    private $catalogMediaConfig;

    /**
     * @var WriteInterface
     */
    private $mediaDirectoryWrite;

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @param CatalogMediaConfig $catalogMediaConfig
     * @param Mime $mime
     * @param Filesystem $filesystem
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        CatalogMediaConfig $catalogMediaConfig,
        Mime $mime,
        Filesystem $filesystem
    ) {
        $this->catalogMediaConfig = $catalogMediaConfig;
        $this->mime = $mime;
        $this->mediaDirectoryWrite = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Get image mime type
     *
     * @param string $imageFile
     * @return string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getProductImageMimeType($imageFile)
    {
        $mimeType = null;

        if ($this->isImageExists($imageFile)) {
            $imageMediaPath = $this->getImageMediaPath($imageFile);
            $imageAbsolutePath = $this->mediaDirectoryWrite->getAbsolutePath($imageMediaPath);
            $mimeType = $this->mime->getMimeType($imageAbsolutePath);
       }

        return $mimeType;
    }

    /**
     * Delete product image
     *
     * @param string $imageFile
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function deleteProductImage($imageFile)
    {
        if ($this->isImageExists($imageFile)) {
            $imageMediaPath = $this->getImageMediaPath($imageFile);
            $this->mediaDirectoryWrite->delete($imageMediaPath);
        }
    }

    /**
     * Backup product image
     *
     * @param string $imageFile
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function backupProductImage($imageFile)
    {
        if ($this->isImageExists($imageFile)) {
            $imageMediaPath = $this->getImageMediaPath($imageFile);
            $imageBackupPath = $this->getImageBackupPath($imageFile);
            $this->mediaDirectoryWrite->copyFile($imageMediaPath, $imageBackupPath);
        }
    }

    /**
     * Delete product image from backup
     *
     * @param string $imageFile
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function deleteProductImageFromBackup($imageFile)
    {
        if ($this->isBackupExists($imageFile)) {
            $imageBackupPath = $this->getImageBackupPath($imageFile);
            $this->mediaDirectoryWrite->delete($imageBackupPath);
        }
    }

    /**
     * Restore product image
     *
     * @param string $imageFile
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function restoreProductImage($imageFile)
    {
        if ($this->isBackupExists($imageFile)) {
            $imageMediaPath = $this->getImageMediaPath($imageFile);
            $imageBackupPath = $this->getImageBackupPath($imageFile);
            $this->mediaDirectoryWrite->copyFile($imageBackupPath, $imageMediaPath);
            $this->deleteProductImageFromBackup($imageFile);
        }
    }

    /**
     * Check if image exists
     *
     * @param string $imageFile
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function isImageExists($imageFile)
    {
        $imageMediaPath = $this->getImageMediaPath($imageFile);

        return $this->mediaDirectoryWrite->isExist($imageMediaPath)
            && $this->mediaDirectoryWrite->isFile($imageMediaPath);
    }

    /**
     * Check if image backup exists
     *
     * @param string $imageFile
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function isBackupExists($imageFile)
    {
        $imageBackupPath = $this->getImageBackupPath($imageFile);

        return $this->mediaDirectoryWrite->isExist($imageBackupPath)
            && $this->mediaDirectoryWrite->isFile($imageBackupPath);
    }

    /**
     * Return image media path
     *
     * @param $imageFile
     * @return string
     */
    private function getImageMediaPath($imageFile)
    {
        return $this->catalogMediaConfig->getMediaPath($imageFile);
    }

    /**
     * Return image backup media path
     *
     * @param $imageFile
     * @return string
     */
    private function getImageBackupPath($imageFile)
    {
        return $this->catalogMediaConfig->getTmpMediaPath($imageFile . self::BACKUP_FILE_SUFFIX);
    }
}
