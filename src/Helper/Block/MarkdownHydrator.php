<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\Block\MarkdownBlock;

class MarkdownHydrator extends Hydrator
{
    public function hydrate(MarkdownBlock $block, array $data)
    {
        $block->setMarkdown($data['markdown']);
    }

    public function validate(MarkdownBlock $block): bool
    {
        if ($block->getMarkdown() == '') {
            throw new ValidationException('No markdown content');
        }

        return true;
    }
}
