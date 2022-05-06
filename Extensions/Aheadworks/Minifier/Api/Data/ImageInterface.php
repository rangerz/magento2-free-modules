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

use Magento\Framework\Api\Data\ImageContentInterface;

/**
 * Interface ImageInterface
 * @package Aheadworks\Minifier\Api\Data
 */
interface ImageInterface extends ImageContentInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const MEDIA_GALLERY_ID = 'media_gallery_id';
    const URL = 'url';
    const FILE = 'file';
    /**#@-*/

    /**
     * Get media gallery id
     *
     * @return int
     */
    public function getMediaGalleryId();

    /**
     * Set media gallery id
     *
     * @param int $id
     * @return $this
     */
    public function setMediaGalleryId($id);

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set URL
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Get file
     *
     * @return string
     */
    public function getFile();

    /**
     * Set file
     *
     * @param string $file
     * @return $this
     */
    public function setFile($file);
}
