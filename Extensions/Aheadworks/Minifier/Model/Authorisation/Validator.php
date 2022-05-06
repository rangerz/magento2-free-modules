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
namespace Aheadworks\Minifier\Model\Authorisation;

use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Webapi\Rest\Request as RestRequest;

/**
 * Class Validator
 * @package Aheadworks\Minifier\Model\Authorisation
 */
class Validator
{
    const AUTHORISATION_HEADER = 'awMinifierAuth';

    /**
     * @var HashManagement
     */
    private $hashManager;

    /**
     * @param HashManagement $hashManager
     */
    public function __construct(
        HashManagement $hashManager
    ) {
        $this->hashManager = $hashManager;
    }

    /**
     * Validate request authorisation
     *
     * @param RestRequest $request
     * @return bool
     * @throws AuthorizationException
     */
    public function validate($request)
    {
        $authHash = $request->getHeader(self::AUTHORISATION_HEADER);
        $clientAuthHash = $this->hashManager->getClientAuthHash();

        if (!$authHash || !$clientAuthHash || $authHash !== $clientAuthHash) {
            throw new AuthorizationException(__('Access not allowed'));
        }

        return true;
    }
}
