<?php

namespace Kuusamo\Vle\Helper\Block\Render;

class MarkdownBlockRenderer extends BlockRenderer
{
    public function render(): string
    {
        return $this->block->toHtml();
    }
}
