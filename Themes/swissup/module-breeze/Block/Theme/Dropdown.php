<?php

namespace Swissup\Breeze\Block\Theme;

use Magento\Framework\View\Element\Template;

class Dropdown extends Template
{
    protected $_template = 'Swissup_Breeze::theme/dropdown.phtml';

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $json;

    private $content;

    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        array $data = []
    ) {
        $this->json = $json;

        parent::__construct($context, $data);
    }

    protected function _toHtml()
    {
        if (!$this->getHref() && !$this->getChildHtml()) {
            return '';
        }

        return parent::_toHtml();
    }

    public function getCssClass(): string
    {
        $class = 'actions dropdown options switcher-options';

        if ($this->getData('chevron') === false || !$this->getChildHtml()) {
            $class .= ' no-chevron';
        }

        if ($this->getData('css_class')) {
            $class .= ' ' . $this->getData('css_class');
        }

        return $class;
    }

    public function getJsonConfig()
    {
        return $this->json->serialize([
            'dropdown' => [
                'dialog' => $this->getIsDialog(),
            ],
        ]);
    }
}
