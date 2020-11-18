<?php

namespace Kuusamo\Vle\Helper\Block\Render;

use Kuusamo\Vle\Entity\Block\Block;
use Kuusamo\Vle\Entity\Block\AudioBlock;
use Kuusamo\Vle\Entity\Block\DownloadBlock;
use Kuusamo\Vle\Entity\Block\ImageBlock;
use Kuusamo\Vle\Entity\Block\MarkdownBlock;
use Kuusamo\Vle\Entity\Block\VideoBlock;
use Kuusamo\Vle\Service\Templating;
use Exception;

class BlockRendererFactory
{
    /**
     * Get a renderer for a speciic block.
     *
     * @param Block      $block      Block to render.
     * @param Templating $templating Templating engine.
     * $return Renderer
     */
    public static function get(Block $block, Templating $templating): BlockRenderer
    {
        if ($block instanceof AudioBlock) {
            return new AudioBlockRenderer($block, $templating);
        } elseif ($block instanceof DownloadBlock) {
            return new DownloadBlockRenderer($block, $templating);
        } elseif ($block instanceof ImageBlock) {
            return new ImageBlockRenderer($block, $templating);
        } elseif ($block instanceof MarkdownBlock) {
            return new MarkdownBlockRenderer($block);
        } elseif ($block instanceof VideoBlock) {
            return new VideoBlockRenderer($block, $templating);
        }

        // @This is untestable without a fake block type
        throw new Exception(sprintf(
            'Block type "%s" not supported.',
            get_class($block)
        ));
    }
}
