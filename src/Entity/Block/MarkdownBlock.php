<?php

namespace Kuusamo\Vle\Entity\Block;

use JsonSerializable;
use Parsedown;

/**
 * @Entity
 * @Table(name="blocks_markdown")
 */
class MarkdownBlock extends Block implements JsonSerializable
{
    /**
     * @Column(type="string")
     */
    private $markdown;

    public function getMarkdown(): string
    {
        return $this->markdown;
    }

    public function setMarkdown(string $value)
    {
        $this->markdown = $value;
    }

    public function toHtml(): string
    {
        $parsedown = new Parsedown;
        $parsedown->setSafeMode(true);
        return $parsedown->text($this->markdown);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => Block::TYPE_MARKDOWN,
            'markdown' => $this->markdown
        ];
    }
}
