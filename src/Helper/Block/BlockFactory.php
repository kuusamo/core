<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\Block\AudioBlock;
use Kuusamo\Vle\Entity\Block\DownloadBlock;
use Kuusamo\Vle\Entity\Block\ImageBlock;
use Kuusamo\Vle\Entity\Block\MarkdownBlock;
use Kuusamo\Vle\Entity\Block\QuestionBlock;
use Kuusamo\Vle\Entity\Block\VideoBlock;

class BlockFactory
{
    /**
     * Create a brand new block object.
     *
     * @param string $type Block type.
     * @return Block
     */
    public static function create($type)
    {
        switch ($type) {
            case 'audio':
                return new AudioBlock;
            case 'download':
                return new DownloadBlock;
            case 'image':
                return new ImageBlock;
            case 'markdown':
                return new MarkdownBlock;
            case 'question':
                return new QuestionBlock;
            case 'video':
                return new VideoBlock;
            default:
                throw new BlockException(sprintf('Block type %s not supported', $type));
        }
    }
}
