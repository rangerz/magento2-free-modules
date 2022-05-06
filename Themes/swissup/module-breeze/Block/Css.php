<?php

namespace Swissup\Breeze\Block;

class Css extends \Magento\Framework\View\Element\AbstractBlock
{
    private $assetRepo;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->assetRepo = $assetRepo;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $items = [];
        $inlineCss = $this->_scopeConfig->getValue(
            'dev/css/use_css_critical_path',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        foreach ($this->getBundles() ?? [] as $name => $data) {
            if ($inlineCss) {
                $items[] = $this->renderCss($name);
            } else {
                $items[] = $this->renderLink($name, $data);
            }
            if (!isset($data['deferred']) || $data['deferred']) {
                if ($inlineCss) {
                    // magento will defer this style on it's own when inlineCss is used
                    $items[] = $this->renderLink('deferred-' . $name, $data);
                } else {
                    $items[] = $this->renderLink($name, $data, true);
                }
            }
        }

        return implode("\n", array_filter($items));
    }

    /**
     * @param string $name
     * @param array $data
     * @param boolean $deferred
     * @return string
     */
    private function renderLink($name, $data = [], $deferred = false)
    {
        $media = 'all';

        if (!empty($data['media'])) {
            $media = $data['media'];
        }

        if ($deferred) {
            $template = '<link rel="stylesheet" type="text/css" media="print" onload="media=\'' . $media . '\'" href="%s"/>';
            $href = 'css/deferred-' . $name . '.css';
        } else {
            $template = '<link rel="stylesheet" type="text/css" media="' . $media . '" href="%s"/>';
            $href = 'css/' . $name . '.css';
        }

        return sprintf($template, $this->getViewFileUrl($href));
    }

    /**
     * @param string $name
     * @return string
     */
    private function renderCSS($name)
    {
        try {
            $asset = $this->assetRepo->createAsset('css/' . $name . '.css', ['_secure' => 'false']);
            $content = $asset->getContent();
        } catch (\Exception $e) {
            return '';
        }

        $content = str_replace("\n", ' ', $content);
        $content = preg_replace('/\s{2,}/', ' ', $content);

        return '<style>' . $content . '</style>';
    }
}
