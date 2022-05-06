<?php

namespace Swissup\Breeze\Block;

class Preload extends \Magento\Framework\View\Element\AbstractBlock
{
    private $items = [];

    private $allowedAttributes = [
        'as',
        'href',
        'imagesrcset',
        'imagesizes',
    ];

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $items = [];

        foreach ($this->items as $attributes) {
            $renderedAttributes = [];
            foreach ($attributes as $name => $value) {
                if (!$value || !in_array($name, $this->allowedAttributes)) {
                    continue;
                }
                $renderedAttributes[] = $name . '="' . $value . '"';
            }
            $renderedAttributes = join(' ', $renderedAttributes);

            $items[] = '<link rel="preload" ' . $renderedAttributes . '/>';
        }

        return implode("\n", $items);
    }

    public function addItem($attributes)
    {
        $this->items[] = $attributes;
    }
}
