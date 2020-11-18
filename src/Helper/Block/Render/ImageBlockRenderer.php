<?php

namespace Kuusamo\Vle\Helper\Block\Render;

class ImageBlockRenderer extends BlockRenderer
{
    public function render(): string
    {
        return $this->templating->renderTemplate('blocks/image.html', [
            'image' => $this->block->getImage()
        ]);
    }
}
