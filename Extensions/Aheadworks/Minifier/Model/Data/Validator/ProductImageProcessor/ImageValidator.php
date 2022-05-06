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
namespace Aheadworks\Minifier\Model\Data\Validator\ProductImageProcessor;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Minifier\Api\Data\ImageInterface;
use \Magento\Framework\Api\ImageContentValidatorInterface;

/**
 * Class ImageValidator
 * @package Aheadworks\Minifier\Model\Data\Validator\ProductImageProcessor
 */
class ImageValidator extends AbstractValidator
{
    /**
     * @var ImageContentValidatorInterface
     */
    private $imageContentValidator;

    /**
     * @param ImageContentValidatorInterface $imageContentValidator
     */
    public function __construct(
        ImageContentValidatorInterface $imageContentValidator
    ) {
        $this->imageContentValidator = $imageContentValidator;
    }

    /**
     * @param ImageInterface $image
     * @return bool
     */
    public function isValid($image)
    {
        $this->_clearMessages();
        $messages = [];

        if (!$image->getMediaGalleryId()) {
            $messages[] = __('Image mediaGalleryId cannot be empty');
        }
        if (!$image->getBase64EncodedData()) {
            $messages[] = __('Image base64EncodedData cannot be empty');
        }
        if (!$image->getType()) {
            $messages[] = __('Image type cannot be empty');
        }
        if (!$image->getName()) {
            $messages[] = __('Image name cannot be empty');
        }
        if (!$image->getFile()) {
            $messages[] = __('Image file cannot be empty');
        }

        try {
            $this->imageContentValidator->isValid($image);
        } catch (InputException $exception) {
            $messages[] = __($exception->getMessage());
        }

        $this->_addMessages($messages);

        return empty($this->getMessages());
    }
}
