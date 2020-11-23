<?php

namespace Kuusamo\Vle\Helper\Block\Render;

use Kuusamo\Vle\Entity\Block\Block;
use Kuusamo\Vle\Service\Templating\Templating;

abstract class BlockRenderer
{
    protected $block;
    protected $templating;

    /**
     * Constructor. Templating is optional as not all block need it.
     */
    public function __construct(Block $block, Templating $templating = null)
    {
        $this->block = $block;
        $this->templating = $templating;
    }

    abstract public function render();
}
