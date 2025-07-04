<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity\Block;

use Kuusamo\Vle\Entity\Image;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="blocks_images")
 */
class ImageBlock extends Block implements JsonSerializable
{
    /**
     * @ManyToOne(targetEntity="Kuusamo\Vle\Entity\Image")
     */
    private $image;

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $value)
    {
        $this->image = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => Block::TYPE_IMAGE,
            'image' => $this->image
        ];
    }
}
