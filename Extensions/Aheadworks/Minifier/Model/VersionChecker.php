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

use Magento\Framework\Module\ModuleListInterface;

/**
 * Class VersionChecker
 * @package Aheadworks\Minifier\Model
 */
class VersionChecker
{
    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
    }
    
    /**
     * Get module version
     *
     * @return string
     */
    public function getModuleVersion()
    {
        $moduleInfo = $this->moduleList->getOne('Aheadworks_Minifier');

        return $moduleInfo['setup_version'];
    }
}