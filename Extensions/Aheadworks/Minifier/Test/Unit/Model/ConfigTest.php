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

namespace Aheadworks\Buildify\Test\Unit\Model;

use Aheadworks\Minifier\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\Config\Backend\Encrypted;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package Aheadworks\Buildify\Test\Unit\Model
 */
class ConfigTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var Encrypted|\PHPUnit_Framework_MockObject_MockObject
     */
    private $encrypted;

    /**
     * @var Config
     */
    private $config;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfigMock = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->encrypted = $this->createPartialMock(
            Encrypted::class,
            ['processValue']
        );
        $this->config = $objectManager->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfigMock,
                'encryptor' => $this->encrypted
            ]
        );
    }

    /**
     * Test getPublicApiKey method
     */
    public function testGetPublicApiKey()
    {
        $expected = 'test_encoded_public_key';

        $this->scopeConfigMock->expects($this->once())
                              ->method('getValue')
                              ->with(Config::XML_PATH_PUBLIC_API_KEY)
                              ->willReturn($expected);

        $this->assertEquals($expected, $this->config->getPublicApiKey());
    }

    /**
     * Test getPublicApiKey method
     */
    public function testGetPrivateApiKey()
    {
        $expected = 'test_encoded_private_key';

        $this->scopeConfigMock->expects($this->once())
                              ->method('getValue')
                              ->with(Config::XML_PATH_PRIVATE_API_KEY)
                              ->willReturn($expected);

        $this->assertEquals($expected, $this->config->getPrivateApiKey());
    }

    /**
     * Test getDecryptedPublicApiKey method
     */
    public function testGetDecryptedPublicApiKey()
    {
        $expected = 'test_public_key';

        $this->encrypted->expects($this->once())
                        ->method('processValue')
                        ->willReturn($expected);

        $this->assertEquals($expected, $this->config->getDecryptedPublicApiKey());
    }

    /**
     * Test getDecryptedPrivateApiKey method
     */
    public function testGetDecryptedPrivateApiKey()
    {
        $expected = 'test_private_key';

        $this->encrypted->expects($this->once())
                        ->method('processValue')
                        ->willReturn($expected);

        $this->assertEquals($expected, $this->config->getDecryptedPrivateApiKey());
    }

    /**
     * Test isEnablePrefetching method
     */
    public function testIsEnablePrefetching()
    {
        $expected = 0;

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_ENABLE_PREFETCHING)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->config->isEnablePrefetching());
    }
}
