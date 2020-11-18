<?php

namespace Kuusamo\Vle\Helper\Block\Render;

class AudioBlockRenderer extends BlockRenderer
{
    public function render(): string
    {
        return $this->templating->renderTemplate('blocks/audio.html', [
            'file' => $this->block->getFile()
        ]);
    }
}
