<?php

namespace Kuusamo\Vle\Helper\Block\Render;

class VideoBlockRenderer extends BlockRenderer
{
    public function render(): string
    {
        $providerTemplate = $this->getProviderTemplate($this->block->getProvider());

        if ($providerTemplate === null) {
            return '';
        }

        return $this->templating->renderTemplate(
            sprintf('blocks/video-%s.html', $providerTemplate),
            ['providerId' => $this->block->getProviderId()]
        );
    }

    /**
     * Work out which template to use.
     *
     * @param string $provider Provider name.
     * @return string|null
     */
    private function getProviderTemplate($provider)
    {
        if (in_array($provider, ['youtube', 'vimeo'])) {
            return $provider;
        }

        return null;
    }
}
