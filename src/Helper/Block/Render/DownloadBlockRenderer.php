<?php

namespace Kuusamo\Vle\Helper\Block\Render;

class DownloadBlockRenderer extends BlockRenderer
{
    public function render(): string
    {
        return $this->templating->renderTemplate('blocks/download.html', [
            'file' => $this->block->getFile()
        ]);
    }
}
