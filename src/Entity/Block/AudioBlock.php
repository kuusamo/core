<?php

namespace Kuusamo\Vle\Entity\Block;

use Kuusamo\Vle\Entity\File;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="blocks_audio")
 */
class AudioBlock extends Block implements JsonSerializable
{
    /**
     * @ManyToOne(targetEntity="Kuusamo\Vle\Entity\File")
     */
    private $file;

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $value)
    {
        $this->file = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => Block::TYPE_AUDIO,
            'file' => $this->file
        ];
    }
}
