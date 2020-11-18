<?php

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Entity\Block\ImageBlock;

class ImageHydrator extends Hydrator
{
    public function hydrate(ImageBlock $block, array $data)
    {
        $image = $this->db->find('Kuusamo\Vle\Entity\Image', $data['image']['id']);

        if ($image) {
            $block->setImage($image);
        }
    }

    public function validate(ImageBlock $block): bool
    {
        if (($block->getImage() instanceof Image) === false) {
            throw new ValidationException('Invalid image ID');
        }

        return true;
    }
}
