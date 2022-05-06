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

use Aheadworks\Minifier\Api\Data\ImageInterface;
use Magento\Framework\DataObject;

/**
 * Class Image
 * @package Aheadworks\Minifier\Model
 */
class Image extends DataObject implements ImageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBase64EncodedData()
    {
        return $this->getData(self::BASE64_ENCODED_DATA);
    }

    /**
     * {@inheritdoc}
     */
    public function setBase64EncodedData($data)
    {
        return $this->setData(self::BASE64_ENCODED_DATA, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($mimeType)
    {
        return $this->setData(self::TYPE, $mimeType);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaGalleryId()
    {
        return $this->getData(self::MEDIA_GALLERY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setMediaGalleryId($id)
    {
        return $this->setData(self::MEDIA_GALLERY_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    public function getFile()
    {
        return $this->getData(self::FILE);
    }

    public function setFile($file)
    {
        return $this->setData(self::FILE, $file);
    }
}
