<?php

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Entity\Block\AudioBlock;

class AudioHydrator extends Hydrator
{
    public function hydrate(AudioBlock $block, array $data)
    {
        $file = $this->db->find(
            'Kuusamo\Vle\Entity\File',
            $data['file']['id']
        );

        if ($file) {
            $block->setFile($file);
        }
    }

    public function validate(AudioBlock $block): bool
    {
        if (($block->getFile() instanceof File) === false) {
            throw new ValidationException('Invalid file ID');
        }

        return true;
    }
}
